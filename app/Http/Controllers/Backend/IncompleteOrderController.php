<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\IncompleteOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\Settings;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncompleteOrderController extends Controller
{
    public function index(Request $request)
    {
        $settings = Settings::first();
        $last = IncompleteOrder::orderBy('id', 'desc')->first();
        $users = User::get();
        $products = Product::latest()->select('name', 'id')->get();

        // Build query
        $query = IncompleteOrder::with('product')->where('created_at', '<=', \Illuminate\Support\Facades\Date::now()->subMinutes(30));

        if (auth()->user()->role == 3) {
            $query->where('user_id', auth()->user()->id);
        }

        // Filtering by status
        $statusFilter = $request->query('status');
        if ($statusFilter === 'cancelled') {
            $query->where('status', 1);
        } elseif ($statusFilter === 'incomplete') {
            $query->where('status', 0);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q): void {
                $sub->where('token', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $incompletes = $query->latest('last_activity_at')
            ->paginate(15)
            ->appends($request->except('page'));

        // Calculate stats
        $baseQuery = IncompleteOrder::where('created_at', '<=', \Illuminate\Support\Facades\Date::now()->subMinutes(30));
        if (auth()->user()->role == 3) {
            $baseQuery->where('user_id', auth()->user()->id);
        }

        $totalOrders = $baseQuery->count();
        $totalIncomplete = (clone $baseQuery)->where('status', 0)->count();
        $totalCancelled = (clone $baseQuery)->where('status', 1)->count();

        return view('backend.incomplete-order.index', compact('settings', 'products', 'last', 'users', 'incompletes', 'totalIncomplete', 'totalCancelled', 'statusFilter', 'totalOrders'));
    }

    // Show details
    public function show($id)
    {
        $order = IncompleteOrder::findOrFail($id);

        return view('backend.incomplete-order.show', compact('order'));
    }

    // Edit form
    public function edit($id)
    {
        $order = IncompleteOrder::findOrFail($id);
        $users = User::get();

        return view('backend.incomplete-order.edit', compact('order', 'users'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $order = IncompleteOrder::findOrFail($id);

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:191'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'status' => ['nullable', 'integer'],
            'shipping_method_label' => ['nullable', 'string'],
            'shipping_amount' => ['nullable', 'numeric'],
            'sub_total' => ['nullable', 'numeric'],
            'total' => ['nullable', 'numeric'],
            'user_id' => ['nullable', 'exists:users,id'],
            'completed_at' => ['nullable', 'date'],
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $order->update($data);

        return to_route('order.incomplete')->with('success', 'Incomplete order updated successfully.');
    }

    // Delete - only admins
    public function destroy($id)
    {
        // Only admins (role 1) can delete
        if (auth()->user()->role != 1) {
            return to_route('order.incomplete')->with('error', 'Unauthorized: Only admins can delete incomplete orders.');
        }

        $order = IncompleteOrder::findOrFail($id);
        $order->delete();

        return to_route('order.incomplete')->with('success', 'Incomplete order deleted.');
    }

    // Cancel incomplete order
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $order = IncompleteOrder::findOrFail($id);
        $order->update([
            'status' => 1,
            'cancellation_reason' => $request->input('cancellation_reason'),
        ]);

        return to_route('order.incomplete')->with('success', 'Incomplete order cancelled successfully.');
    }

    public function bulkDelete(Request $request)
    {
        // Only admins (role 1) can bulk delete
        if (auth()->user()->role != 1) {
            return response()->json(['error' => 'Unauthorized: Only admins can delete incomplete orders.'], 403);
        }

        Log::info('Bulk delete called', $request->all());

        $ids = $request->ids;

        if (! $ids || count($ids) == 0) {
            Log::warning('No IDs provided for bulk delete');

            return response()->json(['error' => 'No orders selected.'], 400);
        }

        try {
            IncompleteOrder::whereIn('id', $ids)->delete();
            Log::info('Deleted IDs:', $ids);

            return response()->json(['success' => 'Selected incomplete orders deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Bulk delete error: '.$e->getMessage());

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    public function bulkConvert(Request $request, WhatsAppService $whatsAppService)
    {
        $authUser = \Illuminate\Support\Facades\Auth::user();

        if (! $authUser || $authUser->role != 1) {
            return response()->json(['error' => 'Unauthorized: Only admins can convert incomplete orders.'], 403);
        }

        $ids = (array) $request->input('ids', []);

        if (count($ids) === 0) {
            return response()->json(['error' => 'No orders selected.'], 400);
        }

        $incompletes = IncompleteOrder::whereIn('id', $ids)->get();
        $converted = 0;
        $skipped = 0;
        $skippedCancelledIds = [];
        $skippedFailedIds = [];

        foreach ($incompletes as $incomplete) {
            if ((int) $incomplete->status === 1) {
                $skipped++;
                $skippedCancelledIds[] = $incomplete->id;

                continue;
            }

            try {
                DB::transaction(function () use ($incomplete, $whatsAppService): void {
                    $this->convertIncompleteToOrder($incomplete, $whatsAppService);
                });
                $converted++;
            } catch (\Throwable $e) {
                Log::error('Bulk convert failed', [
                    'incomplete_order_id' => $incomplete->id,
                    'message' => $e->getMessage(),
                ]);
                $skipped++;
                $skippedFailedIds[] = $incomplete->id;
            }
        }

        return response()->json([
            'success' => "Converted {$converted} order(s). Skipped {$skipped} order(s).",
            'converted' => $converted,
            'skipped' => $skipped,
            'skipped_cancelled_ids' => $skippedCancelledIds,
            'skipped_failed_ids' => $skippedFailedIds,
        ]);
    }

    public function bulkCancel(Request $request)
    {
        $authUser = \Illuminate\Support\Facades\Auth::user();

        if (! $authUser || $authUser->role != 1) {
            return response()->json(['error' => 'Unauthorized: Only admins can cancel incomplete orders in bulk.'], 403);
        }

        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer'],
            'cancellation_reason' => ['required', 'string', 'max:500'],
        ]);

        $ids = collect($validated['ids'])
            ->map(static fn ($id) => (int) $id)
            ->filter(static fn ($id) => $id > 0)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return response()->json(['error' => 'No valid orders selected.'], 422);
        }

        $reason = trim($validated['cancellation_reason']);
        if ($reason === '') {
            return response()->json(['error' => 'Cancellation note is required.'], 422);
        }

        $incompletes = IncompleteOrder::whereIn('id', $ids)->get();

        if ($incompletes->isEmpty()) {
            return response()->json(['error' => 'No matching incomplete orders found.'], 404);
        }

        $cancelled = 0;
        $skipped = 0;
        $skippedCancelledIds = [];

        foreach ($incompletes as $incomplete) {
            if ((int) $incomplete->status === 1) {
                $skipped++;
                $skippedCancelledIds[] = $incomplete->id;
                continue;
            }

            $incomplete->update([
                'status' => 1,
                'cancellation_reason' => $reason,
            ]);
            $cancelled++;
        }

        return response()->json([
            'success' => "Cancelled {$cancelled} order(s). Skipped {$skipped} order(s).",
            'cancelled' => $cancelled,
            'skipped' => $skipped,
            'skipped_cancelled_ids' => $skippedCancelledIds,
        ]);
    }

    private function convertIncompleteToOrder(IncompleteOrder $incomplete, WhatsAppService $whatsAppService): void
    {
        $slugs = [];

        if (isset($incomplete->cart_snapshot) && is_array($incomplete->cart_snapshot) && ! empty($incomplete->cart_snapshot)) {
            foreach ($incomplete->cart_snapshot as $item) {
                if (! empty($item['slug'])) {
                    $slugs[] = $item['slug'];
                }

                if (! empty($item['product_id'])) {
                    $product = Product::find($item['product_id']);
                    if ($product && ! empty($product->slug)) {
                        $slugs[] = $product->slug;
                    }
                }
            }
        }

        if (! empty($incomplete->product_slug)) {
            $slugs[] = $incomplete->product_slug;
        }

        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && $user->role == 3) {
            $assignedUser = $user;
        } elseif ($incomplete->user_id) {
            $assignedUser = $incomplete->user;
        } else {
            $assignedUser = User::where('role', 3)->where('status', 1)->inRandomOrder()->first();
        }

        $order = new Order;
        $order->name = $incomplete->name;
        $order->phone = $incomplete->phone;
        $order->address = $incomplete->address;
        $order->shipping_method = $incomplete->shipping_method_label ?? null;
        $order->product_id = $incomplete->product_id ?? null;
        $order->shipping_cost = $incomplete->shipping_amount ?? 0;
        $order->sub_total = $incomplete->sub_total ?? 0;
        $order->total = $incomplete->total ?? 0;
        $order->status = 1;
        $order->ip_address = $incomplete->ip_address;
        $order->order_assign = $assignedUser?->id ?? null;
        $order->order_type = Order::TYPE_INCOMPLETE;
        $order->product_slug = ! empty($slugs) ? json_encode(array_values(array_unique($slugs))) : null;
        $order->save();

        $cart = new Cart;
        $product = $incomplete->product;
        $cart->product_id = $product->id ?? null;
        $cart->order_id = $order->id;
        $cart->quantity = 1;
        $cart->price = $incomplete->sub_total;
        $cart->save();

        $whatsAppService->sendOrderNotification($order);

        $incomplete->delete();
    }
}

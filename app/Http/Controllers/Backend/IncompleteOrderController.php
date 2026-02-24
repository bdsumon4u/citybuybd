<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\IncompleteOrder;
use App\Models\Product;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
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
}

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
        $status = 0;
        $users = User::get();
        $products = Product::latest()->select('name', 'id')->get();

        // Optional search
        $query = IncompleteOrder::with('product')->where('created_at', '<=', \Illuminate\Support\Facades\Date::now()->subMinutes(30)); // eager load product

        if (auth()->user()->role == 3) {
            $query->where('user_id', auth()->user()->id);
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

        return view('backend.incomplete-order.index', compact('settings', 'products', 'last', 'status', 'users', 'incompletes'));
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
        ]);

        $order->update($data);

        return to_route('order.incomplete')->with('success', 'Incomplete order updated successfully.');
    }

    // Delete
    public function destroy($id)
    {
        $order = IncompleteOrder::findOrFail($id);
        $order->delete();

        return to_route('order.incomplete')->with('success', 'Incomplete order deleted.');
    }

    public function bulkDelete(Request $request)
    {
        Log::info('Bulk delete called', $request->all()); // Log request data

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

<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;

class OrderAssigneeService
{
    /**
     * Select an assignee for the order, prioritizing assigned employees on products.
     */
    public function selectAssignee(?array $productIds = null): ?int
    {
        $now = now()->format('H:i:s');
        $ids = array_values(array_unique(array_filter(
            array_map('intval', \Illuminate\Support\Arr::flatten($productIds ?? []))
        )));

        // 1. Try assigned employee who is available now
        if (! empty($ids)) {
            $product = Product::whereIn('id', $ids)
                ->whereHas('assignedEmployees', function ($q) use ($now) {
                    $q->where('status', 1)
                        ->whereRaw('? >= COALESCE(order_start, start_time)', [$now])
                        ->whereRaw('? <= COALESCE(order_end, end_time)', [$now]);
                })
                ->first();
            if ($product) {
                $employee = $this->randomUserId(
                    $product->assignedEmployees()
                        ->where('status', 1)
                        ->whereRaw('? >= COALESCE(order_start, start_time)', [$now])
                        ->whereRaw('? <= COALESCE(order_end, end_time)', [$now])
                );
                if ($employee) {
                    return (int) $employee;
                }
            }

            // 2. Try assigned employee regardless of time
            $product = Product::whereIn('id', $ids)
                ->whereHas('assignedEmployees', function ($q) {
                    $q->where('status', 1);
                })
                ->first();
            if ($product) {
                $employee = $this->randomUserId(
                    $product->assignedEmployees()
                        ->where('status', 1)
                );
                if ($employee) {
                    return (int) $employee;
                }
            }
        }

        // 3. Fallback: any active employee available now
        $employee = User::where('status', 1)
            ->where('role', 3)
            ->whereRaw('? >= COALESCE(order_start, start_time)', [$now])
            ->whereRaw('? <= COALESCE(order_end, end_time)', [$now])
            ->inRandomOrder()
            ->value('id');
        if ($employee) {
            return (int) $employee;
        }

        // 4. Last resort: any active employee regardless of time
        $employee = User::where('status', 1)
            ->where('role', 3)
            ->inRandomOrder()
            ->value('id');
        if ($employee) {
            return (int) $employee;
        }

        // 5. Manager/Admin fallback (legacy)
        $priority = [
            ['role' => 2, 'status' => 1], // Active Manager
            ['role' => 1, 'status' => 1], // Active Admin
            ['role' => 3, 'status' => null], // Inactive Employee
            ['role' => 2, 'status' => null], // Inactive Manager
            ['role' => 1, 'status' => null], // Inactive Admin
        ];
        foreach ($priority as $criteria) {
            $query = User::query()->where('role', $criteria['role']);
            if ($criteria['status'] !== null) {
                $query->where('status', $criteria['status']);
            }
            $id = $query->inRandomOrder()->value('id');
            if ($id) {
                return (int) $id;
            }
        }

        return null;
    }

    /**
     * Helper to avoid ambiguous column errors for value('id') on belongsToMany.
     * Always use users.id as alias.
     */
    private function randomUserId($query)
    {
        return $query->select('users.id')->inRandomOrder()->value('id');
    }
}

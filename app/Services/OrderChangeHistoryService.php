<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\OrderChangeHistory;
use App\Models\User;

class OrderChangeHistoryService
{
    public function recordStatusChange(Order $order, ?User $actor, int $oldStatus, int $newStatus, string $source): void
    {
        if ($oldStatus === $newStatus) {
            return;
        }

        OrderChangeHistory::create([
            'order_id' => $order->id,
            'changed_by' => $actor?->id,
            'field_name' => 'status',
            'old_value' => $this->formatStatusValue($oldStatus),
            'new_value' => $this->formatStatusValue($newStatus),
            'source' => $source,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'changed_at' => now(),
        ]);
    }

    public function recordAssignedUserChange(Order $order, ?User $actor, ?int $oldUserId, ?int $newUserId, string $source): void
    {
        if ((int) $oldUserId === (int) $newUserId) {
            return;
        }

        OrderChangeHistory::create([
            'order_id' => $order->id,
            'changed_by' => $actor?->id,
            'field_name' => 'order_assign',
            'old_value' => $this->formatAssignedValue($oldUserId),
            'new_value' => $this->formatAssignedValue($newUserId),
            'source' => $source,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'changed_at' => now(),
        ]);
    }

    private function formatStatusValue(int $status): string
    {
        return Order::STATUS_MAP[$status] ?? 'unknown';
    }

    private function formatAssignedValue(?int $userId): string
    {
        if (! $userId) {
            return 'Unassigned';
        }

        $user = User::find($userId);
        if (! $user) {
            return (string) $userId;
        }

        return $user->id.' ('.$user->name.')';
    }
}

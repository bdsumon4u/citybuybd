<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class OrderProtectionService
{
    public const LOCKED_STATUSES = [
        Order::STATUS_COMPLETED,
        Order::STATUS_PARTIAL_DELIVERY,
        Order::STATUS_ORDER_RETURN,
        Order::STATUS_PAID_RETURN,
        Order::STATUS_PENDING_RETURN,
    ];

    public function isLockedStatus(?int $status): bool
    {
        return in_array((int) $status, self::LOCKED_STATUSES, true);
    }

    public function isLockedOrder(Order $order): bool
    {
        return $this->isLockedStatus((int) $order->status);
    }

    public function validateMutation(
        ?User $actor,
        Order $order,
        bool $statusChanged,
        bool $assignedChanged,
        ?string $secretKey = null,
    ): ?string {
        if (! $statusChanged && ! $assignedChanged) {
            return null;
        }

        if (! $this->isLockedOrder($order)) {
            return null;
        }

        $isAdmin = $actor && (int) $actor->role === 1;

        if (! $isAdmin) {
            return 'This order is already delivered/returned. Only admin can change status (with secret key).';
        }

        if ($assignedChanged) {
            return 'Assigned employee cannot be changed after delivered/returned status, even by admin.';
        }

        if ($statusChanged && ! $this->isValidSecretKey($secretKey)) {
            return 'Invalid secret key. Admin must enter the correct secret key to change this status.';
        }

        return null;
    }

    private function isValidSecretKey(?string $secretKey): bool
    {
        $configuredSecret = (string) config('order.final_status_override_secret', '');

        if ($configuredSecret === '' || $secretKey === null) {
            return false;
        }

        return hash_equals($configuredSecret, (string) $secretKey);
    }
}

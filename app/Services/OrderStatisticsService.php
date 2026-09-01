<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Models\ManualOrderType;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderStatisticsService
{
    public const STATUS_LABELS = [
        Order::STATUS_PROCESSING => 'Processing',
        Order::STATUS_PENDING_DELIVERY => 'Courier Entry',
        Order::STATUS_ON_HOLD => 'On Hold',
        Order::STATUS_CANCEL => 'Cancelled',
        Order::STATUS_COMPLETED => 'Delivered (Completed)',
        Order::STATUS_PENDING_PAYMENT => 'Pending Payment',
        Order::STATUS_ON_DELIVERY => 'On Delivery',
        Order::STATUS_NO_RESPONSE1 => 'No Response 1',
        Order::STATUS_NO_RESPONSE2 => 'No Response 2',
        Order::STATUS_COURIER_HOLD => 'Courier Hold',
        Order::STATUS_ORDER_RETURN => 'Order Return',
        Order::STATUS_PARTIAL_DELIVERY => 'Partial Delivery',
        Order::STATUS_PAID_RETURN => 'Paid Return',
        Order::STATUS_STOCK_OUT => 'Stock Out',
        Order::STATUS_TOTAL_DELIVERY => 'Total Courier',
        Order::STATUS_PRINTED_INVOICE => 'Printed Invoice',
        Order::STATUS_PENDING_RETURN => 'Pending Return',
        Order::STATUS_DOUBLE => 'Double Order',
    ];

    public const STATUS_BADGES = [
        Order::STATUS_PROCESSING => 'badge-info',
        Order::STATUS_PENDING_DELIVERY => 'badge-primary',
        Order::STATUS_ON_HOLD => 'badge-warning',
        Order::STATUS_CANCEL => 'badge-danger',
        Order::STATUS_COMPLETED => 'badge-success',
        Order::STATUS_PENDING_PAYMENT => 'badge-warning',
        Order::STATUS_ON_DELIVERY => 'badge-primary',
        Order::STATUS_NO_RESPONSE1 => 'badge-secondary',
        Order::STATUS_NO_RESPONSE2 => 'badge-secondary',
        Order::STATUS_COURIER_HOLD => 'badge-warning',
        Order::STATUS_ORDER_RETURN => 'badge-danger',
        Order::STATUS_PARTIAL_DELIVERY => 'badge-success',
        Order::STATUS_PAID_RETURN => 'badge-danger',
        Order::STATUS_STOCK_OUT => 'badge-danger',
        Order::STATUS_TOTAL_DELIVERY => 'badge-primary',
        Order::STATUS_PRINTED_INVOICE => 'badge-dark',
        Order::STATUS_PENDING_RETURN => 'badge-danger',
        Order::STATUS_DOUBLE => 'badge-secondary',
    ];

    /**
     * Get comprehensive monthly order statistics handled by a staff member.
     */
    public function getEmployeeMonthlyOrderStats(User $user, int $month, int $year): array
    {
        $manualTypes = ManualOrderType::pluck('name')->merge(['manual'])->unique()->filter()->values()->toArray();

        $assignedRows = Order::query()
            ->where('order_assign', $user->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->select(['order_type', 'status', DB::raw('count(*) as count')])
            ->groupBy('order_type', 'status')
            ->get();

        $totalHandled = (int) $assignedRows->sum('count');

        $onlineCount = 0;
        $manualCount = 0;
        $typeCounts = [];

        foreach ($assignedRows as $row) {
            $type = (string) ($row->order_type ?? Order::TYPE_ONLINE);
            $cnt = (int) $row->count;

            $typeCounts[$type] = ($typeCounts[$type] ?? 0) + $cnt;

            if ($type === Order::TYPE_ONLINE) {
                $onlineCount += $cnt;
            } else {
                $manualCount += $cnt;
            }
        }

        $onlinePercent = $totalHandled > 0 ? round(($onlineCount / $totalHandled) * 100, 1) : 0.0;
        $manualPercent = $totalHandled > 0 ? round(($manualCount / $totalHandled) * 100, 1) : 0.0;

        // Formatted type breakdown
        $byType = [];
        arsort($typeCounts);
        foreach ($typeCounts as $type => $count) {
            $byType[] = [
                'type' => $type,
                'category' => $type === Order::TYPE_ONLINE ? 'Online' : 'Manual',
                'count' => $count,
                'percent' => $totalHandled > 0 ? round(($count / $totalHandled) * 100, 1) : 0.0,
            ];
        }

        // Manual orders created by this user in the month
        $createdCount = Order::query()
            ->where('created_by', $user->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        // Delivered manual orders created by this user
        $createdDeliveredCount = Order::query()
            ->where('created_by', $user->id)
            ->where('status', OrderStatusEnum::Completed)
            ->whereNotNull('delivered_at')
            ->whereYear('delivered_at', $year)
            ->whereMonth('delivered_at', $month)
            ->count();

        return [
            'total_handled' => $totalHandled,
            'online_count' => $onlineCount,
            'online_percent' => $onlinePercent,
            'manual_count' => $manualCount,
            'manual_percent' => $manualPercent,
            'by_type' => $byType,
            'created_by_user_count' => $createdCount,
            'created_by_user_delivered' => $createdDeliveredCount,
        ];
    }
}

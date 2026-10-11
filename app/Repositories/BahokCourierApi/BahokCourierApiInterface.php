<?php

declare(strict_types=1);

namespace App\Repositories\BahokCourierApi;

use App\Models\Order;
use Illuminate\Http\Request;

interface BahokCourierApiInterface
{
    /**
     * Authenticate and obtain an access token.
     */
    public function getAccessToken(): ?string;

    /**
     * Create a delivery order in Bahok Courier.
     */
    public function createOrder(?Request $request, Order $order);

    /**
     * Create multiple delivery orders in a single request.
     */
    public function bulkCreateOrder(array $orders);

    /**
     * Retrieve short info about an order by consignment ID.
     */
    public function getOrderShortInfo(string|int $consignmentId);

    /**
     * Retrieve all available delivery districts.
     */
    public function getDistricts();

    /**
     * Retrieve thanas within a district.
     */
    public function getThanas(?int $districtId = null);

    /**
     * Retrieve areas within a thana.
     */
    public function getAreas(?int $thanaId = null);

    /**
     * Retrieve all available delivery hubs.
     */
    public function getHubs();

    /**
     * Retrieve all order statuses.
     */
    public function getStatuses();

    /**
     * Process webhook status update from Bahok Courier.
     */
    public function statusUpdate($request): bool;
}

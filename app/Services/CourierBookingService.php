<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Repositories\PathaoApi\PathaoApiInterface;
use App\Repositories\RedXApi\RedXApiInterface;
use App\Repositories\SteadFastApi\SteadFastApiInterface;
use Illuminate\Http\Request;

final class CourierBookingService
{
    private PathaoApiInterface $pathao;
    private RedXApiInterface $redX;
    private SteadFastApiInterface $steadfast;

    public function __construct(
        PathaoApiInterface $pathao,
        RedXApiInterface $redX,
        SteadFastApiInterface $steadfast
    ) {
        $this->pathao = $pathao;
        $this->redX = $redX;
        $this->steadfast = $steadfast;
    }

    public function bookOrder(Order $order, ?Request $request = null): array
    {
        if (!$order->courier) {
            return [
                'success' => false,
                'message' => 'No courier selected for this order.',
            ];
        }

        $courier = $order->courier;

        if ($courier == 1) {
            return $this->bookRedX($order, $request);
        } elseif ($courier == 3) {
            return $this->bookPathao($order, $request);
        } elseif ($courier == 4) {
            return $this->bookSteadFast($order, $request);
        }

        return [
            'success' => false,
            'message' => 'Invalid courier selected.',
        ];
    }

    private function bookRedX(Order $order, ?Request $request): array
    {
        $parcel = $this->redX->createOrder($request, $order);

        if (isset($parcel->tracking_id)) {
            $order->consignment_id = $parcel->tracking_id;
            $order->save();

            return [
                'success' => true,
                'message' => 'Order booked to RedX successfully.',
                'consignment_id' => $parcel->tracking_id,
            ];
        }

        if (!isset($parcel->status_code) && isset($parcel->validation_errors) && $parcel->validation_errors[0]) {
            return [
                'success' => false,
                'message' => 'Validation error: ' . $parcel->validation_errors[0],
                'errors' => $parcel->validation_errors,
            ];
        }

        if (isset($parcel->status_code) && $parcel->status_code == 401) {
            return [
                'success' => false,
                'message' => 'Authentication failed. Please check RedX credentials.',
                'errors' => $parcel,
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to book order to RedX.',
            'errors' => $parcel,
        ];
    }

    private function bookPathao(Order $order, ?Request $request): array
    {
        $parcel = $this->pathao->createOrder($request, $order);

        if ($parcel->type == 'success') {
            $order->consignment_id = $parcel->data->consignment_id;
            $order->save();

            return [
                'success' => true,
                'message' => 'Order booked to Pathao successfully.',
                'consignment_id' => $parcel->data->consignment_id,
            ];
        }

        if ($parcel->type == 'error') {
            return [
                'success' => false,
                'message' => 'Failed to book order to Pathao.',
                'errors' => $parcel->errors ?? [],
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to book order to Pathao.',
            'errors' => $parcel,
        ];
    }

    private function bookSteadFast(Order $order, ?Request $request): array
    {
        $parcel = $this->steadfast->createOrder($request, $order);

        if ($parcel->status == 200) {
            $order->consignment_id = $parcel->consignment->tracking_code;
            $order->save();

            return [
                'success' => true,
                'message' => 'Order booked to SteadFast successfully.',
                'consignment_id' => $parcel->consignment->tracking_code,
            ];
        }

        if ($parcel->status == 400) {
            return [
                'success' => false,
                'message' => 'Failed to book order to SteadFast.',
                'errors' => $parcel->errors ?? [],
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to book order to SteadFast.',
            'errors' => $parcel,
        ];
    }
}


<?php

namespace App\Repositories\SteadFastApi;

use App\Models\Order;
use App\Models\Settings;
use Illuminate\Support\Facades\Http;

class SteadFastApiRepository implements SteadFastApiInterface
{
    // create order
    public function createOrder($request, $order)
    {
        $settings = Settings::first();

        try {
            $post_data = [
                'invoice' => $order->id,
                'recipient_name' => $order->name ?? 'N/A',
                'recipient_address' => $order->address ?? 'N/A',
                'recipient_phone' => $order->phone ?? '',
                'cod_amount' => $order->total,
                'note' => '',
            ];
            $response = Http::withHeaders([
                'Api-Key' => $settings->steadfast_apikey,
                'Secret-Key' => $settings->steadfast_secretkey,
                'Content-Type' => 'application/json',
            ])->post('https://portal.steadfast.com.bd/api/v1/create_order', $post_data);

            return json_decode((string) $response->getBody()->getContents());
        } catch (\Throwable) {
            return false;
        }
    }

    // bulk create order
    public function BulkCreateOrder($request, $order)
    {
        $settings = Settings::first();
        try {
            $post_data = [];
            foreach ($request->orders as $order) {
                $post_data[] = [
                    'invoice' => $order->id,
                    'recipient_name' => $order->name ?? 'N/A',
                    'recipient_address' => $order->address ?? 'N/A',
                    'recipient_phone' => $order->phone ?? '',
                    'cod_amount' => (int) $order->total,
                    'note' => '',
                ];
            }

            $response = Http::withHeaders([
                'Api-Key' => $settings->steadfast_apikey,
                'Secret-Key' => $settings->steadfast_secretkey,
                'Content-Type' => 'application/json',
            ])->post('https://portal.steadfast.com.bd/api/v1/create_order/bulk-order', ['data' => json_encode($post_data)]);

            return json_decode((string) $response->getBody()->getContents());
        } catch (\Throwable) {
            return false;
        }
    }

    // delivery status checking
    public function checkingDeliveryStatus($type, $request)
    {
        $settings = Settings::first();
        try {

            if ($type == 'invoice') {
                $id = $request->id;
                $url = '/status_by_invoice/'.$id;
            } elseif ($type == 'tracking') {
                $id = $request->tracking_code;
                $url = '/status_by_trackingcode/'.$id;
            } else {
                $id = $request->consignment_id;
                $url = '/status_by_cid/'.$id;
            }

            $response = Http::withHeaders([
                'Api-Key' => $settings->steadfast_apikey,
                'Secret-Key' => $settings->steadfast_secretkey,
                'Content-Type' => 'application/json',
            ])->get('https://portal.steadfast.com.bd/api/v1/'.$url);

            return json_decode((string) $response->getBody()->getContents());
        } catch (\Throwable) {
            return false;
        }
    }

    // current balance checking
    public function checkingCurrentBalance()
    {
        $settings = Settings::first();
        try {

            $response = Http::withHeaders([
                'Api-Key' => $settings->steadfast_apikey,
                'Secret-Key' => $settings->steadfast_secretkey,
                'Content-Type' => 'application/json',
            ])->get('https://portal.steadfast.com.bd/api/v1//get_balance');

            return json_decode((string) $response->getBody()->getContents());
        } catch (\Throwable) {
            return false;
        }
    }
}

<?php

namespace App\Repositories\RedXApi;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Repositories\RedXApi\RedXApiInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Settings;


class RedXApiRepository implements RedXApiInterface
{

    //create order
    public function createOrder($request, $order)
    {
 $settings = Settings::first();
 
        try {
            $post_data = [
                "customer_name"      => $order->name ?? 'N/A',
                "customer_phone"     =>  $order->phone ?? '',
                "delivery_area"      => (string) $request->area_name,
                "delivery_area_id"   => $request->area_id,
                "customer_address"   => $order->address ?? 'N/A',
                "merchant_invoice_id"=>  (string)$order->id,
                "cash_collection_amount"=> $order->total,
                "parcel_weight"     => $order->weight,
                "instruction"       => "",
                "value"             => $order->total,
            ];

            $response = Http::withHeaders([
                'API-ACCESS-TOKEN'        => 'Bearer '.$settings->redx_token,
                'Content-Type'  => 'application/json'
            ])->post(\config('courier.redx_merchant.api_url').'/parcel', $post_data);
            return json_decode($response->getBody()->getContents());
        } catch (\Throwable $th) {
            return false;
        }
    }


    //get Areas
    public function getAreas()
    {
 $settings = Settings::first();
        try {
            $response = Http::withHeaders([
                'API-ACCESS-TOKEN'        => 'Bearer '.$settings->redx_token,
                'Content-Type'            => 'application/json'
            ])->get(\config('courier.redx_merchant.api_url').'/areas');
            return json_decode($response->getBody()->getContents());
        } catch (\Throwable $th) {
            return false;
        }
    }


    //webhook status update
    public function statusUpdate($request){
        try {
            $order = Order::where('courier',1)->where('consignment_id',$request->tracking_number)->first();
            if($order):
                if($request->status == 'ready-for-delivery')://ready for delivery
                    $order->status   = OrderStatus::Processing;
                elseif($request->status == 'delivery-in-progress'):// delivery in progress
                    $order->status   = OrderStatus::Processing;
                elseif($request->status == 'delivered')://  delivered
                    $order->status   = OrderStatus::Completed;
                elseif($request->status == 'agent-hold'):// agent hold
                    $order->status   = OrderStatus::On_Hold;
                elseif($request->status == 'agent-returning'):// agent returing

                elseif($request->status == 'returned'):// return
                    $order->status   = OrderStatus::Cancel;
                elseif($request->status == 'agent-area-change')://agent area change

                endif;
                $order->save();
            endif;
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }



}

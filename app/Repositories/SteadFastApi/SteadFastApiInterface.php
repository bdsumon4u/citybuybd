<?php
namespace App\Repositories\SteadFastApi;
interface SteadFastApiInterface { 
    public function createOrder($request,$parcelInfo);//create order
    public function BulkCreateOrder($request, $order);
    public function checkingDeliveryStatus($type,$request);//delivery status checking
    public function checkingCurrentBalance();//current balance check
}

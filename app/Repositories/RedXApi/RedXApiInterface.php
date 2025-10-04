<?php
namespace App\Repositories\RedXApi;
interface RedXApiInterface {
    public function createOrder($request,$parcelInfo);//create order
    public function getAreas();//get areas
    public function statusUpdate($request);//webhook
}

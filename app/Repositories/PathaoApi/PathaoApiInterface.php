<?php

namespace App\Repositories\PathaoApi;

interface PathaoApiInterface
{
    public function access_info(); // access token and refresh token

    public function getStores(); // get stores

    public function getCities(); // get cities

    public function getZones($city_id); // get zones

    public function getAreas($zone_id); // get areas

    public function createOrder($request, $parcelInfo); // create parcel

    public function statusUpdate($request);
}

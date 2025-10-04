<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\PathaoApi\PathaoApiInterface;
use Illuminate\Http\Request;

class PathaoController extends Controller
{

    protected $pathao;
    public function __construct(PathaoApiInterface $pathao)
    {
        $this->pathao    = $pathao;
    }
    public function GetStores(Request $request){
        $stores = $this->pathao->getStores();
        return view('backend.pages.pathao_get.stores',compact('stores'));
    }
    public function GetCities(Request $request){
        $cities = $this->pathao->getCities();
        return view('backend.pages.pathao_get.cities',compact('cities'));
    }
    public function GetZones(Request $request){
        $zones = $this->pathao->getZones($request->city_id);
        return view('backend.pages.pathao_get.zones',compact('zones'));
    }
    public function GetAreas(Request $request){

        $areas = $this->pathao->getAreas($request->zone_id);
        return view('backend.pages.pathao_get.areas',compact('areas'));
    }

    public function pathaoStatusUpdate(Request $request){

        if($this->pathao->statusUpdate($request)):
            return response()->json([
                'success'   => true,
                'message'   => 'Status updated successfully'
            ],200);
        else:
            return response()->json([
                'success'   => false,
                'message'   => 'Status update Failed.'
            ],400);
        endif;
    }

}

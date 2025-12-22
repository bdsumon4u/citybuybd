<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\PathaoApi\PathaoApiInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

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


        // Log::error("Webhook received for invalid store ID: $request->consignment_id");

        $this->statusUpdate($request);


        return response()->json(['message' => 'Webhook received'], 202)
            ->header('X-Pathao-Merchant-Webhook-Integration-Secret', 'f3992ecc-59da-4cbe-a049-a13da2018d51');


    }


      public function statusUpdate($request){


        //   Log::error("Webhook received for invalid store ID: $request->consignment_id");



            $status = [
                    "Pickup_Requested",
                    "Assigned_for_Pickup",
                    "Picked",
                    "Pickup_Failed",
                    "Pickup_Cancelled",
                    "At_the_Sorting_HUB",
                    "In_Transit",
                    "Received_at_Last_Mile_HUB",
                    "Assigned_for_Delivery",
                    "Delivered",
                    "Partial_Delivery",
                    "Return",
                    "Delivery_Failed",
                    "On_Hold",
                    "Payment_Invoice",
            ];
            $order = Order::where('consignment_id',$request->consignment_id)->first();

            // Log::error("Webhook received for invalid order ID: $order->id");

           if($request->event == 'order.assigned-for-pickup'):      //assgin for delivery man
                $order->courier_status   = 'Assigned_for_Pickup';

            elseif($request->event == 'order.picked'):     // pickup
               $order->status   = 7; //on delivery
               $order->courier_status   = 'Picked';

            elseif($request->event == 'order.pickup-cancelled'):// partial delivered
                $order->status   = 4;
                $order->courier_status   = 'Pickup_Cancelled';

            elseif($request->event == 'order.in-transit')://in transit
                $order->courier_status   = 'In_Transit';

            elseif($request->event == 'order.assigned-for-delivery'):      //assgin for delivery man
                $order->courier_status   = 'Assigned_for_Delivery';

            elseif($request->event == 'order.received-at-last-mile-hub')://receive  last mile hub
                $order->courier_status   = 'Received_at_Last_Mile_HUB';

            elseif($request->event == 'order.delivered'):     // delivered
                $order->status   = 5; //delivered
                $order->courier_status   = 'Delivered';

            elseif($request->event == 'order.on-hold'):     //  hold
                $order->status   = 11; //courier hold
                $order->courier_status   = 'On_Hold';

            elseif($request->event == 'order.returned'):    // return
                $order->status   = 12; //return
                $order->courier_status   = 'Return';

            elseif($request->event == 'order.partial-delivery'):    // partial delivery
                $order->status   = 13; //partial delivery
                $order->courier_status   = 'Partial_Delivery';

            elseif($request->event == 'order.paid-return'):    // paid return
                $order->status   = 14; //paid return
                $order->courier_status   = 'Paid_Return';

            elseif($request->event == 'order.stock-out'):    // stock out
                $order->status   = 15; //stock out
                $order->courier_status   = 'Stock_Out';

            endif;



            // if(in_array($request->payment_status,$status)):
            //     if($request->payment_status):
            //         $order->payment_status   = $request->payment_status;
            //     else:
            //         $order->courier_status   = $request->order_status;
            //     endif;
            // endif;

            $order->save();
            return true;

    }



}

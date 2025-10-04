<?php
namespace App\Repositories\PathaoApi;

use App\Models\Order;
use App\Repositories\PathaoApi\PathaoApiInterface;
use Illuminate\Support\Facades\Session;
use  App\Enums\OrderStatus;
use App\Models\Settings;

class PathaoApiRepository implements PathaoApiInterface{

    //authorization access information
    public function access_info(){
        
        $settings = Settings::first();

        $curl = curl_init();
        $token_postdata = [

            
            
            'client_id'     => $settings->pathao_client_id,
            'client_secret' => $settings->pathao_client_secret,
            'username'      => $settings->pathao_email,
            'password'      => $settings->pathao_password,
            'grant_type'    => "password",
        ];

            if(Session::has('pathao_access_token')):
                return Session::get('pathao_access_token');
            else:
                curl_setopt_array($curl,
                    [
                        CURLOPT_URL=> "https://api-hermes.pathao.com/aladdin/api/v1/issue-token",
                        CURLOPT_RETURNTRANSFER=>true,
                        CURLOPT_ENCODING=>"",
                        CURLOPT_MAXREDIRS=>10,
                        CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST=>"POST",
                        CURLOPT_POSTFIELDS   => json_encode($token_postdata),
                        CURLOPT_HTTPHEADER   => array(
                                "cache-control:no-cache",
                                "content-type:application/json"
                            )
                    ]
                );

                $token_response            = curl_exec($curl);
                $AuthorizationInfo         = json_decode($token_response);
                if(isset($AuthorizationInfo->type) == 'error'):
                    return '';
                elseif($AuthorizationInfo->access_token):
                    Session::put(['pathao_access_token'=>$AuthorizationInfo->access_token]);
                endif;
                return Session::get('pathao_access_token');
            endif;


    }

    //get stores
    public function getStores(){

        $curl =curl_init();
        curl_setopt_array($curl,
            [
                CURLOPT_URL=> "https://api-hermes.pathao.com/aladdin/api/v1/stores",
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_ENCODING=>"",
                CURLOPT_MAXREDIRS=>10,
                CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST=>"GET",
                CURLOPT_HTTPHEADER   => array(
                        "cache-control:no-cache",
                        "content-type:application/json",
                        "Authorization:Bearer ". @$this->access_info() //access token from access info method
                    )
            ]
        );

        $response = curl_exec($curl);
        $stores         = json_decode($response);

         if(@$stores->data->data == null):
            return [];
         else:
            return @$stores->data->data;
         endif;

    }

    //get cities
    public function getCities(){
        $curl = curl_init();
        curl_setopt_array($curl,
            [
                CURLOPT_URL=> "https://api-hermes.pathao.com/aladdin/api/v1/countries/1/city-list",
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_ENCODING=>"",
                CURLOPT_MAXREDIRS=>10,
                CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST=>"GET",
                CURLOPT_HTTPHEADER   => array(
                        "cache-control:no-cache",
                        "content-type:application/json",
                        "Authorization:Bearer ". @$this->access_info() //access token from access info method
                    )
            ]
        );

        $response = curl_exec($curl);
        $cities         = json_decode($response);
        if(@$cities->data->data == null):
            return [];
         else:
            return @$cities->data->data;
         endif;
    }


    //get zones
    public function getZones($city_id){
        $curl = curl_init();
        curl_setopt_array($curl,
            [
                CURLOPT_URL=> "https://api-hermes.pathao.com/aladdin/api/v1/cities/".$city_id."/zone-list",
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_ENCODING=>"",
                CURLOPT_MAXREDIRS=>10,
                CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST=>"GET",
                CURLOPT_HTTPHEADER   => array(
                        "cache-control:no-cache",
                        "content-type:application/json",
                        "Authorization:Bearer ". @$this->access_info() //access token from access info method
                    )
            ]
        );

        $response = curl_exec($curl);
        $zones         = json_decode($response);
        if(@$zones->data->data == null):
            return [];
        else:
            return @$zones->data->data;
        endif;
    }


    //get areas
    public function getAreas($zone_id){
        $curl = curl_init();
        curl_setopt_array($curl,
            [
                CURLOPT_URL=> "https://api-hermes.pathao.com/aladdin/api/v1/zones/".$zone_id."/area-list",
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_ENCODING=>"",
                CURLOPT_MAXREDIRS=>10,
                CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST=>"GET",
                CURLOPT_HTTPHEADER   => array(
                        "cache-control:no-cache",
                        "content-type:application/json",
                        "Authorization:Bearer ". $this->access_info() //access token from access info method
                    )
            ]
        );

        $response      = curl_exec($curl);
        $areas         = json_decode($response);
        return $areas->data->data;
    }

    //create order
    public function createOrder($request,$order){
        
        $settings = Settings::first();

      try {

          $post_data = [
                  "store_id"           => (int) $settings->pathao_store_id,
                  "merchant_order_id"  => $order->id,
                  "sender_name"        => $settings->insta_link,
                  "sender_phone"       => '01712345678',
                  "recipient_name"     => $order->name,
                  "recipient_phone"    => $order->phone,
                  "recipient_address"  => $order->address,
                  "recipient_city"     => (int) $order->city,
                  "recipient_zone"     => (int) $order->zone,
                //   "recipient_area"     => (int) $order->area,
                  "delivery_type"      => 48,
                  "item_type"          => 2,
                  "special_instruction"=> "",
                  "item_quantity"      => 1,
                  "item_weight"        => (float) $order->weight,
                  "amount_to_collect"  => (int) $order->total,
                  "item_description"   => ""
              ];
          $curl = curl_init();
          curl_setopt_array($curl,
              [
                  CURLOPT_URL=> "https://api-hermes.pathao.com/aladdin/api/v1/orders",
                  CURLOPT_RETURNTRANSFER=>true,
                  CURLOPT_ENCODING=>"",
                  CURLOPT_MAXREDIRS=>10,
                  CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST=>"POST",
                  CURLOPT_POSTFIELDS   => json_encode($post_data),
                  CURLOPT_HTTPHEADER   => array(
                          "cache-control:no-cache",
                          "content-type:application/json",
                          "Authorization:Bearer ". $this->access_info() //access token from access info method
                      )
              ]
          );
          $response       = curl_exec($curl);
          $result         = json_decode($response);
          return $result;
      } catch (\Throwable $th) {
        return false;
      }

    }

    public function statusUpdate($request){
        
        
        try {

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
   

            if($request->order_status_slug == 'Assigned_for_Pickup'):      //assgin for delivery man
                $order->courier_status   = 'Assigned_for_Pickup';
                
            elseif($request->order_status_slug == 'Picked'):     // pickup 
               $order->status   = 7; //on delivery
               $order->courier_status   = 'Picked';
               
            elseif($request->order_status_slug == 'Pickup_Cancelled'):// partial delivered
                $order->status   = 4;
                $order->courier_status   = 'Pickup_Cancelled';
                
            elseif($request->order_status_slug == 'In_Transit')://in transit
                $order->courier_status   = 'In_Transit';
            
            elseif($request->order_status_slug == 'Assigned_for_Delivery'):      //assgin for delivery man
                $order->courier_status   = 'Assigned_for_Delivery';
                
            elseif($request->order_status_slug == 'Received_at_Last_Mile_HUB')://receive  last mile hub
                $order->courier_status   = 'Received_at_Last_Mile_HUB';

            elseif($request->order_status_slug == 'Delivered'):     // delivered
                $order->status   = 5; //delivered
                $order->courier_status   = 'Delivered';
                
            elseif($request->order_status_slug == 'On_Hold'):     //  hold
                $order->status   = 11; //courier hold
                $order->courier_status   = 'On_Hold';
                
            elseif($request->order_status_slug == 'Return'):    // return
                $order->status   = 12; //return
                $order->courier_status   = 'Return';
            
            

            endif;
            
            

            if(in_array($request->order_status_slug,$status)):
                if($request->payment_status):
                    $order->payment_status   = $request->payment_status;
                else:
                    $order->courier_status   = $request->order_status;
                endif;
            endif;

            $order->save();
            return true;
        } catch (\Throwable $th) {
            
            return false;
        }
       
    }
}

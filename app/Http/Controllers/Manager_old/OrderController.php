<?php

namespace App\Http\Controllers\Manager;

use App\Exports\PaperflyExport;
use App\Exports\PathaoExport;
use App\Exports\RedxExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Courier;
use App\Models\Product;
use App\Models\City;
use App\Models\Zone;
use App\Models\Settings;
use App\Models\Shipping;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\CustomersExport;
use App\Exports\OrdersExport;
use App\Repositories\PathaoApi\PathaoApiInterface;
use App\Repositories\RedXApi\RedXApiInterface;
use App\Repositories\SteadFastApi\SteadFastApiInterface;
use DB;
use Excel;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {

 $settings = Settings::first();
        $orders = Order::orderBy('id', 'desc')->where('status', 1)->paginate(10);
        $total_orders = Order::all();
        $last = Order::orderBy('id', 'desc')->where('status', 1)->first();
         $status = 1;
        return view('manager.pages.orders.management', compact('orders', 'settings', 'last', 'total_orders','status'));
    }
    public function get_city(Request $request){
        $data['city'] = City::where('courier_id',$request->courier_id)->get();
        return response()->json($data);

    }
    public function get_zone(Request $request){
        $data['zone'] = Zone::where('city',$request->city)->get();
        return response()->json($data);

    }

    public function management($status)
    {
        $st=1;
        if($status == 'processing'){
           $st=1; }
        else if($status == 'pending'){
           $st=2; }
        else if($status == 'hold'){
           $st=3; }
        else if($status == 'cancel'){
           $st=4; }
        else if($status == 'completed'){
           $st=5; }
        else if($status == 'pending_p'){
           $st=6; }
        else if($status == 'ondelivery'){
           $st=7; }
        else if($status == 'noresponse1'){
           $st=8; }
        else if($status == 'noresponse2'){
           $st=9; }
        else if($status == 'noresponse3'){
           $st=10; }
        else if($status == 'courier_hold'){
           $st=11; }
        else if($status == 'return'){
           $st=12; }
      
        
        $settings = Settings::first();
        $orders = Order::orderBy('id', 'desc')->where('status', $st)->paginate(10);
        $total_orders = Order::all();
        $last = Order::orderBy('id', 'desc')->where('status', $st)->first();
         $status = $st;
        return view('manager.pages.orders.management', compact('orders', 'settings', 'last', 'total_orders','status'));
    }
    
     public function statusChange($status,$id)
    {
       
           
        $order = Order::find($id);
        $order->status = $status;
        $order->save();
        $notification = array(
            'message'    => 'status Changed!',
            'alert-type' => 'info'
        );
        return redirect()->back()->with('notification');
    }


    
    

    public function search_order_input(Request $request)
    {

        $settings = Settings::first();
            $orders = Order::orderBy('id', 'desc')
                ->where('id', 'LIKE', '%' . $request->search_input . '%')
                ->orWhere('name', 'LIKE', '%' . $request->search_input . '%')
                ->orWhere('phone', 'LIKE', '%' . $request->search_input . '%')
                ->get();
        
        $total_orders = Order::all();
        $last = Order::orderBy('id', 'desc')->where('status', 1)->first();
        return view('manager.pages.orders.searchInput', compact('orders', 'settings', 'total_orders','last'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shippings =Shipping::where('status',1)->get();
        $carts = Cart::where('ip_address', request()->ip())->where('order_id',NULL)->get();
         $setting = Settings::first();
        return view('manager.pages.orders.create', compact('shippings','carts','setting'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
    {

        $validator = [
            'name'     => ['required'],
            'phone'    => ['required'],
            'address'  => ['required'],
        ];

        if($request->courier == 3)://3 = pathao
            //  $validator['pathao_store_id']  = ['required'];
             $validator['pathao_city_id']   = ['required'];
             $validator['pathao_zone_id']   = ['required'];
            //  $validator['pathao_area_id']   = ['required'];
            //  $validator['sender_name']      = ['required'];
            //  $validator['sender_phone']     = ['required'];
            //  $validator['weight']           = ['required'];
        elseif($request->courier == 1):
            $validator['gram_weight']  = ['required'];
        endif;

       Validator::make($request->all(),$validator,[],[
            // 'pathao_store_id' => 'pathao store',
            'pathao_city_id'  => 'city name',
            'pathao_zone_id'  => 'zone name',
            // 'pathao_area_id'  => 'area name',
            // 'gram_weight'     => 'weight'
         ])->validate();


            $current_time = Carbon::now()->format('H:i:s');
            $user = User::where('role', 3)->inRandomOrder()->first();


            $order               = new Order();
            $order->name         = $request->name;

            $order->order_assign    = $user->id;


            $order->address = $request->address;
            $order->sub_total = $request->sub_total;
            $order->pay = $request->pay;
            $order->phone   = $request->phone;
            $order->shipping_cost   = $request->shipping_cost;

            $shipping = Shipping::where('id', $request->shipping_method)->get();
            $order->total = ($request->sub_total + $request->shipping_cost) - ($request->discount + $request->pay);
            $order->shipping_method = $request->shipping_method;
            $order->discount   = $request->discount;
            $order->order_note  = $request->order_note;
            $order->courier  = $request->courier;

            if($request->courier == 3):// 3 = pathao
                $order->sender_name    = $request->sender_name;
                $order->sender_phone   = $request->sender_phone;
                $order->courier        = $request->courier;
                $order->store          = $request->pathao_store_id;
                $order->city           = $request->pathao_city_id;
                $order->zone           = $request->pathao_zone_id;
                $order->area           = $request->pathao_area_id;
                // $order->quantity        = $request->quantity;
                $order->weight          = $request->weight;
            elseif($request->courier == 1):
                $order->weight          = $request->gram_weight;
            endif;
            
            $order->status     = $request->status;
            $order->sub_total  = $request->sub_total;
            $order->ip_address = request()->ip();
            $order->save();

            if($order && $request->courier == 1 && $request->status == 2)://1 = redX
                $parcel = $this->redX->createOrder($request,$order);

                if(isset($parcel->tracking_id)):
                    $orderUpdate                 = Order::find($order->id);
                    if($orderUpdate):
                        $orderUpdate->consignment_id = $parcel->tracking_id;
                        $orderUpdate->save();
                    endif;
                elseif(!isset($parcel->status_code) && isset($parcel->validation_errors) && $parcel->validation_errors[0]):
                    return redirect()->back()->withErrors($parcel->validation_errors[0]);
                elseif($parcel->status_code == 401 ):
                    return redirect()->back()->withErrors($parcel);
                else:
                    return redirect()->back();
                endif;

            elseif($order && $request->courier == 3 && $request->status == 2):// 3 = pathao
                $parcel = $this->pathao->createOrder($request,$order);
                if($parcel->type == 'success' ):
                    $orderUpdate                 = Order::find($parcel->data->merchant_order_id);
                    if($orderUpdate):
                        $orderUpdate->consignment_id = $parcel->data->consignment_id;
                        $orderUpdate->save();
                    endif;
                elseif($parcel->type == 'error' ):
                    return redirect()->back()->withErrors($parcel->errors);
                endif;

            elseif($order && $request->courier == 4 && $request->status == 2)://4 = steadfast
                $parcel = $this->steadfast->createOrder($request,$order);
                if($parcel->status == 200 ):
                    $orderUpdate                 = Order::find($parcel->consignment->invoice);
                    if($orderUpdate):
                        $orderUpdate->consignment_id = $parcel->consignment->tracking_code;
                        $orderUpdate->save();
                    endif;
                elseif($parcel->status == 400 ):
                    return redirect()->back()->withErrors($parcel->errors);
                else:
                    return redirect()->back();
                endif;

            endif;

            foreach ($request->products as $product) {
                $cart = new Cart();
                $cart->product_id = $product['id'];
                $cart->order_id   = $order->id;
                $cart->quantity   = $product['quantity'];
                $cart->price      = $product['price'];


                if(isset($product['attribute']) && is_array($product['attribute'])):
                    $cart->attribute  =  $product['attribute'];
                endif;
                $cart->save();
            }

            return redirect()->route("manager.order.manage");
        // }


        return redirect()->back();
    }

     public function addProduct(Request $request){
        if(request()->ajax()):
            $product  = Product::select('id','atr','atr_item','name','slug','sku','regular_price','offer_price')->find($request->product_id);

            return response()->json([
                'product'  => $product,
                'price'    => $product->offer_price ?? $product->regular_price,
                'view'     => view('backend.pages.orders.product_row',compact('product'))->render(),
            ]);
        endif;
        return '';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shippings =Shipping::where('status',1)->get();
        $orderDetails = Order::find($id);
        if (!is_null($orderDetails)) {
            return view('manager.pages.orders.details', compact('orderDetails','shippings'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        $carts= Cart::where('order_id', $id)->get();
        $setting = Settings::first();
        $total_price =0;

        foreach ($carts as $cart) {
            $total_price += $cart->price * $cart->quantity;
        }
        
$net_price = $total_price - $order->discount - $order->pay + $order->shipping_cost; 

        if (!is_null($order)) {
            $shippings =Shipping::where('status',1)->get();
          $carts = Cart::where('order_id',$order->id)->get();
            return view('manager.pages.orders.update', compact('order','carts','net_price','total_price','setting'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function update(Request $request, $id)
    {

        $validator = [
            'name'     => ['required'],
            'phone'    => ['required'],
            'address'  => ['required'],
        ];

        if($request->courier == 3)://3 = pathao
            //  $validator['pathao_store_id']  = ['required'];
             $validator['pathao_city_id']   = ['required'];
             $validator['pathao_zone_id']   = ['required'];
            //  $validator['pathao_area_id']   = ['required'];
            //  $validator['sender_name']      = ['required'];
            //  $validator['sender_phone']     = ['required'];
            //  $validator['weight']           = ['required'];
        elseif($request->courier == 1):
            $validator['gram_weight']  = ['required'];
        endif;

       Validator::make($request->all(),$validator,[],[
            // 'pathao_store_id' => 'pathao store',
            'pathao_city_id'  => 'city name',
            'pathao_zone_id'  => 'zone name',
            // 'pathao_area_id'  => 'area name',
            // 'gram_weight'     => 'weight'
         ])->validate();

        $order = Order::find($id);
            $order->name           = $request->name;
            $order->address        = $request->address;
            $order->sub_total      = $request->sub_total;
            $order->phone          = $request->phone;
            $order->shipping_cost  = $request->shipping_cost;
            $order->total          = ($request->sub_total + $request->shipping_cost) - ($request->discount + $request->pay);
            $order->discount       = $request->discount;
            $order->order_note     = $request->order_note;
            $order->courier        = $request->courier;
            
            if($request->courier == 3):// 3 = pathao
                $order->sender_name    = $request->sender_name;
                $order->sender_phone   = $request->sender_phone;
                $order->courier        = $request->courier;
                $order->store          = $request->pathao_store_id;
                $order->city           = $request->pathao_city_id;
                $order->zone           = $request->pathao_zone_id;
                $order->area           = $request->pathao_area_id;
                // $order->quantity        = $request->quantity;
                $order->weight          = $request->weight;
            elseif($request->courier == 1):
                $order->weight          = $request->gram_weight;
            endif;

            $order->pay            = $request->pay;
            $order->status     = $request->status;
            $order->sub_total  = $request->sub_total;

            $order->save();

            if($order && $request->courier == 1 && $request->status == 2)://1 = redX
                $parcel = $this->redX->createOrder($request,$order);
                if(isset($parcel->tracking_id)):
                    $orderUpdate                 = Order::find($order->id);
                    if($orderUpdate):
                        $orderUpdate->consignment_id = $parcel->tracking_id;
                        $orderUpdate->save();
                    endif;
                elseif(!isset($parcel->status_code) && isset($parcel->validation_errors) && $parcel->validation_errors[0]):
                    return redirect()->back()->withErrors($parcel->validation_errors[0]);
                elseif($parcel->status_code == 401 ):
                    return redirect()->back()->withErrors($parcel);
                else:
                    return redirect()->back();
                endif;
            elseif($order && $request->courier == 3 && $request->status == 2):// 3 = pathao
                $parcel = $this->pathao->createOrder($request,$order);
                if($parcel->type == 'success' ):
                    $orderUpdate                 = Order::find($parcel->data->merchant_order_id);
                    if($orderUpdate):
                        $orderUpdate->consignment_id = $parcel->data->consignment_id;
                        $orderUpdate->save();
                    endif;
                elseif($parcel->type == 'error' ):
                    return redirect()->back()->withErrors($parcel->errors);
                endif;
            elseif($order && $request->courier == 4 && $request->status == 2)://4 = steadfast
                $parcel = $this->steadfast->createOrder($request,$order);
                if($parcel->status == 200 ):
                    $orderUpdate                 = Order::find($parcel->consignment->invoice);
                    if($orderUpdate):
                        $orderUpdate->consignment_id = $parcel->consignment->tracking_code;
                        $orderUpdate->save();
                    endif;
                elseif($parcel->status == 400 ):
                    return redirect()->back()->withErrors($parcel->errors);
                else:
                    return redirect()->back();
                endif;
            endif;


            Cart::where('order_id',$order->id)->delete();
            foreach ($request->products as $product) {
                $cart = new Cart();
                $cart->product_id = $product['id'];
                $cart->order_id   = $order->id;
                $cart->quantity   = $product['quantity'];
                $cart->price      = $product['price'];
                if(isset($product['attribute']) && is_array($product['attribute'])):
                    $cart->attribute  =  $product['attribute'];
                endif;
                $cart->save();
            }

            return redirect()->route("manager.order.manage");
        // }



        return redirect()->back();
    }
    
    
    public function update_s(Request $request, $id)
    {


        $order =Order::find($id);
        $order->status       = $request->status;

        $order->save();


        return redirect()->route('manager.order.manage');
    }

    public function update_auto(Request $request){
        dd($request->all());
          // do database operations required
          return 'success';

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order=Order::find($id);
        if (!is_null($order)) {
           $order->delete();
        }
        return redirect()->back();
    }
    
    public function deleteChecketorders(Request $request)
    {

         $ids = $request->all_id ;

        Order::whereIn('id',explode(",",$ids))->delete();
        $notification = array(
            'message'    => 'Order deleted!',
            'alert-type' => 'error'
        );
        return redirect()->route('manager.order.manage')->with($notification);
    }

     public function selected_status(Request $request)
    {

         $status= $request->status;
         $ids = $request->all_status;
         $orders =Order::whereIn('id',explode(",",$ids))->get();
         foreach($orders as $orders){
            $orders->status =$status;
            $orders->save();
         }

         return redirect()->back();


    }
    public function ajax_find_product($id){
        $product = Product::where('id',$id)->first();
        return response()->json($product);
    }
     public function ajax_find_courier($id){
        $courier = Courier::where('id',$id)->first();
        return response()->json($courier);
    }



     public function exportIntoExcel(){

        return Excel::download(new CustomersExport, 'customers_list.xlsx');
    }

     public function orderexport(){

        return Excel::download(new OrdersExport, 'order.xlsx');
    }




}

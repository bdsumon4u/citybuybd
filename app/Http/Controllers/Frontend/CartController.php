<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart as ShoppingCart;
use Auth;
use App\Models\ProductAttribute;
use App\Models\AtrItem;
use Illuminate\Support\Facades\Log;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $cartItems= Cart::orderBy('id','desc')->where('order_id', null)->get();
        return view('frontend.pages.cart',compact('cartItems'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $cart = Cart::where('ip_address', request()->ip())->where('product_id', $request->product_id)->where('order_id', NULL)->first();

       if (!is_null($cart)) {
           $cart->increment('quantity');

            $notification = array(
            'message'    => 'Another Quantity Added',
            'alert-type' => 'info'
        );
            return redirect()->back()->with($notification);

       }else{
        $cart= new Cart();

        $cart->attribute= json_encode($request->attribute);
        $cart->ip_address= $request->ip();
        $cart->product_id= $request->product_id;
        $cart->quantity= $request->quantity;
        $cart->price= $request->price;

        $cart->save();

        $notification = array(
            'message'    => 'Item Added succesfullfy',
            'alert-type' => 'success'
        );
            return redirect()->back()->with($notification);

       }

    }

    
//    public function o_store(Request $request)
//     {
//         $attributes = $request->attribute; // [atr_id => atr_item_id]

//         $color = null;
//         $size  = null;
//         $model = null;

//         if ($attributes) {
//             foreach ($attributes as $atrId => $itemId) {
//                 $attribute = ProductAttribute::find($atrId);
//                 $item      = AtrItem::find($itemId);

//                 if ($attribute && $item) {
//                     $attrName = strtolower($attribute->name);

//                     if ($attrName === 'color') {
//                         $color = $item->name;
//                     }

//                     if ($attrName === 'size') {
//                         $size = $item->name;
//                     }

//                     if ($attrName === 'model') { 
//                         $model = $item->name; 
//                     }
//                 }
//             }
//         }

//         ShoppingCart::add([
//             'id'      => $request->product_id,
//             'name'    => $request->product_name,
//             'qty'     => $request->quantity,
//             'price'   => $request->price,
//             'options' => [
//                 'attributes' => $attributes ?? [],
//                 'color'      => $color,
//                 'size'       => $size,
//                 'model'      => $model, 
//                 'image'      => $request->product_image,
//                 'slug'       => $request->slug,
//             ],
//             'taxRate' => 0,
//         ]);

//         return redirect()->route('checkout');
//     }


public function o_store(Request $request)
{ 

    $attributes = $request->attribute; // [atr_id => atr_item_id]

    $color = null;
    $size  = null;
    $model = null;

    if ($attributes) {

        foreach ($attributes as $atrId => $itemId) {
            $attribute = ProductAttribute::find($atrId);
            $item      = AtrItem::find($itemId);


            if ($attribute && $item) {
                $attrName = strtolower($attribute->name);

                if ($attrName === 'color') {
                    $color = $item->name;
                }

                if ($attrName === 'size') {
                    $size = $item->name;
                }

                if ($attrName === 'model') {
                    $model = $item->name; 
                }
            }
        }
    }

    ShoppingCart::add([
        'id'      => $request->product_id,
        'name'    => $request->product_name,
        'qty'     => $request->quantity,
        'price'   => $request->price,
        'options' => [
            'attributes' => $attributes ?? [],
            'color'      => $color,
            'size'       => $size,
            'model'      => $model,
            'image'      => $request->product_image,
            'slug'       => $request->slug,
        ],
        'taxRate' => 0,
    ]);

    return redirect()->route('checkout');
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $cart= Cart::find($id);
        if (!is_null($cart)) {
           $cart->quantity = $request->quantity;
           $cart->save();
           return redirect()->back();
        }else{
           return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         
        ShoppingCart::remove($id);
    //    $cart= Cart::find($id);

    //    if (!is_null($cart)) {
    //        $cart->delete();
            $notification = array(
            'message'    => 'Cart Deleted',
            'alert-type' => 'error'
        );
    //    }else{
    //     return redirect()->back()->with($notification);
    //    }
       return redirect()->back()->with($notification);
    }
    public function admin_cart_dlt($id,$order){
        Cart::find($id)->delete();
        $carts = Cart::where('order_id', $order)->get();

        $total_price =0;

        foreach($carts as $cart){

                 $total_price += $cart->price * $cart->quantity;




        }
        return response()->json($total_price);

    }


    public function cart_plus(Request $request)
    {  
        $qty =(int) $request->qty; 
        ShoppingCart::update($request->rowId,$qty);
        return response()->json(['success' => 'Success'],200);


    }



}

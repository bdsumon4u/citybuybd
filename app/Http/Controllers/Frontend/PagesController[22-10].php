<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Landing;
use App\Models\Order;
use App\Models\Product;
use App\Models\Settings;
use App\Models\Shipping;
use App\Models\Slider;
use App\Models\Subcategory;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart as ShoppingCart;
// use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Settings::take(1)->first();
        $sliders = Slider::where('status', 1)->get();
        $products = Product::where('status', 1)->paginate(18);

        $category_products = Category::with('products')->where('status', 1)->take(5)->get();

        //        $sales =Cart::groupBy('product_id')->get();
        //        $sales =Product::with('all_carts')->orderBy('product_id')->get();;

        $best_selling = DB::table('products')
            ->select([
                'products.id',
                DB::raw('SUM(carts.quantity) as total_sales'),
            ])
            ->join('carts', 'carts.product_id', '=', 'products.id')
            ->groupBy('products.id')
            ->orderByDesc('total_sales')
            ->get();

        $hots = Product::where('status', 1)->whereNotNull('offer_price')->take(12)->get();
        $categories = Category::orderBy('title', 'asc')->where('status', 1)->get();

        return view('frontend.pages.index', compact('categories', 'products', 'sliders', 'settings', 'hots', 'category_products', 'best_selling'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function details($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $relatedProducts = Product::where('category_id', $product->category_id)->Where('status', 1)->get()->take(18);
        $shipping_charge = Shipping::get();

        if (! is_null($product)) {
            $settings = Settings::first();
            $categories = Category::orderBy('title', 'asc')->where('status', 1)->get();

            return view('frontend.pages.details', compact('product', 'categories', 'settings', 'relatedProducts', 'shipping_charge'));
        } else {
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request)
    {

        // dd(ShoppingCart::content());
        /*     dd($request->all()); */

        $total = 0;
        $shipping = Shipping::where('id', $request->shipping_method)->get();
        foreach ($shipping as $shipping) {
            $total = $request->sub_total + $shipping->amount;
        }

        if (ShoppingCart::content()->count() < 1) {
            $notification = [
                'message' => 'No Product',
                'alert-type' => 'danger',
            ];

            return back()->with($notification);
        }

        $settings = Settings::first();
        $categories = DB::table('categories')->select('id', 'title')->where('status', 1)->get();

        $current_time = \Illuminate\Support\Facades\Date::now()->format('H:i:s');

        // foreach(Cart::totalCarts() as $cart ){
        //     $product = Product::find($cart->product_id);
        //     if ($product->assign){
        //         $temp_user =User::where('id',$product->assign)->first();
        //         if ($temp_user->status==1 && $temp_user->start_time < $current_time && $temp_user->end_time > $current_time){
        //             $user = $temp_user;
        //         }else{
        //             $user = null;
        //         }
        //     }
        // }

        $user = User::where('status', 1)->where('role', 3)->inRandomOrder()->first();

        $order = new Order;
        $order->name = $request->name;
        $order->address = $request->address;
        $order->order_assign = $user->id;
        $order->phone = $request->phone;
        // $shipping = Shipping::where('id',$request->shipping_method)->get();

        // $order->shipping_cost =$shipping->sum('amount');

        $order->total = ShoppingCart::total() + $request->shipping_method;

        // $order->shipping_method = $request->shipping_method;
        $order->shipping_cost = $request->shipping_method;

        $order->status = 1;
        $order->sub_total = ShoppingCart::total();
        $order->payment_method = 'cod';
        $order->ip_address = request()->ip();
        $order->save();

        foreach (ShoppingCart::content() as $cart) {
            $orderProducts = new Cart;
            $orderProducts->order_id = $order->id;
            $orderProducts->product_id = $cart->id;
            $orderProducts->quantity = $cart->qty;
            $orderProducts->price = $cart->price;
            $orderProducts->attribute = $cart->options['attribute'];
            $orderProducts->save();
        }

        ShoppingCart::destroy();

        return view('frontend.pages.c_order', compact('order', 'settings', 'categories'));

    }

    public function redirect_to_merchant($url)
    {

        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head><script type="text/javascript">
                function closethisasap() { document.forms["redirectpost"].submit(); }
            </script></head>
        <body onLoad="closethisasap();">

        <form name="redirectpost" method="post" action="<?php echo 'https://secure.aamarpay.com/'.$url; ?>"></form>
        <!-- for live url https://secure.aamarpay.com -->
        </body>
        </html>
        <?php
        exit;
    }

    public function success(Request $request)
    {
        //        'opt_a' => $request->sub_total,
        //        'opt_b' => request()->ip(),
        //        'opt_c' => $request->phone,
        $user = null;
        $current_time = \Illuminate\Support\Facades\Date::now()->format('H:i:s');

        if (is_null(($user))) {
            $user = User::where('status', 1)->where('end_time', '>', $current_time)->where('role', 3)->Where('start_time', '<', $current_time)->inRandomOrder()->first();
        }
        if (is_null(($user))) {

            $user = User::where('status', 1)->where('role', 3)->inRandomOrder()->first();
        }

        $settings = Settings::first();
        $categories = DB::table('categories')->select('id', 'title')->where('status', 1)->get();

        $order = new Order;
        $order->name = $request->cus_name;
        $order->address = $request->opt_c;
        $order->phone = $request->cus_phone;
        $shipping = Shipping::where('id', $request->opt_d)->get();
        foreach ($shipping as $shipping) {
            $order->shipping_cost = $shipping->amount;
            $order->total = $request->opt_a + $shipping->amount;
        }
        $order->shipping_method = $request->opt_d;
        $order->status = 1;
        $order->sub_total = $request->opt_a;
        $order->payment_method = 'aamarpay';
        $order->ip_address = $request->opt_b;
        $order->txn_id = $request->pg_txnid;
        //        $order->txn_idd = $request->epw_txnid;
        $order->save();
        foreach (Cart::totalCarts() as $cart) {
            $cart->order_id = $order->id;
            $cart->save();
        }

        return view('frontend.pages.c_order', compact('order', 'settings', 'categories'));

    }

    public function landingorder(Request $request)
    {
        // dd($request->all());
        $current_time = \Illuminate\Support\Facades\Date::now()->format('H:i:s');

        $settings = Settings::first();
        $categories = DB::table('categories')->select('id', 'title')->where('status', 1)->get();
        $user = User::where('status', 1)->where('role', 3)->inRandomOrder()->first();

        $order = new Order;
        $order->name = $request->name;
        $order->order_assign = $user->id;
        $order->address = $request->address;
        $order->phone = $request->phone;

        $shipping = Shipping::where('id', $request->shipping_method)->get();

        //  dd($request->shipping_method);

        foreach ($shipping as $shipping) {
            $order->shipping_cost = $shipping->amount;
            $order->total = ($request->sub_total * $request->quantity) + $shipping->amount;
        }

        $order->shipping_method = $request->shipping_method;

        $order->status = 1;
        $order->coming = 1;

        $order->sub_total = $request->sub_total * $request->quantity;
        $order->ip_address = request()->ip();

        $order->save();

        if ($order) {
            $cart = new Cart;
            $cart->product_id = $request->product_id;
            $cart->order_id = $order->id;
            $cart->quantity = $request->quantity;
            $cart->price = $request->price;
            $cart->ip_address = $order->id;
            $cart->save();
        }

        return view('frontend.pages.c_order', compact('order', 'settings', 'categories'));

    }

    public function fail(Request $request)
    {
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkout()
    {

        $settings = Settings::first();
        $shippings = Shipping::where('status', 1)->get();
        $carts = Cart::where('ip_address', request()->ip())->where('order_id', null)->get();
        $categories = DB::table('categories')->select('id', 'title')->where('status', 1)->get();

        return view('frontend.pages.checkout', compact('carts', 'shippings', 'settings', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // $category = $request->category;
        $search = $request->search;
        $settings = Settings::first();
        $categories = DB::table('categories')->select('id', 'title')->where('status', 1)->get();
        $products = Product::Where('name', 'like', '%'.$search.'%')->orderBy('id', 'desc')->where('status', 1)->paginate(18);

        return view('frontend.pages.search', compact('products', 'search', 'settings', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajax_find_shipping($id)
    {
        $shipping = Shipping::where('id', $id)->first();
        $totalPrice = Cart::totalPrice();

        return response()->json(['totalPrice' => $totalPrice, 'shipping' => $shipping]);
    }

    public function cart_plus_admin($id, $order)
    {
        $cart_plus = Cart::find($id);

        $cart_plus->quantity += 1;
        $cart_plus->save();
        $carts = Cart::where('order_id', $order)->get();

        $totalPrice = 0;

        foreach ($carts as $cart) {

            $totalPrice += $cart->price * $cart->quantity;

        }

        return response()->json(['totalPrice' => $totalPrice, 'cart' => $cart_plus]);

    }

    public function cart_minus_admin($id, $order)
    {
        $cart_plus = Cart::find($id);

        $cart_plus->quantity -= 1;
        $cart_plus->save();
        $carts = Cart::where('order_id', $order)->get();

        $totalPrice = 0;

        foreach ($carts as $cart) {

            $totalPrice += $cart->price * $cart->quantity;

        }

        return response()->json(['totalPrice' => $totalPrice, 'cart' => $cart_plus]);

    }

    public function qty_minus($id)
    {
        $cart_plus = Cart::find($id);
        if ($cart_plus->quantity >= 2) {
            $cart_plus->quantity -= 1;
        }
        $cart_plus->save();

        $totalPrice = Cart::totalPrice();

        return response()->json(['totalPrice' => $totalPrice, 'cart' => $cart_plus]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function category($id)
    {
        $settings = Settings::first();
        $category = Category::find($id);
        $products = Product::where('category_id', $category->id)->Where('status', 1)->paginate(30);
        $categories = DB::table('categories')->select('id', 'title')->where('status', 1)->get();

        return view('frontend.pages.category', compact('category', 'products', 'settings', 'categories'));
    }

    public function subcategory($id)
    {
        $settings = Settings::first();
        $categories = Subcategory::find($id);
        $products = Product::where('subcategory_id', $categories->id)->Where('status', 1)->paginate(100);

        return view('frontend.pages.subcategory', compact('categories', 'products', 'settings'));

    }

    public function childcategory($id)
    {
        $settings = Settings::first();
        $categories = Childcategory::find($id);
        $products = Product::where('childcategory_id', $categories->id)->Where('status', 1)->paginate(100);

        return view('frontend.pages.childcategory', compact('categories', 'products', 'settings'));

    }

    public function contact()
    {
        $settings = Settings::first();

        return view('frontend.pages.contact', compact('settings'));
    }

    public function about()
    {
        $settings = Settings::first();

        return view('frontend.pages.about', compact('settings'));
    }

    public function termCondition()
    {
        $settings = Settings::first();

        return view('frontend.pages.term', compact('settings'));
    }

    public function return_policy()
    {
        $settings = Settings::first();

        return view('frontend.pages.return', compact('settings'));
    }

    public function privacy_policy()
    {
        $settings = Settings::first();

        return view('frontend.pages.privacy', compact('settings'));
    }

    public function cancel_policy()
    {
        $settings = Settings::first();

        return view('frontend.pages.cancel', compact('settings'));
    }

    public function landing($id)
    {
        $shippings = Shipping::where('status', 1)->get();

        $settings = Settings::first();
        $landing = Landing::with('product')->find($id);

        return view('frontend.pages.landing2', compact('settings', 'landing', 'shippings'));
    }
}

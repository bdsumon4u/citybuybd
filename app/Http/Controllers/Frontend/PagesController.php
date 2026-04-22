<?php

namespace App\Http\Controllers\Frontend;

// use Cart;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\IncompleteOrder;
use App\Models\Landing;
use App\Models\Order;
use App\Models\Product;
use App\Models\Settings;
use App\Models\Shipping;
use App\Models\Slider;
use App\Models\Subcategory;
// use DateTime;
use App\Models\User;
use App\Services\FacebookConversionsApiService;
use App\Services\OrderAssigneeService;
use App\Services\OrderDefenderService;
use App\Services\OrderForwardingService;
use App\Services\QuantityMonitorService;
use App\Services\WhatsAppService;
use App\Support\UtmAttribution;
use Gloudemans\Shoppingcart\Facades\Cart as ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = optimize('settings_first', fn () => Settings::take(1)->first(), 86400, ['settings']);

        $sliders = optimize('sliders_active', fn () => Slider::where('status', 1)->get(), 86400, ['sliders']);

        $page = request('page', 1);
        $products = optimize('products_index_page_'.$page, fn () => Product::where('status', 1)->latest()->paginate(18), 60, ['products']);

        $category_products = optimize('category_products_home', fn () => Category::with(['products' => function ($query): void {
            $query->latest();
        }])->where('status', 1)->take(5)->get(), 86400, ['categories', 'products']);

        $best_selling = optimize('best_selling_products', function () {
            // 1. Get IDs of best selling products (efficient aggregation)
            $bestSellingIds = DB::table('carts')
                ->select('product_id', DB::raw('SUM(quantity) as total_sales'))
                ->groupBy('product_id')
                ->orderByDesc('total_sales')
                ->limit(20)
                ->pluck('product_id')
                ->toArray();

            if (empty($bestSellingIds)) {
                return collect();
            }

            // 2. Fetch full product models using whereIn (avoids N+1 and GROUP BY issues)
            $products = Product::whereIn('id', $bestSellingIds)->get();

            // 3. Sort back to match sales order (since whereIn doesn't guarantee order)
            return $products->sortBy(fn ($model) => array_search($model->id, $bestSellingIds))->values(); // values() resets keys
        }, 86400, ['products', 'orders', 'carts']);

        $hots = optimize('hot_products', fn () => Product::where('status', 1)->whereNotNull('offer_price')->latest()->take(12)->get(), 86400, ['products']);

        $categories = optimize('categories_list_asc', fn () => Category::orderBy('title', 'asc')->where('status', 1)->get(), 86400, ['categories']);

        return view('frontend.pages.index', compact('categories', 'products', 'sliders', 'settings', 'hots', 'category_products', 'best_selling'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function details($slug)
    {
        $product = optimize('product_details_'.$slug, fn () => Product::where('slug', $slug)->first(), 86400, ['products']);

        $shipping_charge = optimize('shipping_charge_all', fn () => Shipping::get(), 86400, ['shippings']);

        if (! is_null($product)) {
            $relatedProducts = optimize('related_products_cat_'.$product->category_id, fn () => Product::where('category_id', $product->category_id)->Where('status', 1)->take(18)->get(), 86400, ['products']);

            $settings = optimize('settings_first', fn () => Settings::first(), 86400, ['settings']);

            $categories = optimize('categories_list_asc', fn () => Category::orderBy('title', 'asc')->where('status', 1)->get(), 86400, ['categories']);

            app(FacebookConversionsApiService::class)->trackViewContent($product, request());

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
    public function order(Request $request, WhatsAppService $whatsAppService)
    {

        // dd(ShoppingCart::content());
        /*     dd($request->all()); */

        $settings = Settings::first();

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
        $notification = app(OrderDefenderService::class)->defend($request, $settings);
        if ($notification !== null) {
            return back()->with($notification);
        }

        $categories = DB::table('categories')->select('id', 'title')->where('status', 1)->get();

        $order = DB::transaction(function () use ($request) {
            $user = $this->getAssignedUser();
            $utmAttribution = UtmAttribution::fromRequest($request);
            $order = new Order;
            $order->name = $request->name;
            $order->address = $request->address;
            $order->order_assign = $user->id;
            $order->phone = $request->phone;
            $order->total = ShoppingCart::total() + $request->shipping_method;
            $order->shipping_cost = $request->shipping_method;
            $order->status = 1;
            $order->sub_total = ShoppingCart::total();
            $order->payment_method = 'cod';
            $order->order_type = Order::TYPE_ONLINE;
            $order->ip_address = request()->ip();
            $this->applyUtmAttribution($order, $utmAttribution);
            $order->save();

            foreach (ShoppingCart::content() as $cart) {
                $orderProducts = new Cart;
                $orderProducts->order_id = $order->id;
                $orderProducts->product_id = $cart->id;
                $orderProducts->quantity = $cart->qty;
                $orderProducts->price = $cart->price;
                $orderProducts->color = $cart->options['color'] ?? null;
                $orderProducts->size = $cart->options['size'] ?? null;
                $orderProducts->model = $cart->options['model'] ?? null;
                $orderProducts->save();
            }

            // Update ordered_quantity from cart items
            app(QuantityMonitorService::class)->updateOrderedQuantity($order);

            return $order;
        });

        ShoppingCart::destroy();

        app(FacebookConversionsApiService::class)->trackPurchase($order, $request);

        // Send WhatsApp notification after products are attached
        $whatsAppService->sendOrderNotification($order);

        // Forward to master immediately if configured (slave mode only)
        app(OrderForwardingService::class)->forwardOrder($order);

        IncompleteOrder::where('phone', $request->phone)->delete();

        return view('frontend.pages.c_order', compact('order', 'settings', 'categories'));

    }

    public function redirect_to_merchant($url): never
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

    public function success(Request $request, WhatsAppService $whatsAppService)
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
        $utmAttribution = UtmAttribution::fromRequest($request);

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
        $order->order_type = Order::TYPE_ONLINE;
        $order->ip_address = $request->opt_b;
        $this->applyUtmAttribution($order, $utmAttribution);
        $order->txn_id = $request->pg_txnid;
        //        $order->txn_idd = $request->epw_txnid;
        $order->save();
        foreach (Cart::totalCarts() as $cart) {
            $cart->order_id = $order->id;
            $cart->save();
        }

        // Update ordered_quantity from cart items
        app(QuantityMonitorService::class)->updateOrderedQuantity($order);

        app(FacebookConversionsApiService::class)->trackPurchase($order, $request);

        // Send WhatsApp notification after products are attached
        $whatsAppService->sendOrderNotification($order);

        // Forward to master immediately if configured (slave mode only)
        app(OrderForwardingService::class)->forwardOrder($order);

        return view('frontend.pages.c_order', compact('order', 'settings', 'categories'));

    }

    public function landingorder(Request $request, WhatsAppService $whatsAppService)
    {
        $current_time = \Illuminate\Support\Facades\Date::now()->format('H:i:s');

        $settings = Settings::first();
        $notification = app(OrderDefenderService::class)->defend($request, $settings);
        if ($notification !== null) {
            return back()->with($notification);
        }

        $categories = DB::table('categories')->select('id', 'title')->where('status', 1)->get();

        $order = DB::transaction(function () use ($request) {
            $user = $this->getAssignedUser($request->filled('product_id') ? (int) $request->product_id : null);
            $utmAttribution = UtmAttribution::fromRequest($request);
            $order = new Order;
            $order->name = $request->name;
            $order->order_assign = $user->id;
            $order->address = $request->address;
            $order->phone = $request->phone;

            $shipping = Shipping::where('id', $request->shipping_method)->get();
            foreach ($shipping as $shipping) {
                $freeshipcheck = DB::table('products')->where('id', $request->product_id)->where('shipping', 1)->first();
                $order->shipping_cost = $freeshipcheck ? 0 : $shipping->amount;
                $order->total = ($request->integer('sub_total') * $request->integer('quantity')) + ($freeshipcheck ? 0 : $shipping->amount);
            }

            $order->shipping_method = $request->shipping_method;
            $order->status = 1;
            $order->coming = 1;
            $order->sub_total = $request->integer('sub_total') * $request->integer('quantity');
            $order->order_type = 'Landing';
            $order->ip_address = request()->ip();
            $this->applyUtmAttribution($order, $utmAttribution);
            $order->save();

            $cart = new Cart;
            $cart->product_id = $request->product_id;
            $cart->order_id = $order->id;
            $cart->quantity = $request->integer('quantity', 1);
            $cart->price = $request->input('price', 0);
            $cart->ip_address = request()->ip();
            $cart->attribute = $request->attribute;
            $cart->save();

            // Update ordered_quantity from cart items
            app(QuantityMonitorService::class)->updateOrderedQuantity($order);

            return $order;
        });

        app(FacebookConversionsApiService::class)->trackPurchase($order, $request);

        // Send WhatsApp notification after products are attached
        $whatsAppService->sendOrderNotification($order);

        // Forward to master immediately if configured (slave mode only)
        app(OrderForwardingService::class)->forwardOrder($order);

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
    // public function checkout()
    // {

    //     $settings = Settings::first();
    //     $shippings =Shipping::where('status',1)->get();
    //     $carts = Cart::where('ip_address', request()->ip())->where('order_id',NULL)->get();
    //     $categories = DB::table('categories')->select('id','title')->where('status',1)->get();

    //     return view('frontend.pages.checkout',compact('carts','shippings','settings','categories'));
    // }

    public function checkout()
    {
        $this->ensureDeviceIdCookie();

        $settings = Settings::first();
        $shippings = Shipping::where('status', 1)->get();
        $carts = \App\Models\Cart::where('ip_address', request()->ip())
            ->whereNull('order_id')
            ->get();
        $categories = DB::table('categories')
            ->select('id', 'title')
            ->where('status', 1)
            ->get();

        // Session-scoped token: keep token for later auto-save
        $incompleteToken = session('incomplete_token');
        if (! $incompleteToken) {
            $incompleteToken = (string) Str::uuid();
            session(['incomplete_token' => $incompleteToken]);
        }

        // Pre-fetch products for cart items to avoid N+1 in view
        $cartContent = ShoppingCart::content();
        $productIds = $cartContent->pluck('id')->all();
        $cartProducts = Product::whereIn('id', $productIds)->get()->keyBy('id');

        app(FacebookConversionsApiService::class)->trackInitiateCheckout($cartContent, (float) ShoppingCart::total(), request());

        return view('frontend.pages.checkout', compact('settings', 'shippings', 'categories', 'carts', 'incompleteToken', 'cartProducts'));
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
        $settings = optimize('settings_first', fn () => Settings::first(), 86400, ['settings']);

        $category = optimize('category_find_'.$id, fn () => Category::find($id), 86400, ['categories']);

        $page = request('page', 1);
        $products = optimize('products_category_'.$id.'_page_'.$page, fn () => Product::when($category, function ($query) use ($category) {
            return $query->where('category_id', $category->id);
        })->Where('status', 1)->latest()->paginate(30), 60, ['products']);

        $categories = optimize('categories_select_list', fn () => DB::table('categories')->select('id', 'title')->where('status', 1)->get(), 86400, ['categories']);

        return view('frontend.pages.category', compact('category', 'products', 'settings', 'categories'));
    }

    public function subcategory($id)
    {
        $settings = optimize('settings_first', fn () => Settings::first(), 86400, ['settings']);

        $category = optimize('subcategory_find_'.$id, fn () => Subcategory::find($id), 86400, ['subcategory']);

        $page = request('page', 1);
        $products = optimize('products_subcategory_'.$id.'_page_'.$page, fn () => Product::when($category, function ($query) use ($category) {
            return $query->where('subcategory_id', $category->id);
        })->Where('status', 1)->paginate(100), 60, ['products']);

        return view('frontend.pages.subcategory', compact('category', 'products', 'settings'));
    }

    public function childcategory($id)
    {
        $settings = optimize('settings_first', fn () => Settings::first(), 86400, ['settings']);

        $category = optimize('childcategory_find_'.$id, fn () => Childcategory::find($id), 86400, ['childcategories']);

        $page = request('page', 1);
        $products = optimize('products_childcategory_'.$id.'_page_'.$page, fn () => Product::when($category, function ($query) use ($category) {
            return $query->where('childcategory_id', $category->id);
        })->Where('status', 1)->paginate(100), 60, ['products']);

        return view('frontend.pages.childcategory', compact('category', 'products', 'settings'));
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
        $this->ensureDeviceIdCookie();

        $shippings = Shipping::where('status', 1)->get();
        $settings = Settings::first();
        $landing = Landing::with('product')->find($id);

        if ($landing?->product) {
            app(FacebookConversionsApiService::class)->trackViewContent($landing->product, request());
        }

        return view('frontend.pages.landing2', compact('settings', 'landing', 'shippings'));
    }

    /**
     * Get assigned user from a specific product or from cart products.
     * Prefers employees available now (by order_start/order_end, falling back to start_time/end_time).
     * If no available employee found, assigns any active employee regardless of time.
     *
     * @return User|null
     */
    private function getAssignedUser(?int $productId = null)
    {
        $ids = $productId !== null
            ? [$productId]
            : ShoppingCart::content()->pluck('id')->toArray();
        $assigneeService = new OrderAssigneeService;
        $userId = $assigneeService->selectAssignee($ids);

        return $userId ? User::find($userId) : null;
    }

    private function ensureDeviceIdCookie(): string
    {
        $existingDeviceId = request()->cookie('device_id');
        if (is_string($existingDeviceId) && trim($existingDeviceId) !== '') {
            $deviceId = trim($existingDeviceId);

            Cookie::queue(Cookie::make(
                'device_id',
                $deviceId,
                60 * 24 * 365 * 5,
                '/',
                null,
                request()->isSecure(),
                false,
                false,
                'Lax',
            ));

            return $deviceId;
        }

        $deviceId = (string) Str::uuid();

        Cookie::queue(Cookie::make(
            'device_id',
            $deviceId,
            60 * 24 * 365 * 5,
            '/',
            null,
            request()->isSecure(),
            false,
            false,
            'Lax',
        ));

        return $deviceId;
    }

    private function applyUtmAttribution(Order $order, array $utmAttribution): void
    {
        foreach (UtmAttribution::KEYS as $key) {
            $order->{$key} = $utmAttribution[$key] ?? null;
        }
    }
}

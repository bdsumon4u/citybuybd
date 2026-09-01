<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employee;

use App\Exports\CustomersExport;
use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\AtrItem;
use App\Models\Cart;
use App\Models\City;
use App\Models\Courier;
use App\Models\ManualOrderType;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Settings;
use App\Models\Shipping;
use App\Models\User;
use App\Models\Zone;
use App\Repositories\PathaoApi\PathaoApiInterface;
use App\Repositories\RedXApi\RedXApiInterface;
use App\Repositories\SteadFastApi\SteadFastApiInterface;
use App\Services\CourierBookingService;
use App\Services\OrderChangeHistoryService;
use App\Services\OrderForwardingService;
use App\Services\OrderProtectionService;
use App\Services\QuantityMonitorService;
use App\Services\WhatsAppService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $pathao;

    protected $steadfast;

    protected $redX;

    protected $whatsAppService;

    protected $courierBookingService;

    public function __construct(
        PathaoApiInterface $pathao,
        SteadFastApiInterface $steadfast,
        RedXApiInterface $redX,
        WhatsAppService $whatsAppService,
        CourierBookingService $courierBookingService
    ) {
        $this->pathao = $pathao;
        $this->steadfast = $steadfast;
        $this->redX = $redX;
        $this->whatsAppService = $whatsAppService;
        $this->courierBookingService = $courierBookingService;
    }

    public function index()
    {
        $settings = Settings::getSettings();
        $orders = Order::where('order_assign', Auth::user()->id)->orderBy('id', 'desc')->where('status', 1)->paginate(10);
        $total_orders = Order::where('order_assign', Auth::user()->id)->orderBy('id', 'desc')->get();
        $last = Order::orderBy('id', 'desc')->where('status', 1)->first();
        $status = 1;

        return view('employee.pages.orders.management', compact('orders', 'settings', 'last', 'total_orders', 'status'));
    }

    public function management($status)
    {
        $st = 1;
        if ($status == 'processing') {
            $st = 1;
        } elseif ($status == 'pending') {
            $st = 2;
        } elseif ($status == 'hold') {
            $st = 3;
        } elseif ($status == 'cancel') {
            $st = 4;
        } elseif ($status == 'completed') {
            $st = 5;
        } elseif ($status == 'pending_p') {
            $st = 6;
        } elseif ($status == 'ondelivery') {
            $st = 7;
        } elseif ($status == 'noresponse1') {
            $st = 8;
        } elseif ($status == 'noresponse2') {
            $st = 9;
        } elseif ($status == 'noresponse3') {
            $st = 10;
        } elseif ($status == 'courier_hold') {
            $st = 11;
        } elseif ($status == 'return') {
            $st = 12;
        } elseif ($status == 'partial_delivery') {
            $st = 13;
        } elseif ($status == 'paid_return') {
            $st = 14;
        } elseif ($status == 'stock_out') {
            $st = 15;
        } elseif ($status == 'total_delivery') {
            $st = 16;
        } elseif ($status == 'printed_invoice') {
            $st = 17;
        } elseif ($status == 'pending_return') {
            $st = 18;
        }

        $settings = Settings::getSettings();
        $orders = Order::where('order_assign', Auth::user()->id)->orderBy('id', 'desc')->where('status', $st)->paginate(10);
        $total_orders = Order::where('order_assign', Auth::user()->id)->orderBy('id', 'desc')->get();
        $last = Order::orderBy('id', 'desc')->where('status', $st)->first();
        $status = $st;

        return view('employee.pages.orders.management', compact('orders', 'settings', 'last', 'total_orders', 'status'));
    }

    // new update start

    public function newIndex()
    {
        $settings = Settings::getSettings();
        // $orders = Order::with('many_cart')->orderBy('id', 'desc')->paginate(10);

        // $last = Order::where('order_assign',Auth::user()->id)->orderBy('id', 'desc')->where('status', 1)->first();
        $last = Order::where('order_assign', Auth::user()->id)->orderBy('id', 'desc')->where('status', 1)->first();
        $status = 1;
        $users = User::get();
        $products = Product::latest()->select('name', 'id')->get();

        return view('employee.pages.orders.new-management', compact('settings', 'products', 'last', 'status', 'users'));
    }

    public function newIndexAction(Request $request)
    {
        // dd($request->all());
        $users = User::get();
        $today = \Illuminate\Support\Facades\Date::today()->format('Y-m-d');
        $query = Order::with(['many_cart.product:id,name,slug', 'user', 'couriers'])->where('order_assign', Auth::user()->id)->orderby('id', 'DESC');

        // if ($request->search_input) {

        //     $query =  Order::with('many_cart')->whereRaw("(name like '%$request->search_input%' or id like '%$request->search_input%' or phone like '%$request->search_input%')");
        // }

        if ($request->search_input) {
            $term = $request->search_input;
            $searchQuery = Order::with(['many_cart.product:id,name,slug', 'user'])
                ->where(function ($builder) use ($term): void {
                    $builder->where('name', 'like', "%{$term}%")
                        ->orWhere('id', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%");
                })
                ->orderBy('id', 'DESC');

            $this->applyOrderTypeFilter($searchQuery, $request->order_type);

            $paginate = $request->paginate ?? 25;
            $orders = $searchQuery->paginate($paginate);

            return view('employee.pages.orders.management-ajax-view', compact('users', 'orders'));
        }

        if ($request->courier) {
            $query->where('courier', $request->courier);
        }

        if ($request->fromDate && $request->toDate) {
            $date_from = \Illuminate\Support\Facades\Date::parse($request->fromDate)->format('Y-m-d');
            $date_to = \Illuminate\Support\Facades\Date::parse($request->toDate)->format('Y-m-d');
            $query->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
        }

        if ($request->fixeddate) {
            if ($request->fixeddate == 1) {
                // dd("dasfads");
                $query->whereDate('created_at', \Illuminate\Support\Facades\Date::today());
            } elseif ($request->fixeddate == 2) {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(1)->format('Y-m-d');
                $query->whereDate('created_at', $date);
            } elseif ($request->fixeddate == 7) {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(7)->format('Y-m-d');
                $query->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
            } elseif ($request->fixeddate == 15) {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(15)->format('Y-m-d');
                $query->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
            } elseif ($request->fixeddate == 30) {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(30)->format('Y-m-d');
                $query->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
            }
        }

        if ($request->product_id) {
            $product_id = $request->product_id;
            $query->whereHas('many_cart', function ($q) use ($product_id): void {
                $q->where('product_id', $product_id);
            });
        }
        $this->applyOrderTypeFilter($query, $request->order_type);

        if ($request->special_filter) {
            // Handle special filter types
            if ($request->special_filter === 'delay') {
                $days = max(1, (int) $request->input('delay_days', 5));
                $threshold = now()->subDays($days);
                $courierStatuses = [
                    Order::STATUS_TOTAL_DELIVERY,
                    Order::STATUS_ON_DELIVERY,
                    Order::STATUS_COURIER_HOLD,
                ];
                $courierStatusValues = array_map('strval', $courierStatuses);

                $latestStatusChange = DB::table('order_change_histories')
                    ->selectRaw('order_id, MAX(changed_at) as courier_since')
                    ->where('field_name', 'status')
                    ->whereIn('new_value', $courierStatusValues)
                    ->groupBy('order_id');

                $query->leftJoinSub($latestStatusChange, 'status_history', function ($join): void {
                    $join->on('orders.id', '=', 'status_history.order_id');
                })
                    ->select('orders.*', DB::raw('COALESCE(status_history.courier_since, orders.created_at) as courier_since'))
                    ->whereIn('orders.status', $courierStatuses)
                    ->whereRaw('COALESCE(status_history.courier_since, orders.created_at) <= ?', [$threshold]);
            }
        } elseif ($request->status) {
            $query->where('status', $request->status);
        }

        $paginate = 25;

        if ($request->paginate) {
            $paginate = $request->paginate;
        }

        $orders = $query->latest()->paginate($paginate);

        // dd($orders);
        return view('employee.pages.orders.management-ajax-view', compact('users', 'orders'));
    }

    public function total_order_list(Request $request)
    {
        $today = \Illuminate\Support\Facades\Date::today()->format('Y-m-d');

        $query = Order::where('order_assign', Auth::user()->id);

        $this->applyOrderTypeFilter($query, $request->order_type);

        if ($request->fromDate && $request->toDate) {
            $date_from = \Illuminate\Support\Facades\Date::parse($request->fromDate)->format('Y-m-d');
            $date_to = \Illuminate\Support\Facades\Date::parse($request->toDate)->format('Y-m-d');
            $query->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
        }

        if ($request->fixeddate) {
            if ($request->fixeddate == 1) {
                // dd("dasfads");
                $query->whereDate('created_at', \Illuminate\Support\Facades\Date::today());
            } elseif ($request->fixeddate == 2) {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(1)->format('Y-m-d');
                $query->whereDate('created_at', $date);
            } elseif ($request->fixeddate == 7) {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(7)->format('Y-m-d');
                $query->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
            } elseif ($request->fixeddate == 15) {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(15)->format('Y-m-d');
                $query->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
            } elseif ($request->fixeddate == 30) {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(30)->format('Y-m-d');
                $query->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
            }
        }
        
        if ($request->courier) {
            $query->where('courier', $request->courier);
        }
        // Employee panel must always be scoped to the logged-in employee.

        if ($request->product_id) {
            $product_id = $request->product_id;
            $query->whereHas('many_cart', function ($q) use ($product_id): void {
                $q->where('product_id', $product_id);
            });
        }

        $statusCounts = (clone $query)->reorder()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $total = $statusCounts->sum();

        $processing = $statusCounts->get(1, 0);
        $pending_Delivery = $statusCounts->get(2, 0);
        $on_Hold = $statusCounts->get(3, 0);
        $cancel = $statusCounts->get(4, 0);
        $completed = $statusCounts->get(5, 0);
        $pending_Payment = $statusCounts->get(6, 0);
        $on_Delivery = $statusCounts->get(7, 0);
        $no_response1 = $statusCounts->get(8, 0);
        $no_response2 = $statusCounts->get(9, 0);
        $courier_hold = $statusCounts->get(11, 0);
        $return = $statusCounts->get(12, 0);
        $partial_delivery = $statusCounts->get(13, 0);
        $paid_return = $statusCounts->get(14, 0);
        $stock_out = $statusCounts->get(15, 0);
        $total_delivery = $statusCounts->get(16, 0);
        $printed_invoice = $statusCounts->get(Order::STATUS_PRINTED_INVOICE, 0);
        $pending_return = $statusCounts->get(Order::STATUS_PENDING_RETURN, 0);

        // Calculate delay orders (in courier status for specified days)
        $days = max(1, (int) $request->input('delay_days', 5));
        $threshold = now()->subDays($days);
        $courierStatuses = [
            Order::STATUS_TOTAL_DELIVERY,
            Order::STATUS_ON_DELIVERY,
            Order::STATUS_COURIER_HOLD,
        ];
        $courierStatusValues = array_map('strval', $courierStatuses);
        $latestStatusChange = DB::table('order_change_histories')
            ->selectRaw('order_id, MAX(changed_at) as courier_since')
            ->where('field_name', 'status')
            ->whereIn('new_value', $courierStatusValues)
            ->groupBy('order_id');
        $delayCount = (clone $query)->leftJoinSub($latestStatusChange, 'status_history', function ($join): void {
            $join->on('orders.id', '=', 'status_history.order_id');
        })
            ->whereIn('orders.status', $courierStatuses)
            ->whereRaw('COALESCE(status_history.courier_since, orders.created_at) <= ?', [$threshold])
            ->count();

        // Calculate double orders (status-based)
        $doubleCount = (clone $query)->where('status', Order::STATUS_DOUBLE)->count();

        // dd($pending_Payment);
        return response()->json(['total' => $total, 'processing' => $processing, 'pending_Delivery' => $pending_Delivery, 'printed_invoice' => $printed_invoice, 'total_delivery' => $total_delivery, 'on_Hold' => $on_Hold, 'hold' => $on_Hold, 'cancel' => $cancel, 'completed' => $completed, 'pending_Payment' => $pending_Payment, 'on_Delivery' => $on_Delivery, 'no_response1' => $no_response1, 'no_response2' => $no_response2, 'courier_hold' => $courier_hold, 'return' => $return, 'pending_return' => $pending_return, 'partial_delivery' => $partial_delivery, 'paid_return' => $paid_return, 'stock_out' => $stock_out, 'delay' => $delayCount, 'double' => $doubleCount]);
    }

    // new update end

    public function statusChange(Request $request, $status, $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = (int) $order->status;
        $newStatus = (int) $status;

        $message = app(OrderProtectionService::class)->validateMutation(
            Auth::user(),
            $order,
            $oldStatus !== $newStatus,
            false,
            $request->input('status_over_cod')
        );

        if ($message) {
            return $this->mutationDeniedResponse($request, $message);
        }

        $order->status = $newStatus;
        $order->save();

        app(OrderChangeHistoryService::class)->recordStatusChange(
            $order,
            Auth::user(),
            $oldStatus,
            $newStatus,
            'employee.statusChange'
        );

        // Book to courier when status is set to Courier Entry (2)
        if ($order->status == Order::STATUS_PENDING_DELIVERY && $order->courier && ! $order->consignment_id) {
            $this->courierBookingService->bookOrder($order, null);
        }

        $notification = [
            'message' => 'status Changed!',
            'alert-type' => 'info',
        ];

        return back()->with('notification');
    }

    public function create()
    {
        $shippings = Shipping::where('status', 1)->get();
        $carts = Cart::where('order_id', null)->get();
        $setting = Settings::getSettings();

        return view('employee.pages.orders.create', compact('shippings', 'carts', 'setting'));
    }

    public function store(Request $request)
    {
        $validator = [
            'name' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'products' => ['required', 'array'],
        ];

        if ($request->courier == 3) { // 3 = pathao
            //  $validator['pathao_store_id']  = ['required'];
            $validator['pathao_city_id'] = ['required'];
            $validator['pathao_zone_id'] = ['required'];
            //  $validator['pathao_area_id']   = ['required'];
            //  $validator['sender_name']      = ['required'];
            //  $validator['sender_phone']     = ['required'];
            //  $validator['weight']           = ['required'];
        } elseif ($request->courier == 1) {
            $validator['gram_weight'] = ['required'];
        }

        Validator::make($request->all(), $validator, [], [
            // 'pathao_store_id' => 'pathao store',
            'pathao_city_id' => 'city name',
            'pathao_zone_id' => 'zone name',
            // 'pathao_area_id'  => 'area name',
            // 'gram_weight'     => 'weight'
        ])->validate();

        $order = new Order;
        $order->name = $request->name;

        if (empty($request->order_assign)) {
            $order->order_assign = Auth::user()->id;
        } else {
            $order->order_assign = $request->order_assign;
        }

        $order->address = $request->address;
        $order->sub_total = $request->sub_total;
        $order->pay = $request->pay;
        $order->phone = $request->phone;
        $order->shipping_cost = $request->shipping_cost;

        $shipping = Shipping::where('id', $request->shipping_method)->get();
        $order->total = ($request->sub_total + $request->shipping_cost) - ($request->discount + $request->pay);
        $order->shipping_method = $request->shipping_method;
        $order->discount = $request->discount;
        $order->order_note = $request->order_note;
        $order->courier = $request->courier;

        if ($request->courier == 3) { // 3 = pathao
            $order->sender_name = $request->sender_name;
            $order->sender_phone = $request->sender_phone;
            $order->courier = $request->courier;
            $order->store = $request->pathao_store_id;
            $order->city = $request->pathao_city_id;
            $order->zone = $request->pathao_zone_id;
            $order->area = $request->pathao_area_id;
            // $order->quantity        = $request->quantity;
            $order->weight = $request->weight;
        } elseif ($request->courier == 1) {
            $order->weight = $request->gram_weight;
        }

        $order->status = $request->status;
        $order->sub_total = $request->sub_total;
        $order->ip_address = request()->ip();
        $order->created_by = Auth::id();
        $order->order_type = ! empty($request->manual_order_type) ? $request->manual_order_type : Order::TYPE_MANUAL;
        $order->save();

        // Book to courier when status is set to Courier Entry (2)
        if ($order->status == Order::STATUS_PENDING_DELIVERY && $order->courier && ! $order->consignment_id) {
            $this->courierBookingService->bookOrder($order, $request);
        }

        foreach ($request->products ?? [] as $product) {
            $cart = new Cart;
            $cart->product_id = $product['id'];
            $cart->order_id = $order->id;
            $cart->quantity = $product['quantity'];
            $cart->price = $product['price'];

            if (isset($product['attribute']) && is_array($product['attribute'])) {
                $cart->attribute = $product['attribute'];
            }
            $this->applySelectedAttributesToCart($cart, $product['attribute'] ?? []);
            $cart->save();
        }

        // Update ordered_quantity from cart items
        app(QuantityMonitorService::class)->updateOrderedQuantity($order);

        // Send WhatsApp notification after products are attached
        $this->whatsAppService->sendOrderNotification($order);

        // Forward to master immediately if configured (slave mode only)
        app(OrderForwardingService::class)->forwardOrder($order);

        // return redirect()->route("order.newmanage");
        // }

        return redirect('employee/order-management/manage');
    }

    public function show($id)
    {
        $shippings = Shipping::where('status', 1)->get();
        $orderDetails = Order::find($id);
        if (! is_null($orderDetails)) {
            return view('employee.pages.orders.details', compact('orderDetails', 'shippings'));
        }
    }

    public function noted_edit(Request $request, $id)
    {
        $order = Order::find($id);

        $order->order_note = $request->order_noted;
        $order->save();

        return 1;
        // $notification = array(
        //     'message'    => 'status Changed!',
        //     'alert-type' => 'info'
        // );
        // return redirect()->back()->with('notification');
    }

    //     public function edit($id)
    //     {
    //         $order = Order::find($id);
    // $setting = Settings::getSettings();

    //             $carts= Cart::where('order_id', $id)->get();

    //         $total_price =0;

    //         foreach ($carts as $cart) {

    //             $total_price += $cart->price * $cart->quantity;

    //         }

    //         $net_price = $total_price- $order->discount+ $order->shipping_cost;

    //         if (!is_null($order)) {
    //             $shippings =Shipping::where('status',1)->get();
    //           $carts = Cart::where('order_id',$order->id)->get();
    //             return view('employee.pages.orders.update', compact('order','carts','total_price','net_price','setting'));
    //         }
    //     }
    public function edit($id)
    {
        $order = Order::with(['changeHistories.changer'])->find($id);
        $setting = Settings::getSettings();
        $carts = Cart::where('order_id', $id)->get();

        $total_price = 0;
        foreach ($carts as $cart) {
            $total_price += $cart->price * $cart->quantity;
        }

        $net_price = $total_price - $order->discount + $order->shipping_cost;

        // 🔹 Fallback product name
        $fallbackProductName = Product::find($order->product_id)?->name;

        if (! is_null($order)) {
            $shippings = Shipping::where('status', 1)->get();
            $carts = Cart::where('order_id', $order->id)->get();

            return view('employee.pages.orders.update', compact(
                'order',
                'carts',
                'total_price',
                'net_price',
                'setting',
                'fallbackProductName'
            ));
        }
    }

    public function update(Request $request, $id)
    {
        $validator = [
            'name' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'products' => ['required', 'array'],
        ];

        if ($request->courier == 3) { // 3 = pathao
            //  $validator['pathao_store_id']  = ['required'];
            $validator['pathao_city_id'] = ['required'];
            $validator['pathao_zone_id'] = ['required'];
            //  $validator['pathao_area_id']   = ['required'];
            //  $validator['sender_name']      = ['required'];
            //  $validator['sender_phone']     = ['required'];
            //  $validator['weight']           = ['required'];
        } elseif ($request->courier == 1) {
            $validator['gram_weight'] = ['required'];
        }

        Validator::make($request->all(), $validator, [], [
            // 'pathao_store_id' => 'pathao store',
            'pathao_city_id' => 'city name',
            'pathao_zone_id' => 'zone name',
            // 'pathao_area_id'  => 'area name',
            // 'gram_weight'     => 'weight'
        ])->validate();

        $order = Order::findOrFail($id);
        $oldStatus = (int) $order->status;
        $oldAssigned = $order->order_assign ? (int) $order->order_assign : null;
        $newStatus = (int) ($request->status ?? $oldStatus);
        $newAssigned = $request->has('order_assign') ? (int) $request->order_assign : $oldAssigned;

        $message = app(OrderProtectionService::class)->validateMutation(
            Auth::user(),
            $order,
            $oldStatus !== $newStatus,
            $oldAssigned !== $newAssigned,
            $request->input('status_over_cod')
        );

        if ($message) {
            return back()->withInput()->withErrors(['status' => $message]);
        }

        $order->name = $request->name;
        $order->address = $request->address;
        $order->sub_total = $request->sub_total;
        $order->phone = $request->phone;
        $order->shipping_cost = $request->shipping_cost;
        $order->total = ($request->sub_total + $request->shipping_cost) - ($request->discount + $request->pay);
        $order->discount = $request->discount;
        $order->order_note = $request->order_note;
        $order->courier = $request->courier;

        if ($request->courier == 3) { // 3 = pathao
            $order->sender_name = $request->sender_name;
            $order->sender_phone = $request->sender_phone;
            $order->courier = $request->courier;
            $order->store = $request->pathao_store_id;
            $order->city = $request->pathao_city_id;
            $order->zone = $request->pathao_zone_id;
            $order->area = $request->pathao_area_id;
            // $order->quantity        = $request->quantity;
            $order->weight = $request->weight;
        } elseif ($request->courier == 1) {
            $order->weight = $request->gram_weight;
        }

        $order->pay = $request->pay;
        $order->status = $request->status;
        $order->sub_total = $request->sub_total;
        $order->order_assign = $request->order_assign;
        if ($request->manual_order_type) {
            $order->order_type = $request->manual_order_type;
        }

        $order->save();

        $history = app(OrderChangeHistoryService::class);
        $history->recordStatusChange($order, Auth::user(), $oldStatus, (int) $order->status, 'employee.update');
        $history->recordAssignedUserChange($order, Auth::user(), $oldAssigned, $order->order_assign ? (int) $order->order_assign : null, 'employee.update');

        // Book to courier when status is set to Courier Entry (2)
        if ($order->status == Order::STATUS_PENDING_DELIVERY && $order->courier && ! $order->consignment_id) {
            $this->courierBookingService->bookOrder($order, $request);
        }

        Cart::where('order_id', $order->id)->delete();
        foreach ($request->products ?? [] as $product) {
            $cart = new Cart;
            $cart->product_id = $product['id'];
            $cart->order_id = $order->id;
            $cart->quantity = $product['quantity'];
            $cart->price = $product['price'];
            if (isset($product['attribute']) && is_array($product['attribute'])) {
                $cart->attribute = $product['attribute'];
            }
            $this->applySelectedAttributesToCart($cart, $product['attribute'] ?? []);
            $cart->save();
        }

        return to_route('employee.order.newmanage');
        // }

        return back();
    }

    public function update_s(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = (int) $order->status;
        $newStatus = (int) $request->status;

        $message = app(OrderProtectionService::class)->validateMutation(
            Auth::user(),
            $order,
            $oldStatus !== $newStatus,
            false,
            $request->input('status_over_cod')
        );

        if ($message) {
            return back()->withErrors(['status' => $message]);
        }

        $order->status = $newStatus;
        $order->save();

        app(OrderChangeHistoryService::class)->recordStatusChange(
            $order,
            Auth::user(),
            $oldStatus,
            $newStatus,
            'employee.update_s'
        );

        // Book to courier when status is set to Courier Entry (2)
        if ($order->status == Order::STATUS_PENDING_DELIVERY && $order->courier && ! $order->consignment_id) {
            $this->courierBookingService->bookOrder($order, $request);
        }

        return to_route('employee.order.manage');
    }

    private function mutationDeniedResponse(Request $request, string $message)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => $message], 403);
        }

        return back()->withErrors(['status' => $message]);
    }

    public function update_auto(Request $request)
    {
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
    public function ajax_find_product($id)
    {
        $product = Product::where('id', $id)->first();

        return response()->json($product);
    }

    public function ajax_find_courier($id)
    {
        $courier = Courier::where('id', $id)->first();

        return response()->json($courier);
    }

    public function exportIntoExcel()
    {
        return Excel::download(new CustomersExport, 'customers_list.xlsx');
    }

    public function orderexport(Request $request)
    {
        return Excel::download(new OrdersExport((string) $request->input('all_id_print', '')), 'order.xlsx');
    }

    public function search_order_input(Request $request)
    {
        $settings = Settings::getSettings();
        $orders = Order::orderBy('id', 'desc')
            ->where('id', 'LIKE', '%'.$request->search_input.'%')
            ->orWhere('name', 'LIKE', '%'.$request->search_input.'%')
            ->orWhere('phone', 'LIKE', '%'.$request->search_input.'%')
            ->get();

        $total_orders = Order::all();
        $last = Order::orderBy('id', 'desc')->where('status', 1)->first();

        return view('employee.pages.orders.searchInput', compact('orders', 'settings', 'total_orders', 'last'));
    }

    public function get_city(Request $request)
    {
        $data['city'] = City::where('courier_id', $request->courier_id)->get();

        return response()->json($data);
    }

    public function get_zone(Request $request)
    {
        $data['zone'] = Zone::where('city', $request->city)->get();

        return response()->json($data);
    }

    private function applySelectedAttributesToCart(Cart $cart, ?array $attributes): void
    {
        $cart->color = null;
        $cart->size = null;
        $cart->model = null;

        if (! is_array($attributes)) {
            return;
        }

        foreach ($attributes as $attributeId => $itemId) {
            if (! $attributeId || ! $itemId) {
                continue;
            }

            $attribute = ProductAttribute::find($attributeId);
            $item = AtrItem::find($itemId);

            if (! $attribute || ! $item) {
                continue;
            }

            $name = strtolower((string) $attribute->name);

            if ($name === 'color') {
                $cart->color = $item->name;
            } elseif ($name === 'size') {
                $cart->size = $item->name;
            } elseif ($name === 'model') {
                $cart->model = $item->name;
            }
        }
    }

    private function applyOrderTypeFilter(Builder $builder, ?string $orderType): void
    {
        if (! $orderType) {
            return;
        }

        // Check if it's a standard order type (online, manual, converted)
        if (in_array($orderType, Order::TYPES, true)) {
            $builder->where('order_type', $orderType);

            return;
        }

        // Check if it's a valid manual order type from database
        $isValidManualType = ManualOrderType::where('name', $orderType)
            ->where('status', true)
            ->exists();

        if ($isValidManualType) {
            $builder->where('order_type', $orderType);
        }
    }

    public function parcelHandover()
    {
        $settings = Settings::getSettings();

        return view('employee.pages.orders.parcel-handover', compact('settings'));
    }

    public function scanParcelHandover(Request $request)
    {
        $orderId = $request->input('order_id');

        if (! $orderId) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID is required.',
            ], 400);
        }

        $order = Order::find($orderId);

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if ($order->status !== Order::STATUS_PRINTED_INVOICE) {
            return response()->json([
                'success' => false,
                'message' => 'Order status must be Printed Invoice to scan for handover.',
            ], 400);
        }

        if (! $order->courier) {
            return response()->json([
                'success' => false,
                'message' => 'No courier selected for this order.',
            ], 400);
        }

        $order->status = Order::STATUS_TOTAL_DELIVERY;
        $order->save();

        // Log the scan
        DB::table('order_scan_logs')->insert([
            'order_id' => $order->id,
            'scan_type' => 'handover',
            'scanned_by' => auth()->id(),
            'scanned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated to Total Courier.',
            'order' => $order->fresh(),
            'consignment_id' => $order->consignment_id,
        ]);
    }

    public function returnReceived()
    {
        $settings = Settings::getSettings();

        return view('employee.pages.orders.return-received', compact('settings'));
    }

    public function scanReturnReceived(Request $request)
    {
        $orderId = $request->input('order_id');

        if (! $orderId) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID is required.',
            ], 400);
        }

        $order = Order::find($orderId);

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if (! in_array($order->status, [Order::STATUS_PENDING_RETURN, Order::STATUS_PAID_RETURN], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Order status must be Pending Return or Paid Return to scan for return.',
            ], 400);
        }

        $order->status = Order::STATUS_ORDER_RETURN;
        $order->save();

        // Log the scan
        DB::table('order_scan_logs')->insert([
            'order_id' => $order->id,
            'scan_type' => 'return',
            'scanned_by' => auth()->id(),
            'scanned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated to Return.',
            'order' => $order->fresh(),
            'consignment_id' => $order->consignment_id,
        ]);
    }

    public function getScannedOrders(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $type = $request->input('type', 'handover'); // 'handover' or 'return'

        try {
            $logs = DB::table('order_scan_logs')
                ->whereDate('scanned_at', $date)
                ->where('scan_type', $type)
                ->latest('scanned_at')
                ->get();

            $orders = [];
            foreach ($logs as $log) {
                $order = Order::find($log->order_id);
                if ($order) {
                    $orders[] = [
                        'id' => $order->id,
                        'customer_name' => $order->name,
                        'customer_phone' => $order->phone,
                        'customer_address' => $order->address,
                        'cod' => $order->pay,
                        'scanned_at' => $log->scanned_at,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'orders' => $orders,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching scanned orders: '.$e->getMessage(),
            ], 500);
        }
    }

    public function printScannedOrders(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $type = $request->input('type', 'handover');

        $logs = DB::table('order_scan_logs')
            ->whereDate('scanned_at', $date)
            ->where('scan_type', $type)
            ->latest('scanned_at')
            ->get();

        $orders = [];
        $totalCod = 0;
        foreach ($logs as $log) {
            $order = Order::find($log->order_id);
            if ($order) {
                $orders[] = [
                    'id' => $order->id,
                    'customer_name' => $order->name,
                    'customer_phone' => $order->phone,
                    'customer_address' => $order->address,
                    'cod' => $order->pay,
                    'scanned_at' => $log->scanned_at,
                ];
                $totalCod += (float) ($order->pay ?? 0);
            }
        }

        $title = $type === 'handover' ? 'Parcel Handover' : 'Return Received';

        return view('print.scanned-orders', [
            'orders' => $orders,
            'date' => $date,
            'title' => $title,
            'totalCod' => $totalCod,
            'type' => $type,
        ]);
    }

    public function deleteScannedOrder(Request $request)
    {
        $orderId = $request->input('order_id');
        $type = $request->input('type', 'handover');

        if (! $orderId) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID is required.',
            ], 400);
        }

        try {
            DB::table('order_scan_logs')
                ->where('order_id', $orderId)
                ->where('scan_type', $type)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Scanned order deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting scanned order: '.$e->getMessage(),
            ], 500);
        }
    }
}

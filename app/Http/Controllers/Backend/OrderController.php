<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Exports\CustomersExport;
use App\Exports\OrdersExport;
use App\Exports\PaperflyExport;
use App\Exports\PathaoExport;
use App\Exports\RedxExport;
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
use App\Services\WhatsAppService;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
        $settings = Settings::first();
        $orders = Order::with('many_cart')->orderBy('id', 'desc')->paginate(10);

        $last = Order::orderBy('id', 'desc')->where('status', 1)->first();
        $status = 1;
        $users = User::get();

        return view('backend.pages.orders.management', compact('orders', 'settings', 'last', 'status', 'users'));
    }

    public function newIndex()
    {
        $settings = Settings::first();

        // Load last order with related cart items and product only
        $last = Order::with('items.product')
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->first();

        $status = 1;
        $users = User::all();
        $products = Product::latest()->select('name', 'id')->get();
        $withDeliveryCharge = (bool) Session::get('orders.with_delivery_charge', true);

        return view('backend.pages.orders.new-management', compact('settings', 'products', 'last', 'status', 'users', 'withDeliveryCharge'));
    }

    public function newIndexAction(Request $request)
    {
        $users = User::get();
        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $withDeliveryCharge = $request->boolean('with_delivery_charge', true);
        Session::put('orders.with_delivery_charge', $withDeliveryCharge);

        $query = Order::with(['many_cart' => function ($query) {
            $query->with(['product' => function ($productQuery) {
                $productQuery->select('id', 'name', 'slug');
            }]);
        }, 'user', 'couriers'])->orderBy('id', 'desc')
            ->addSelect([
                'order_check' => Order::from('orders as odr')
                    ->whereColumn('orders.phone', 'odr.phone')
                    ->selectRaw('COUNT(phone) as order_check')
                    ->groupBy('phone')
                    ->limit(1),
            ]);

        if ($request->search_input) {
            $query->whereRaw("(name like '%$request->search_input%' or id like '%$request->search_input%' or phone like '%$request->search_input%')");
            $paginate = 25;
            if ($request->paginate) {
                $paginate = $request->paginate;
            }
            $orders = $query->paginate($paginate);

            return view('backend.pages.orders.management-ajax-view', [
                'users' => $users,
                'orders' => $orders,
                'withDeliveryCharge' => $withDeliveryCharge,
            ]);
        }
        if ($request->courier) {
            $query->where('courier', $request->courier);
        }
        if ($request->order_assign) {
            $query->where('order_assign', $request->order_assign);
        }
        if ($request->product_id) {
            $product_id = $request->product_id;
            $query->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
        }
        if ($request->fromDate && $request->toDate) {
            $date_from = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
            $date_to = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
            $query->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
        }
        if ($request->fixeddate) {
            if ($request->fixeddate == 1) {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($request->fixeddate == 2) {
                $date = \Carbon\Carbon::today()->subDays(1)->format('Y-m-d');
                $query->whereDate('created_at', $date);
            } elseif ($request->fixeddate == 7) {
                $date = \Carbon\Carbon::today()->subDays(7)->format('Y-m-d');
                $query->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
            } elseif ($request->fixeddate == 15) {
                $date = \Carbon\Carbon::today()->subDays(15)->format('Y-m-d');
                $query->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
            } elseif ($request->fixeddate == 30) {
                $date = \Carbon\Carbon::today()->subDays(30)->format('Y-m-d');
                $query->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
            }
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $this->applyOrderTypeFilter($query, $request->order_type);

        $paginate = 25;

        if ($request->paginate) {
            $paginate = $request->paginate;
        }

        if ($request->order_assign) {
            $query->where('order_assign', $request->order_assign);
        }

        $orders = $query->paginate($paginate);

        return view('backend.pages.orders.management-ajax-view', [
            'users' => $users,
            'orders' => $orders,
            'withDeliveryCharge' => $withDeliveryCharge,
        ]);
    }

    public function total_order_list(Request $request)
    {
        $today = \Carbon\Carbon::today()->format('Y-m-d');

        $processing = Order::with('many_cart')->latest();
        $pending_Delivery = Order::with('many_cart')->latest();
        $on_Hold = Order::with('many_cart')->latest();
        $cancel = Order::with('many_cart')->latest();
        $completed = Order::with('many_cart')->latest();
        $pending_Payment = Order::with('many_cart')->latest();
        $on_Delivery = Order::with('many_cart')->latest();
        $no_response1 = Order::with('many_cart')->latest();
        $no_response2 = Order::with('many_cart')->latest();
        $courier_hold = Order::with('many_cart')->latest();
        $return = Order::with('many_cart')->latest();
        $partial_delivery = Order::with('many_cart')->latest();
        $paid_return = Order::with('many_cart')->latest();
        $stock_out = Order::with('many_cart')->latest();
        $total_delivery = Order::with('many_cart')->latest();
        $query = Order::with('many_cart')->latest();

        foreach (
            [
                $processing,
                $pending_Delivery,
                $on_Hold,
                $cancel,
                $completed,
                $pending_Payment,
                $on_Delivery,
                $no_response1,
                $no_response2,
                $courier_hold,
                $return,
                $partial_delivery,
                $paid_return,
                $stock_out,
                $total_delivery,
                $query,
            ] as $builder
        ) {
            $this->applyOrderTypeFilter($builder, $request->order_type);
        }

        if ($request->fromDate && $request->toDate) {
            $date_from = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
            $date_to = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
            $processing->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $pending_Delivery->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $on_Hold->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $cancel->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $completed->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $pending_Payment->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $on_Delivery->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $no_response1->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $no_response2->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $courier_hold->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $return->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $partial_delivery->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $paid_return->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $stock_out->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $total_delivery->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
            $query->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
        }

        if ($request->fixeddate) {
            if ($request->fixeddate == 1) {
                // dd("dasfads");
                $query->whereDate('created_at', Carbon::today());
                $processing->whereDate('created_at', Carbon::today());
                $pending_Delivery->whereDate('created_at', Carbon::today());
                $on_Hold->whereDate('created_at', Carbon::today());
                $cancel->whereDate('created_at', Carbon::today());
                $pending_Payment->whereDate('created_at', Carbon::today());
                $on_Delivery->whereDate('created_at', Carbon::today());
                $no_response1->whereDate('created_at', Carbon::today());
                $no_response2->whereDate('created_at', Carbon::today());
                $courier_hold->whereDate('created_at', Carbon::today());
                $return->whereDate('created_at', Carbon::today());
                $partial_delivery->whereDate('created_at', Carbon::today());
                $paid_return->whereDate('created_at', Carbon::today());
                $stock_out->whereDate('created_at', Carbon::today());
                $total_delivery->whereDate('created_at', Carbon::today());
                $completed->whereDate('created_at', Carbon::today());
            } elseif ($request->fixeddate == 2) {
                $date = \Carbon\Carbon::today()->subDays(1)->format('Y-m-d');
                $query->whereDate('created_at', $date);
                $processing->whereDate('created_at', $date);
                $pending_Delivery->whereDate('created_at', $date);
                $on_Hold->whereDate('created_at', $date);
                $cancel->whereDate('created_at', $date);
                $pending_Payment->whereDate('created_at', $date);
                $on_Delivery->whereDate('created_at', $date);
                $no_response1->whereDate('created_at', $date);
                $no_response2->whereDate('created_at', $date);
                $courier_hold->whereDate('created_at', $date);
                $return->whereDate('created_at', $date);
                $partial_delivery->whereDate('created_at', $date);
                $paid_return->whereDate('created_at', $date);
                $stock_out->whereDate('created_at', $date);
                $total_delivery->whereDate('created_at', $date);
                $completed->whereDate('created_at', $date);
            } elseif ($request->fixeddate == 7) {
                $date = \Carbon\Carbon::today()->subDays(7)->format('Y-m-d');
                $query->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);

                $processing->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $pending_Delivery->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $on_Hold->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $cancel->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $pending_Payment->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $on_Delivery->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $no_response1->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $no_response2->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $courier_hold->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $return->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $partial_delivery->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $paid_return->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $stock_out->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $total_delivery->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $completed->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
            } elseif ($request->fixeddate == 15) {
                $date = \Carbon\Carbon::today()->subDays(15)->format('Y-m-d');
                $query->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);

                $processing->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $pending_Delivery->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $on_Hold->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $cancel->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $pending_Payment->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $on_Delivery->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $no_response1->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $no_response2->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $courier_hold->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $return->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $partial_delivery->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $paid_return->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $stock_out->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $total_delivery->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $completed->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
            } elseif ($request->fixeddate == 30) {
                $date = \Carbon\Carbon::today()->subDays(30)->format('Y-m-d');
                $query->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);

                $processing->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $pending_Delivery->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $on_Hold->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $cancel->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $pending_Payment->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $on_Delivery->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $no_response1->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $no_response2->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $courier_hold->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $return->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $partial_delivery->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $paid_return->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $stock_out->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
                $completed->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59']);
            }
        }
        if ($request->courier) {
            $processing->where('courier', $request->courier);
            $pending_Delivery->where('courier', $request->courier);
            $on_Hold->where('courier', $request->courier);
            $cancel->where('courier', $request->courier);
            $pending_Payment->where('courier', $request->courier);
            $on_Delivery->where('courier', $request->courier);
            $no_response1->where('courier', $request->courier);
            $no_response2->where('courier', $request->courier);
            $courier_hold->where('courier', $request->courier);
            $return->where('courier', $request->courier);
            $partial_delivery->where('courier', $request->courier);
            $paid_return->where('courier', $request->courier);
            $stock_out->where('courier', $request->courier);
            $total_delivery->where('courier', $request->courier);
            $completed->where('courier', $request->courier);
            $query->where('courier', $request->courier);
        }

        if ($request->order_assign) {
            $processing->where('order_assign', $request->order_assign);
            $pending_Delivery->where('order_assign', $request->order_assign);
            $on_Hold->where('order_assign', $request->order_assign);
            $cancel->where('order_assign', $request->order_assign);
            $pending_Payment->where('order_assign', $request->order_assign);
            $on_Delivery->where('order_assign', $request->order_assign);
            $no_response1->where('order_assign', $request->order_assign);
            $no_response2->where('order_assign', $request->order_assign);
            $courier_hold->where('order_assign', $request->order_assign);
            $return->where('order_assign', $request->order_assign);
            $partial_delivery->where('order_assign', $request->order_assign);
            $paid_return->where('order_assign', $request->order_assign);
            $stock_out->where('order_assign', $request->order_assign);
            $total_delivery->where('order_assign', $request->order_assign);
            $completed->where('order_assign', $request->order_assign);
            $query->where('order_assign', $request->order_assign);
        }

        if ($request->product_id) {
            $product_id = $request->product_id;

            $processing->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $pending_Delivery->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $on_Hold->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $cancel->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $pending_Payment->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $on_Delivery->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $no_response1->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $no_response2->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $courier_hold->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $return->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $partial_delivery->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $paid_return->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $stock_out->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $total_delivery->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $completed->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
            $query->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
        }

        $total = $query->count();

        $processing = $processing->where('status', 1)->count();
        $pending_Delivery = $pending_Delivery->where('status', 2)->count();
        $on_Hold = $on_Hold->where('status', 3)->count();
        $cancel = $cancel->where('status', 4)->count();
        $completed = $completed->where('status', 5)->count();
        $pending_Payment = $pending_Payment->where('status', 6)->count();
        $on_Delivery = $on_Delivery->where('status', 7)->count();
        $no_response1 = $no_response1->where('status', 8)->count();
        $no_response2 = $no_response2->where('status', 9)->count();
        $courier_hold = $courier_hold->where('status', 11)->count();
        $return = $return->where('status', 12)->count();
        $partial_delivery = $partial_delivery->where('status', 13)->count();
        $paid_return = $paid_return->where('status', 14)->count();
        $stock_out = $stock_out->where('status', 15)->count();
        $total_delivery = $total_delivery->where('status', 16)->count();
        $printed_invoice = (clone $query)->where('status', Order::STATUS_PRINTED_INVOICE)->count();
        $pending_return = (clone $query)->where('status', Order::STATUS_PENDING_RETURN)->count();

        // dd($pending_Payment);
        return response()->json(['total' => $total, 'processing' => $processing, 'pending_Delivery' => $pending_Delivery, 'printed_invoice' => $printed_invoice, 'total_delivery' => $total_delivery, 'on_Hold' => $on_Hold, 'hold' => $on_Hold, 'cancel' => $cancel, 'completed' => $completed, 'pending_Payment' => $pending_Payment, 'on_Delivery' => $on_Delivery, 'no_response1' => $no_response1, 'no_response2' => $no_response2, 'courier_hold' => $courier_hold, 'return' => $return, 'pending_return' => $pending_return, 'partial_delivery' => $partial_delivery, 'paid_return' => $paid_return, 'stock_out' => $stock_out]);
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

        $settings = Settings::first();
        $orders = Order::with('many_cart')->orderBy('id', 'desc')->where('status', $st)->paginate(10);

        $last = Order::orderBy('id', 'desc')->where('status', $st)->first();
        $status = $st;
        $users = User::get();

        return view('backend.pages.orders.management', compact('orders', 'settings', 'last', 'status', 'users'));
    }

    public function statusChange($status, $id)
    {
        $order = Order::find($id);
        $order->status = $status;
        $order->save();

        // Book to courier when status is set to Courier Entry (2)
        if ($order->status == Order::STATUS_PENDING_DELIVERY && $order->courier && ! $order->consignment_id) {
            $this->courierBookingService->bookOrder($order, null);
        }

        $notification = [
            'message' => 'status Changed!',
            'alert-type' => 'info',
        ];

        return redirect()->back()->with('notification');
    }

    public function create()
    {
        $shippings = Shipping::where('status', 1)->get();
        $carts = Cart::where('order_id', null)->get();
        $setting = Settings::first();

        return view('backend.pages.orders.create', compact('shippings', 'carts', 'setting'));
    }

    public function store(Request $request)
    {
        $validator = [
            'name' => ['required'],
            'phone' => ['required', 'min:11', 'max:11'],
            'address' => ['required'],
        ];

        if ($request->courier == 3) { //3 = pathao
            $validator['pathao_city_id'] = ['required'];
            $validator['pathao_zone_id'] = ['required'];
        } elseif ($request->courier == 1) {
            $validator['gram_weight'] = ['required'];
        }

        Validator::make($request->all(), $validator, [], [

            'pathao_city_id' => 'city name',
            'pathao_zone_id' => 'zone name',

        ])->validate();

        $current_time = Carbon::now()->format('H:i:s');

        $order = new Order();
        $order->name = $request->name;

        if (empty($request->order_assign)) {
            $user = User::where('role', 3)->inRandomOrder()->first();
            $order->order_assign = $user->id ?? null;
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
        $order->order_type = ! empty($request->manual_order_type) ? $request->manual_order_type : Order::TYPE_MANUAL;
        $order->save();

        // Book to courier when status is set to Courier Entry (2)
        if ($order->status == Order::STATUS_PENDING_DELIVERY && $order->courier && ! $order->consignment_id) {
            $this->courierBookingService->bookOrder($order, $request);
        }

        foreach ($request->products as $product) {
            $cart = new Cart();
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

        // Send WhatsApp notification after products are attached
        $this->whatsAppService->sendOrderNotification($order);

        return redirect()->route('order.newmanage');
        // }

        return redirect()->back();
    }

    public function addProduct(Request $request)
    {
        if (! request()->ajax()) {
            return '';
        }

        $product = Product::select('id', 'atr', 'atr_item', 'name', 'slug', 'sku', 'regular_price', 'offer_price')
            ->find($request->product_id);

        if (! $product) {
            return response()->json([
                'error' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'product' => $product,
            'price' => $product->offer_price ?? $product->regular_price,
            'view' => view('backend.pages.orders.product_row', compact('product'))->render(),
        ]);
    }

    public function show($id)
    {
        $shippings = Shipping::where('status', 1)->get();
        $orderDetails = Order::find($id);
        if (! is_null($orderDetails)) {
            return view('backend.pages.orders.details', compact('orderDetails', 'shippings'));
        }
    }

    public function print($id)
    {
        $orders = Order::find($id);
        $carts = Cart::where('order_id', $id)->get();
        $settings = Settings::first();

        return view('backend.pages.orders.invoice', compact('orders', 'carts', 'settings'));
    }

    public function edit($id)
    {
        $order = Order::find($id);
        $carts = Cart::where('order_id', $id)->get();
        $setting = Settings::first();
        $total_price = 0;

        foreach ($carts as $cart) {
            $total_price += ($cart->price * $cart->quantity);
        }

        $net_price = $total_price - $order->discount - $order->pay + $order->shipping_cost;

        $fallbackProductName = optional(Product::find($order->product_id))->name;

        if (! is_null($order)) {
            $shippings = Shipping::where('status', 1)->get();
            $carts = Cart::where('order_id', $order->id)->get();

            return view('backend.pages.orders.update', compact('order', 'carts', 'net_price', 'total_price', 'setting', 'fallbackProductName'));
        }
    }

    public function update(Request $request, $id)
    {
        $validator = [
            'name' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
        ];

        if ($request->courier == 3) { //3 = pathao
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

        $order = Order::find($id);
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

        // Book to courier when status is set to Courier Entry (2)
        if ($order->status == Order::STATUS_PENDING_DELIVERY && $order->courier && ! $order->consignment_id) {
            $this->courierBookingService->bookOrder($order, $request);
        }

        Cart::where('order_id', $order->id)->delete();
        foreach ($request->products as $product) {
            $cart = new Cart();
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

        return redirect()->route('order.newmanage');
        // }

        return redirect()->back();
    }

    public function update_auto(Request $request)
    {
        // dd($request->all());
        // do database operations required
        return 'success';
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (! is_null($order)) {
            Cart::where('order_id', $id)->delete();
            $order->delete();
        }

        return redirect()->back();
    }

    public function deleteChecketorders(Request $request)
    {
        $ids = $request->all_id;
        Order::whereIn('id', explode(',', $ids))->delete();
        $notification = [
            'message' => 'Order deleted!',
            'alert-type' => 'error',
        ];

        return redirect()->route('order.newmanage')->with($notification);
    }

    public function printChecketorders(Request $request)
    {
        $ids = $request->all_id_print;
        $settings = Settings::first();
        $orders = Order::whereIn('id', explode(',', $ids))->get();

        return view('backend.pages.orders.bulk_invoice', compact('orders', 'settings'));
    }

    public function labelChecketorders(Request $request)
    {
        $ids = $request->all_id_label;
        $settings = Settings::first();
        $orders = Order::whereIn('id', explode(',', $ids))->get();

        return view('backend.pages.orders.bulk_label', compact('orders', 'settings'));
    }

    public function excelChecketorders(Request $request)
    {
        $ids = $request->all_id_excel;
        //        $carts= Cart::with('order','product')
        //        ->whereIn('order_id',explode(",",$ids))
        //        ->get();
        //        dd($carts);
        // return new OrdersExport($ids);

        $today = \Carbon\Carbon::today()->format('d-m-y').'.xlsx';

        if ($request->courier == 'redx') {
            return Excel::download(new RedxExport($ids), $today);
        } elseif ($request->courier == 'pathao') {
            return Excel::download(new PathaoExport($ids), $today);
        } elseif ($request->courier == 'paperfly') {
            return Excel::download(new PaperflyExport($ids), $today);
        } else {
            return Excel::download(new OrdersExport($ids), $today);
        }
    }

    public function selected_status(Request $request)
    {
        $status = $request->status;
        $ids = $request->all_status;
        $orders = Order::whereIn('id', explode(',', $ids))->get();
        $sss = [];
        foreach ($orders as $order) {
            $sss[] = $order->phone;
            $order->status = $status;
            $order->save();

            // Book to courier when status is set to Courier Entry (2)
            if ($order->status == Order::STATUS_PENDING_DELIVERY && $order->courier && ! $order->consignment_id) {
                $this->courierBookingService->bookOrder($order, null);
            }
        }

        return redirect()->back();
    }

    public function selected_e_assign(Request $request)
    {
        $status = $request->e_assign;
        $ids = $request->all_e_assign;
        $orders = Order::whereIn('id', explode(',', $ids))->get();
        foreach ($orders as $orders) {
            $orders->order_assign = $status;
            $orders->save();
        }

        return redirect()->back();
    }

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
        // $ids = $request->all_id_print ;
        // dd($ids);
        return Excel::download(new OrdersExport, 'order.xlsx');
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

    //Order Filter
    public function search_order(Request $request)
    {
        $status = $request->tostatus;
        $date_from = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
        $date_to = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
        $settings = Settings::first();
        $orders = Order::with('many_cart', 'many_cart.product', 'user')->orderBy('id', 'desc')->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->paginate(50);
        $users = User::where('role', 3)->get();

        return view('backend.pages.orders.search', compact('orders', 'settings', 'users', 'status', 'date_from', 'date_to'));
    }

    public function search_order_status($date_from, $date_to, $status)
    {
        $date_from = \Carbon\Carbon::parse($date_from)->format('Y-m-d');
        $date_to = \Carbon\Carbon::parse($date_to)->format('Y-m-d');
        $settings = Settings::first();
        $orders = Order::with('many_cart', 'many_cart.product', 'user')->orderBy('id', 'desc')->where('status', $status)->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->paginate(50);
        $users = User::where('role', 3)->get();

        return view('backend.pages.orders.search', compact('orders', 'settings', 'users', 'status', 'date_from', 'date_to'));
    }

    public function total_order_custom_date($date_from = null, $date_to = null)
    {
        $date_from = \Carbon\Carbon::parse($date_from)->format('Y-m-d');
        $date_to = \Carbon\Carbon::parse($date_to)->format('Y-m-d');
        $total = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->count();
        $processing = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 1)->count();
        $pending_Delivery = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 2)->count();
        $on_Hold = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 3)->count();
        $cancel = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 4)->count();
        $completed = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 5)->count();
        $pending_Payment = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 6)->count();
        $on_Delivery = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 7)->count();

        $no_response1 = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 8)->count();
        $no_response2 = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 9)->count();
        $courier_hold = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 11)->count();
        $return = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 12)->count();
        $partial_delivery = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 13)->count();
        $paid_return = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 14)->count();
        $stock_out = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 15)->count();
        $total_delivery = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('status', 16)->count();

        return response()->json(['total' => $total, 'processing' => $processing, 'pending_Delivery' => $pending_Delivery, 'on_Hold' => $on_Hold, 'cancel' => $cancel, 'completed' => $completed, 'pending_Payment' => $pending_Payment, 'on_Delivery' => $on_Delivery, 'no_response1' => $no_response1, 'no_response2' => $no_response2, 'courier_hold' => $courier_hold, 'return' => $return, 'partial_delivery' => $partial_delivery, 'paid_return' => $paid_return, 'stock_out' => $stock_out, 'total_delivery' => $total_delivery]);
    }

    public function search_order_input(Request $request)
    {
        $settings = Settings::first();
        $orders = Order::with('many_cart', 'many_cart.product', 'user')
            ->orderBy('id', 'desc')
            ->where('id', 'LIKE', '%'.$request->search_input.'%')
            ->orWhere('name', 'LIKE', '%'.$request->search_input.'%')
            ->orWhere('phone', 'LIKE', '%'.$request->search_input.'%')
            ->get();

        $last = Order::orderBy('id', 'desc')->where('status', 1)->first();

        return view('backend.pages.orders.searchInput', compact('orders', 'settings', 'last'));
    }

    public function FilterData(Request $request)
    {
        $query = Order::latest();
        $paginate = 50;
        $today = \Carbon\Carbon::today()->format('Y-m-d');
        if ($request->paginate) {
            $paginate = $request->paginate;
        }

        if ($request->courier) {
            $query->where('courier', $request->courier);
        }

        if ($request->fromDate && $request->toDate) {
            $date_from = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
            $date_to = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
            $query->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59']);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $this->applyOrderTypeFilter($query, $request->order_type);

        $orders = $query->paginate($paginate);

        $settings = Settings::first();
        $users = User::where('role', 3)->get();

        return view('backend.pages.orders.paginate', compact('orders', 'settings', 'users'));
    }

    public function paginate($count, $status)
    {
        // if ($status == 0) {
        $last = Order::orderBy('id', 'desc')->first();
        $orders = Order::with('many_cart', 'many_cart.product', 'user')->orderBy('id', 'desc')->paginate(10);
        // dd($orders);
        // } else {
        //     $last = Order::orderBy('id', 'desc')->where('status', $status)->first();
        //     $orders = Order::with('many_cart','many_cart.product','user')->orderBy('id', 'desc')->where('status', $status)->paginate($count);
        // }
        $settings = Settings::first();
        $users = User::where('role', 3)->get();

        return view('backend.pages.orders.paginate', compact('orders', 'settings', 'last', 'count', 'status', 'last', 'users'));
    }

    public function searchByPastDate($count)
    {
        $today = \Carbon\Carbon::today()->format('Y-m-d');
        if ($count == 0) {
            $orders = Order::with('many_cart', 'many_cart.product', 'user')->orderBy('id', 'desc')->whereDate('created_at', Carbon::today())->paginate(50);
        } elseif ($count == 1) {
            $date = \Carbon\Carbon::today()->subDays(1)->format('Y-m-d');
            $orders = Order::with('many_cart', 'many_cart.product', 'user')->orderBy('id', 'desc')->whereDate('created_at', $date)->paginate(50);
        } elseif ($count == 7) {
            $date = \Carbon\Carbon::today()->subDays(7)->format('Y-m-d');
            $orders = Order::with('many_cart', 'many_cart.product', 'user')->orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->paginate(50);
        } elseif ($count == 15) {
            $date = \Carbon\Carbon::today()->subDays(15)->format('Y-m-d');
            $orders = Order::with('many_cart', 'many_cart.product', 'user')->orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->paginate(50);
        } elseif ($count == 30) {
            $date = \Carbon\Carbon::today()->subDays(30)->format('Y-m-d');
            $orders = Order::with('many_cart', 'many_cart.product', 'user')->orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->paginate(50);
        } else {
            $orders = all();
            $total_orders = $orders;
        }

        $settings = Settings::first();
        $users = User::where('role', 3)->get();

        $last = Order::orderBy('id', 'desc')->where('status', 12)->first();
        $status = 1;

        return view('backend.pages.orders.searchByDate', compact('orders', 'settings', 'count', 'users', 'last', 'status'));
    }

    public function searchByPastDateStatus($count, $status)
    {
        $today = \Carbon\Carbon::today()->format('Y-m-d');
        if ($count == 0) {
            $orders = Order::orderBy('id', 'desc')->where('status', $status)->whereDate('created_at', Carbon::today())->paginate(15);
            $total_orders = Order::whereDate('created_at', Carbon::today())->get();
        } elseif ($count == 1) {
            $date = \Carbon\Carbon::today()->subDays(1)->format('Y-m-d');
            $orders = Order::orderBy('id', 'desc')->where('status', $status)->whereDate('created_at', $date)->paginate(15);
            $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->get();
        } elseif ($count == 7) {
            $date = \Carbon\Carbon::today()->subDays(7)->format('Y-m-d');
            $orders = Order::orderBy('id', 'desc')->where('status', $status)->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->paginate(15);
            $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->get();
        } elseif ($count == 15) {
            $date = \Carbon\Carbon::today()->subDays(15)->format('Y-m-d');
            $orders = Order::orderBy('id', 'desc')->where('status', $status)->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->paginate(15);
            $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->get();
        } elseif ($count == 30) {
            $date = \Carbon\Carbon::today()->subDays(30)->format('Y-m-d');
            $orders = Order::orderBy('id', 'desc')->where('status', $status)->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->paginate(15);
            $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->get();
        } else {
            $orders = all();
            $total_orders = $orders;
        }

        $settings = Settings::first();
        $users = User::where('role', 3)->get();
        $carts = Cart::get();
        $last = Order::orderBy('id', 'desc')->where('status', 12)->first();
        $status = 1;

        return view('backend.pages.orders.searchByDate', compact('orders', 'settings', 'count', 'total_orders', 'users', 'carts', 'last', 'status'));
    }

    public function total_order_fixed_date($count = null)
    {
        $today = \Carbon\Carbon::today()->format('Y-m-d');

        if ($count == 0) {
            $total = Order::whereDate('created_at', Carbon::today())->count();
            $processing = Order::whereDate('created_at', Carbon::today())->where('status', 1)->count();
            $pending_Delivery = Order::whereDate('created_at', Carbon::today())->where('status', 2)->count();
            $on_Hold = Order::whereDate('created_at', Carbon::today())->where('status', 3)->count();
            $cancel = Order::whereDate('created_at', Carbon::today())->where('status', 4)->count();
            $completed = Order::whereDate('created_at', Carbon::today())->where('status', 5)->count();
            $pending_Payment = Order::whereDate('created_at', Carbon::today())->where('status', 6)->count();
            $on_Delivery = Order::whereDate('created_at', Carbon::today())->where('status', 7)->count();

            $no_response1 = Order::whereDate('created_at', Carbon::today())->where('status', 8)->count();
            $no_response2 = Order::whereDate('created_at', Carbon::today())->where('status', 9)->count();
            $courier_hold = Order::whereDate('created_at', Carbon::today())->where('status', 11)->count();
            $return = Order::whereDate('created_at', Carbon::today())->where('status', 12)->count();
            $partial_delivery = Order::whereDate('created_at', Carbon::today())->where('status', 13)->count();
            $paid_return = Order::whereDate('created_at', Carbon::today())->where('status', 14)->count();
            $stock_out = Order::whereDate('created_at', Carbon::today())->where('status', 15)->count();
        } elseif ($count == 1) {
            $date = \Carbon\Carbon::today()->subDays(1)->format('Y-m-d');
            $total = Order::whereDate('created_at', $date)->count();
            $processing = Order::whereDate('created_at', $date)->where('status', 1)->count();
            $pending_Delivery = Order::whereDate('created_at', $date)->where('status', 2)->count();
            $on_Hold = Order::whereDate('created_at', $date)->where('status', 3)->count();
            $cancel = Order::whereDate('created_at', $date)->where('status', 4)->count();
            $completed = Order::whereDate('created_at', $date)->where('status', 5)->count();
            $pending_Payment = Order::whereDate('created_at', $date)->where('status', 6)->count();
            $on_Delivery = Order::whereDate('created_at', $date)->where('status', 7)->count();

            $no_response1 = Order::whereDate('created_at', $date)->where('status', 8)->count();
            $no_response2 = Order::whereDate('created_at', $date)->where('status', 9)->count();
            $courier_hold = Order::whereDate('created_at', $date)->where('status', 11)->count();
            $return = Order::whereDate('created_at', $date)->where('status', 12)->count();
            $partial_delivery = Order::whereDate('created_at', $date)->where('status', 13)->count();
            $paid_return = Order::whereDate('created_at', $date)->where('status', 14)->count();
            $stock_out = Order::whereDate('created_at', $date)->where('status', 15)->count();
        } elseif ($count == 7) {
            $date = \Carbon\Carbon::today()->subDays(7)->format('Y-m-d');
            $total = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->count();
            $processing = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 1)->count();
            $pending_Delivery = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 2)->count();
            $on_Hold = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 3)->count();
            $cancel = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 4)->count();
            $completed = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 5)->count();
            $pending_Payment = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 6)->count();
            $on_Delivery = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 7)->count();

            $no_response1 = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 8)->count();
            $no_response2 = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 9)->count();
            $courier_hold = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 11)->count();
            $return = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 12)->count();
        } elseif ($count == 15) {
            $date = \Carbon\Carbon::today()->subDays(15)->format('Y-m-d');
            $total = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->count();
            $processing = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 1)->count();
            $pending_Delivery = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 2)->count();
            $on_Hold = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 3)->count();
            $cancel = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 4)->count();
            $completed = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 5)->count();
            $pending_Payment = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 6)->count();
            $on_Delivery = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 7)->count();

            $no_response1 = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 8)->count();
            $no_response2 = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 9)->count();
            $courier_hold = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 11)->count();
            $return = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 12)->count();
        } else {
            $date = \Carbon\Carbon::today()->subDays(30)->format('Y-m-d');
            $total = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->count();
            $processing = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 1)->count();
            $pending_Delivery = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 2)->count();
            $on_Hold = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 3)->count();
            $cancel = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 4)->count();
            $completed = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 5)->count();
            $pending_Payment = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 6)->count();
            $on_Delivery = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 7)->count();

            $no_response1 = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 8)->count();
            $no_response2 = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 9)->count();
            $courier_hold = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 11)->count();
            $return = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 12)->count();
            $partial_delivery = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 13)->count();
            $paid_return = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 14)->count();
            $stock_out = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 15)->count();
            $total_delivery = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('status', 16)->count();
        }

        return response()->json(['total' => $total, 'processing' => $processing, 'pending_Delivery' => $pending_Delivery, 'on_Hold' => $on_Hold, 'cancel' => $cancel, 'completed' => $completed, 'pending_Payment' => $pending_Payment, 'on_Delivery' => $on_Delivery, 'no_response1' => $no_response1, 'no_response2' => $no_response2, 'courier_hold' => $courier_hold, 'return' => $return, 'partial_delivery' => $partial_delivery, 'paid_return' => $paid_return, 'stock_out' => $stock_out, 'total_delivery' => $total_delivery]);
    }

    public function noted_edit(Request $request, $id)
    {
        $order = Order::find($id);

        $order->order_note = $request->order_noted;
        $order->save();

        return redirect()->back();
        // $notification = array(
        //     'message'    => 'status Changed!',
        //     'alert-type' => 'info'
        // );
        // return redirect()->back()->with('notification');
    }

    public function assign_edit(Request $request, $id)
    {
        $order = Order::find($id);
        $order->order_assign = $request->order_assign;
        $order->save();

        return redirect()->back();
    }

    public function qc_report($id)
    {
        $settings = Settings::first();

        if ($settings['qc_token']) {
            $order = Order::find($id);

            $url = 'https://courierrank.com/api/dokanai/'.$order->phone;

            $response = Http::withHeaders([
                'API-ACCESS-TOKEN' => 'Bearer '.$settings['qc_token'],
                'Accept' => '*/*',
                'Content-Type' => 'application/json',
            ])->post($url);

            if ($response->successful()) {
                $result = $response->object();

                $order->delivered = $result->delivered;
                $order->returned = $result->returned;

                $order->save();

                return $response->object();
            }

            return $response->object();
        }
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

            $name = strtolower($attribute->name);

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
        $settings = Settings::first();

        return view('backend.pages.orders.parcel-handover', compact('settings'));
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
        $settings = Settings::first();

        return view('backend.pages.orders.return-received', compact('settings'));
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
                ->orderBy('scanned_at', 'desc')
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
                        'cod' => $order->total,
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
            ->orderBy('scanned_at', 'desc')
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
                    'cod' => $order->total,
                    'scanned_at' => $log->scanned_at,
                ];
                $totalCod += (float) ($order->total ?? 0);
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

        if (!$orderId) {
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
                'message' => 'Error deleting scanned order: ' . $e->getMessage(),
            ], 500);
        }
    }
}

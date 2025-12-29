<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\Courier;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\AtrItem;
use App\Models\City;
use App\Models\Zone;
use App\Models\Settings;
use App\Models\Shipping;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Models\ManualOrderType;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Services\WhatsAppService;
use App\Services\CourierBookingService;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $pathao, $steadfast, $redX, $whatsAppService, $courierBookingService;
    public function __construct(
        PathaoApiInterface $pathao,
        SteadFastApiInterface $steadfast,
        RedXApiInterface    $redX,
        WhatsAppService     $whatsAppService,
        CourierBookingService $courierBookingService
    ) {
        $this->pathao    = $pathao;
        $this->steadfast = $steadfast;
        $this->redX      = $redX;
        $this->whatsAppService = $whatsAppService;
        $this->courierBookingService = $courierBookingService;
    }




    public function index()
    {
        $settings = Settings::first();
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
        } else if ($status == 'pending') {
            $st = 2;
        } else if ($status == 'hold') {
            $st = 3;
        } else if ($status == 'cancel') {
            $st = 4;
        } else if ($status == 'completed') {
            $st = 5;
        } else if ($status == 'pending_p') {
            $st = 6;
        } else if ($status == 'ondelivery') {
            $st = 7;
        } else if ($status == 'noresponse1') {
            $st = 8;
        } else if ($status == 'noresponse2') {
            $st = 9;
        } else if ($status == 'noresponse3') {
            $st = 10;
        } else if ($status == 'courier_hold') {
            $st = 11;
        } else if ($status == 'return') {
            $st = 12;
        } else if ($status == 'partial_delivery') {
            $st = 13;
        } else if ($status == 'paid_return') {
            $st = 14;
        } else if ($status == 'stock_out') {
            $st = 15;
        } else if ($status == 'total_delivery') {
            $st = 16;
        } else if ($status == 'printed_invoice') {
            $st = 17;
        } else if ($status == 'pending_return') {
            $st = 18;
        }


        $settings = Settings::first();
        $orders = Order::where('order_assign', Auth::user()->id)->orderBy('id', 'desc')->where('status', $st)->paginate(10);
        $total_orders = Order::where('order_assign', Auth::user()->id)->orderBy('id', 'desc')->get();
        $last = Order::orderBy('id', 'desc')->where('status', $st)->first();
        $status = $st;
        return view('employee.pages.orders.management', compact('orders', 'settings', 'last', 'total_orders', 'status'));
    }


    //new update start

    public function newIndex()
    {

        $settings = Settings::first();
        // $orders = Order::with('many_cart')->orderBy('id', 'desc')->paginate(10);

        // $last = Order::where('order_assign',Auth::user()->id)->orderBy('id', 'desc')->where('status', 1)->first();
        $last = Order::orderBy('id', 'desc')->where('status', 1)->first();
        $status = 1;
        $users = User::get();
        $products = Product::latest()->select('name', 'id')->get();

        return view('employee.pages.orders.new-management', compact('settings', 'products', 'last', 'status', 'users'));
    }



    public function newIndexAction(Request $request)
    {
        // dd($request->all());
        $users = User::get();
        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $query =  Order::with('many_cart')->where('order_assign', Auth::user()->id)->orderby('id', 'DESC');


        // if ($request->search_input) {

        //     $query =  Order::with('many_cart')->whereRaw("(name like '%$request->search_input%' or id like '%$request->search_input%' or phone like '%$request->search_input%')");
        // }


        if ($request->search_input) {
            $term = $request->search_input;
            $searchQuery = Order::with('many_cart')
                // ->where('order_assign', Auth::user()->id)
                ->where(function ($builder) use ($term) {
                    $builder->where('name', 'like', "%{$term}%")
                        ->orWhere('id', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%");
                })
                ->orderBy('id', 'DESC');

            $this->applyOrderTypeFilter($searchQuery, $request->order_type);

            $paginate = $request->paginate ?? 25;
            $orders = $searchQuery->paginate($paginate);

            return view('employee.pages.orders.management-ajax-view', compact("users", 'orders'));
        }





        if ($request->courier) {
            $query->where('courier', $request->courier);
        }

        if ($request->fromDate && $request->toDate) {
            $date_from = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
            $date_to = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
            $query->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        }


        if ($request->fixeddate) {
            if ($request->fixeddate == 1) {
                // dd("dasfads");
                $query->whereDate('created_at', Carbon::today());
            } elseif ($request->fixeddate == 2) {
                $date = \Carbon\Carbon::today()->subDays(1)->format('Y-m-d');
                $query->whereDate('created_at', $date);
            } elseif ($request->fixeddate == 7) {
                $date = \Carbon\Carbon::today()->subDays(7)->format('Y-m-d');
                $query->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
            } elseif ($request->fixeddate == 15) {
                $date = \Carbon\Carbon::today()->subDays(15)->format('Y-m-d');
                $query->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
            } elseif ($request->fixeddate == 30) {
                $date = \Carbon\Carbon::today()->subDays(30)->format('Y-m-d');
                $query->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
            }
        }



        if ($request->product_id) {
            $product_id = $request->product_id;
            $query->whereHas('many_cart', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
        }
        $this->applyOrderTypeFilter($query, $request->order_type);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $paginate = 25;

        if ($request->paginate) {

            $paginate = $request->paginate;
        }

        $orders = $query->latest()->paginate($paginate);
        // dd($orders);
        return view('employee.pages.orders.management-ajax-view', compact("users", 'orders'));
    }

    public function total_order_list(Request $request)
    {

        $today = \Carbon\Carbon::today()->format('Y-m-d');

        $processing = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $pending_Delivery = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $on_Hold = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $cancel = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $completed = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $pending_Payment = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $on_Delivery = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $no_response1 = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $no_response2 = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $courier_hold = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $return = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $partial_delivery = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $paid_return = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $stock_out = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $total_delivery = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();
        $query = Order::with('many_cart')->where('order_assign', Auth::user()->id)->latest();

        if ($request->fromDate && $request->toDate) {
            $date_from = \Carbon\Carbon::parse($request->fromDate)->format('Y-m-d');
            $date_to = \Carbon\Carbon::parse($request->toDate)->format('Y-m-d');
            $processing->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $pending_Delivery->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $on_Hold->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $cancel->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $completed->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $pending_Payment->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $on_Delivery->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $no_response1->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $no_response2->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $courier_hold->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $return->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $partial_delivery->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $paid_return->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $stock_out->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $total_delivery->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
            $query->whereBetween('created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
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
                $query->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);

                $processing->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $pending_Delivery->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $on_Hold->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $cancel->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $pending_Payment->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $on_Delivery->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $no_response1->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $no_response2->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $courier_hold->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $return->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $partial_delivery->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $paid_return->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $stock_out->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $total_delivery->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $completed->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
            } elseif ($request->fixeddate == 15) {
                $date = \Carbon\Carbon::today()->subDays(15)->format('Y-m-d');
                $query->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);

                $processing->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $pending_Delivery->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $on_Hold->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $cancel->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $pending_Payment->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $on_Delivery->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $no_response1->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $no_response2->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $courier_hold->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $return->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $partial_delivery->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $paid_return->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $stock_out->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $total_delivery->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $completed->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
            } elseif ($request->fixeddate == 30) {
                $date = \Carbon\Carbon::today()->subDays(30)->format('Y-m-d');
                $query->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);


                $processing->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $pending_Delivery->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $on_Hold->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $cancel->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $pending_Payment->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $on_Delivery->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $no_response1->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $no_response2->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $courier_hold->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $return->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $partial_delivery->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $paid_return->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $stock_out->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
                $completed->whereBetween('created_at', [$date . " 00:00:00", $today . " 23:59:59"]);
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


        $total        = $query->count();


        $processing        = $processing->where('status', 1)->count();
        $pending_Delivery  = $pending_Delivery->where('status', 2)->count();
        $on_Hold           = $on_Hold->where('status', 3)->count();
        $cancel            = $cancel->where('status', 4)->count();
        $completed         = $completed->where('status', 5)->count();
        $pending_Payment   = $pending_Payment->where('status', 6)->count();
        $on_Delivery       = $on_Delivery->where('status', 7)->count();
        $no_response1      = $no_response1->where('status', 8)->count();
        $no_response2      = $no_response2->where('status', 9)->count();
        $courier_hold      = $courier_hold->where('status', 11)->count();
        $return            = $return->where('status', 12)->count();
        $partial_delivery  = $partial_delivery->where('status', 13)->count();
        $paid_return       = $paid_return->where('status', 14)->count();
        $stock_out         = $stock_out->where('status', 15)->count();
        $total_delivery    = $total_delivery->where('status', 16)->count();
        $printed_invoice   = (clone $query)->where('status', Order::STATUS_PRINTED_INVOICE)->count();
        $pending_return    = (clone $query)->where('status', Order::STATUS_PENDING_RETURN)->count();


        // dd($pending_Payment);
        return response()->json(['total' => $total, 'processing' => $processing, 'pending_Delivery' => $pending_Delivery, 'printed_invoice' => $printed_invoice, 'total_delivery' => $total_delivery, 'on_Hold' => $on_Hold, 'hold' => $on_Hold, 'cancel' => $cancel, 'completed' => $completed, 'pending_Payment' => $pending_Payment, 'on_Delivery' => $on_Delivery, 'no_response1' => $no_response1, 'no_response2' => $no_response2, 'courier_hold' => $courier_hold, 'return' => $return, 'pending_return' => $pending_return, 'partial_delivery' => $partial_delivery, 'paid_return' => $paid_return, 'stock_out' => $stock_out]);
    }



    //new update end


    public function statusChange($status, $id)
    {
        $order = Order::find($id);
        $order->status = $status;
        $order->save();

        // Book to courier when status is set to Courier Entry (2)
        if ($order->status == Order::STATUS_PENDING_DELIVERY && $order->courier && !$order->consignment_id) {
            $this->courierBookingService->bookOrder($order, null);
        }

        $notification = array(
            'message'    => 'status Changed!',
            'alert-type' => 'info'
        );
        return redirect()->back()->with('notification');
    }

    public function create()
    {
        $shippings = Shipping::where('status', 1)->get();
        $carts = Cart::where('order_id', NULL)->get();
        $setting = Settings::first();
        return view('employee.pages.orders.create', compact('shippings', 'carts', 'setting'));
    }


    public function store(Request $request)
    {

        $validator = [
            'name'     => ['required'],
            'phone'    => ['required'],
            'address'  => ['required'],
        ];

        if ($request->courier == 3): //3 = pathao
            //  $validator['pathao_store_id']  = ['required'];
            $validator['pathao_city_id']   = ['required'];
            $validator['pathao_zone_id']   = ['required'];
        //  $validator['pathao_area_id']   = ['required'];
        //  $validator['sender_name']      = ['required'];
        //  $validator['sender_phone']     = ['required'];
        //  $validator['weight']           = ['required'];
        elseif ($request->courier == 1):
            $validator['gram_weight']  = ['required'];
        endif;

        Validator::make($request->all(), $validator, [], [
            // 'pathao_store_id' => 'pathao store',
            'pathao_city_id'  => 'city name',
            'pathao_zone_id'  => 'zone name',
            // 'pathao_area_id'  => 'area name',
            // 'gram_weight'     => 'weight'
        ])->validate();




        $order               = new Order();
        $order->name         = $request->name;

        if (empty($request->order_assign)) {
            $order->order_assign = Auth::user()->id;
        } else {
            $order->order_assign = $request->order_assign;
        }

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

        if ($request->courier == 3): // 3 = pathao
            $order->sender_name    = $request->sender_name;
            $order->sender_phone   = $request->sender_phone;
            $order->courier        = $request->courier;
            $order->store          = $request->pathao_store_id;
            $order->city           = $request->pathao_city_id;
            $order->zone           = $request->pathao_zone_id;
            $order->area           = $request->pathao_area_id;
            // $order->quantity        = $request->quantity;
            $order->weight          = $request->weight;
        elseif ($request->courier == 1):
            $order->weight          = $request->gram_weight;
        endif;

        $order->status     = $request->status;
        $order->sub_total  = $request->sub_total;
        $order->ip_address = request()->ip();
        $order->order_type = !empty($request->manual_order_type) ? $request->manual_order_type : Order::TYPE_MANUAL;
        $order->save();

        // Book to courier when status is set to Courier Entry (2)
        if ($order->status == Order::STATUS_PENDING_DELIVERY && $order->courier && !$order->consignment_id) {
            $this->courierBookingService->bookOrder($order, $request);
        }

        foreach ($request->products as $product) {
            $cart = new Cart();
            $cart->product_id = $product['id'];
            $cart->order_id   = $order->id;
            $cart->quantity   = $product['quantity'];
            $cart->price      = $product['price'];


            if (isset($product['attribute']) && is_array($product['attribute'])):
                $cart->attribute  =  $product['attribute'];
            endif;
            $this->applySelectedAttributesToCart($cart, $product['attribute'] ?? []);
            $cart->save();
        }

        // Send WhatsApp notification after products are attached
        $this->whatsAppService->sendOrderNotification($order);

        // return redirect()->route("order.newmanage");
        // }


        return redirect('employee/order-management/manage');
    }


    public function show($id)
    {
        $shippings = Shipping::where('status', 1)->get();
        $orderDetails = Order::find($id);
        if (!is_null($orderDetails)) {
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
    // $setting = Settings::first();

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
        $order = Order::find($id);
        $setting = Settings::first();
        $carts = Cart::where('order_id', $id)->get();

        $total_price = 0;
        foreach ($carts as $cart) {
            $total_price += $cart->price * $cart->quantity;
        }

        $net_price = $total_price - $order->discount + $order->shipping_cost;

        // 🔹 Fallback product name
        $fallbackProductName = optional(Product::find($order->product_id))->name;

        if (!is_null($order)) {
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
            'name'     => ['required'],
            'phone'    => ['required'],
            'address'  => ['required'],
        ];

        if ($request->courier == 3): //3 = pathao
            //  $validator['pathao_store_id']  = ['required'];
            $validator['pathao_city_id']   = ['required'];
            $validator['pathao_zone_id']   = ['required'];
        //  $validator['pathao_area_id']   = ['required'];
        //  $validator['sender_name']      = ['required'];
        //  $validator['sender_phone']     = ['required'];
        //  $validator['weight']           = ['required'];
        elseif ($request->courier == 1):
            $validator['gram_weight']  = ['required'];
        endif;

        Validator::make($request->all(), $validator, [], [
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

        if ($request->courier == 3): // 3 = pathao
            $order->sender_name    = $request->sender_name;
            $order->sender_phone   = $request->sender_phone;
            $order->courier        = $request->courier;
            $order->store          = $request->pathao_store_id;
            $order->city           = $request->pathao_city_id;
            $order->zone           = $request->pathao_zone_id;
            $order->area           = $request->pathao_area_id;
            // $order->quantity        = $request->quantity;
            $order->weight          = $request->weight;
        elseif ($request->courier == 1):
            $order->weight          = $request->gram_weight;
        endif;

        $order->pay            = $request->pay;
        $order->status     = $request->status;
        $order->sub_total  = $request->sub_total;
        $order->order_assign = $request->order_assign;
        $order->order_type = !empty($request->manual_order_type) ? $request->manual_order_type : ($order->order_type === Order::TYPE_ONLINE ? Order::TYPE_ONLINE : Order::TYPE_MANUAL);

        $order->save();

        // Book to courier when status is set to Courier Entry (2)
        if ($order->status == Order::STATUS_PENDING_DELIVERY && $order->courier && !$order->consignment_id) {
            $this->courierBookingService->bookOrder($order, $request);
        }

        Cart::where('order_id', $order->id)->delete();
        foreach ($request->products as $product) {
            $cart = new Cart();
            $cart->product_id = $product['id'];
            $cart->order_id   = $order->id;
            $cart->quantity   = $product['quantity'];
            $cart->price      = $product['price'];
            if (isset($product['attribute']) && is_array($product['attribute'])):
                $cart->attribute  =  $product['attribute'];
            endif;
            $this->applySelectedAttributesToCart($cart, $product['attribute'] ?? []);
            $cart->save();
        }

        return redirect()->route("employee.order.newmanage");
        // }



        return redirect()->back();
    }

    public function update_s(Request $request, $id)
    {
        $order = Order::find($id);
        $order->status = $request->status;
        $order->save();

        // Book to courier when status is set to Courier Entry (2)
        if ($order->status == Order::STATUS_PENDING_DELIVERY && $order->courier && !$order->consignment_id) {
            $this->courierBookingService->bookOrder($order, $request);
        }

        return redirect()->route('employee.order.manage');
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

    public function orderexport()
    {

        return Excel::download(new OrdersExport, 'order.xlsx');
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

    public function barcodeScan()
    {
        $settings = Settings::first();
        return view('employee.pages.orders.barcode-scan', compact('settings'));
    }

    public function scanOrder(Request $request)
    {
        $orderId = $request->input('order_id');

        if (!$orderId) {
            return response()->json([
                'success' => false,
                'message' => 'Order ID is required.',
            ], 400);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if (! in_array($order->status, [Order::STATUS_PRINTED_INVOICE, Order::STATUS_PENDING_RETURN], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Order status must be Printed Invoice or Pending Return to scan.',
            ], 400);
        }

        if (!$order->courier) {
            return response()->json([
                'success' => false,
                'message' => 'No courier selected for this order.',
            ], 400);
        }

        if ($order->status === Order::STATUS_PRINTED_INVOICE) {
            $order->status = Order::STATUS_TOTAL_DELIVERY;
            $message = 'Order status updated to Total Courier.';
        } else {
            $order->status = Order::STATUS_ORDER_RETURN;
            $message = 'Order status updated to Return.';
        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => $message,
            'order' => $order->fresh(),
            'consignment_id' => $order->consignment_id,
        ]);
    }
}

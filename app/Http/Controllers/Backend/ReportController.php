<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Settings;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employee_orders()
    {
        $settings = Settings::first();
        $orders = Order::orderBy('id', 'desc')->where('order_assign', 0)->get();
        $last = Order::orderBy('id', 'desc')->where('order_assign', 0)->first();

        // $carts=Cart::where('order_id',$orders->id)->get();
        return view('backend.pages.report.employee_orders', compact('orders', 'settings', 'last'));

    }

    public function employee_orders_search(Request $request)
    {

        $employee = $request->employee;
        $searchDays = $request->searchDays;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        $today = \Illuminate\Support\Facades\Date::today()->format('Y-m-d');

        if ($searchDays == 0) {
            $orders = Order::orderBy('id', 'desc')->whereDate('created_at', \Illuminate\Support\Facades\Date::today())->where('order_assign', $employee)->paginate(50);
            $total_orders = Order::whereDate('created_at', \Illuminate\Support\Facades\Date::today())->where('order_assign', $employee)->get();
        } elseif ($searchDays == 1) {
            $date = \Illuminate\Support\Facades\Date::today()->subDays(1)->format('Y-m-d');
            $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->paginate(50);
            $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->get();
        } elseif ($searchDays == 7) {
            $date = \Illuminate\Support\Facades\Date::today()->subDays(7)->format('Y-m-d');
            $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->paginate(50);
            $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->get();
        } elseif ($searchDays == 15) {
            $date = \Illuminate\Support\Facades\Date::today()->subDays(15)->format('Y-m-d');
            $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->paginate(50);
            $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->get();
        } elseif ($searchDays == 30) {
            $date = \Illuminate\Support\Facades\Date::today()->subDays(30)->format('Y-m-d');
            $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->paginate(50);
            $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->get();
        } elseif ($searchDays == 200) {
            $date_from = \Illuminate\Support\Facades\Date::parse($fromDate)->format('Y-m-d');
            $date_to = \Illuminate\Support\Facades\Date::parse($toDate)->format('Y-m-d');
            $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('order_assign', $employee)->paginate(50);
            $total_orders = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('order_assign', $employee)->get();
        } else {
            $orders = Order::orderBy('id', 'desc')->where('order_assign', $employee)->paginate(50);
            $total_orders = Order::where('order_assign', $employee)->get();
        }

        $settings = Settings::first();
        $carts = Cart::whereNotNull('order_id')->get();
        $users = User::where('role', 3)->get();

        // $result = Order::where('order_assign',$employee)->selectRaw('sum(total)')
        //     ->first();
        return view('backend.pages.report.employee_orders_search', compact('orders', 'settings', 'employee', 'total_orders', 'carts', 'users'));
    }

    public function employee_status($employee, $status, $searchDays, $fromDate = null, $toDate = null)
    {

        $today = \Illuminate\Support\Facades\Date::today()->format('Y-m-d');

        if ($searchDays == 0) {
            if ($status == 0) {
                $orders = Order::orderBy('id', 'desc')->whereDate('created_at', \Illuminate\Support\Facades\Date::today())->where('order_assign', $employee)->paginate(50);
                $total_orders = Order::whereDate('created_at', \Illuminate\Support\Facades\Date::today())->where('order_assign', $employee)->get();
            } else {
                $orders = Order::orderBy('id', 'desc')->whereDate('created_at', \Illuminate\Support\Facades\Date::today())->where('order_assign', $employee)->where('status', $status)->paginate(50);
                $total_orders = Order::whereDate('created_at', \Illuminate\Support\Facades\Date::today())->where('order_assign', $employee)->get();
            }
        } elseif ($searchDays == 1) {
            if ($status == 0) {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(1)->format('Y-m-d');
                $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->paginate(50);
                $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->get();
            } else {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(1)->format('Y-m-d');
                $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->where('status', $status)->paginate(50);
                $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->get();
            }

        } elseif ($searchDays == 7) {
            if ($status == 0) {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(7)->format('Y-m-d');
                $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->paginate(50);
                $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->get();
            } else {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(7)->format('Y-m-d');
                $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->where('status', $status)->paginate(50);
                $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->get();
            }

        } elseif ($searchDays == 15) {
            if ($status == 0) {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(15)->format('Y-m-d');
                $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->paginate(50);
                $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->get();
            } else {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(15)->format('Y-m-d');
                $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->where('status', $status)->paginate(50);
                $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->get();
            }

        } elseif ($searchDays == 30) {
            if ($status == 0) {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(30)->format('Y-m-d');
                $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->paginate(50);
                $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->get();
            } else {
                $date = \Illuminate\Support\Facades\Date::today()->subDays(30)->format('Y-m-d');
                $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->where('status', $status)->paginate(50);
                $total_orders = Order::whereBetween('created_at', [$date.' 00:00:00', $today.' 23:59:59'])->where('order_assign', $employee)->get();
            }
        } elseif ($searchDays == 200) {
            if ($status == 0) {
                $date_from = \Illuminate\Support\Facades\Date::parse($fromDate)->format('Y-m-d');
                $date_to = \Illuminate\Support\Facades\Date::parse($toDate)->format('Y-m-d');
                $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('order_assign', $employee)->paginate(50);
                $total_orders = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('order_assign', $employee)->get();
            } else {
                $date_from = \Illuminate\Support\Facades\Date::parse($fromDate)->format('Y-m-d');
                $date_to = \Illuminate\Support\Facades\Date::parse($toDate)->format('Y-m-d');
                $orders = Order::orderBy('id', 'desc')->whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('order_assign', $employee)->where('status', $status)->paginate(50);
                $total_orders = Order::whereBetween('created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])->where('order_assign', $employee)->get();
            }

        } else {
            if ($status == 0) {
                $orders = Order::orderBy('id', 'desc')->where('order_assign', $employee)->paginate(50);
                $total_orders = Order::where('order_assign', $employee)->get();
            } else {
                $orders = Order::orderBy('id', 'desc')->where('order_assign', $employee)->where('status', $status)->paginate(50);
                $total_orders = Order::where('order_assign', $employee)->get();
            }

        }

        $settings = Settings::first();
        $carts = Cart::whereNotNull('order_id')->get();
        $users = User::where('role', 3)->get();

        // $result = Order::where('order_assign',$employee)->selectRaw('sum(total)')
        //     ->first();
        return view('backend.pages.report.employee_orders_search', compact('orders', 'settings', 'employee', 'total_orders', 'carts', 'users'));

    }

    public function product_orders()
    {

        $settings = Settings::first();

        $orders = Order::orderBy('id', 'desc')->where('order_assign', 0)->get();
        $last = Order::orderBy('id', 'desc')->where('order_assign', 0)->first();

        // $carts=Cart::where('order_id',$orders->id)->get();
        return view('backend.pages.report.product_orders', compact('orders', 'settings', 'last'));

    }

    public function products_orders()
    {
        // ok

        $all_carts = Cart::with(['order', 'product'])->whereNotNull('order_id')->get();
        // dd($all_carts);
        $topsales = DB::table('carts')
            ->leftJoin('products', 'products.id', '=', 'carts.product_id')
            ->select('products.id', 'products.name', 'carts.product_id', DB::raw('COUNT(carts.product_id) as total'))
            ->groupBy('products.id', 'carts.product_id', 'products.name')
            ->orderBy('total', 'desc')
            ->get();

        return view('backend.pages.report.ordered_product_c', compact('all_carts', 'topsales'));

    }

    public function product_orders_search(Request $request)
    {
        // ok
        $product = $request->product;
        $searchDays = $request->searchDays;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $today = \Illuminate\Support\Facades\Date::today()->format('Y-m-d');

        if ($searchDays == 0) {
            $orders = DB::table('orders')
                ->orderBy('orders.id', 'desc')
                ->join('carts', 'orders.id', '=', 'carts.order_id')
                ->where('carts.product_id', '=', $product)
                ->whereDate('orders.created_at', \Illuminate\Support\Facades\Date::today())
                ->select('orders.*', 'carts.*')
                ->paginate(50);

        } elseif ($searchDays == 1) {
            $date = \Illuminate\Support\Facades\Date::today()->subDays(1)->format('Y-m-d');
            $orders = DB::table('orders')
                ->orderBy('orders.id', 'desc')
                ->join('carts', 'orders.id', '=', 'carts.order_id')
                ->where('carts.product_id', '=', $product)
                ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                ->select('orders.*', 'carts.*')
                ->paginate(50);

        } elseif ($searchDays == 7) {
            $date = \Illuminate\Support\Facades\Date::today()->subDays(7)->format('Y-m-d');
            $orders = DB::table('orders')
                ->orderBy('orders.id', 'desc')
                ->join('carts', 'orders.id', '=', 'carts.order_id')
                ->where('carts.product_id', '=', $product)
                ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                ->select('orders.*', 'carts.*')
                ->paginate(50);

        } elseif ($searchDays == 15) {
            $date = \Illuminate\Support\Facades\Date::today()->subDays(7)->format('Y-m-d');
            $orders = DB::table('orders')
                ->orderBy('orders.id', 'desc')
                ->join('carts', 'orders.id', '=', 'carts.order_id')
                ->where('carts.product_id', '=', $product)
                ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                ->select('orders.*', 'carts.*')
                ->paginate(50);

        } elseif ($searchDays == 30) {
            $date = \Illuminate\Support\Facades\Date::today()->subDays(7)->format('Y-m-d');
            $orders = DB::table('orders')
                ->orderBy('orders.id', 'desc')
                ->join('carts', 'orders.id', '=', 'carts.order_id')
                ->where('carts.product_id', '=', $product)
                ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                ->select('orders.*', 'carts.*')
                ->paginate(50);

        } elseif ($searchDays == 200) {
            $date_from = \Illuminate\Support\Facades\Date::parse($fromDate)->format('Y-m-d');
            $date_to = \Illuminate\Support\Facades\Date::parse($toDate)->format('Y-m-d');

            $orders = DB::table('orders')
                ->orderBy('orders.id', 'desc')
                ->join('carts', 'orders.id', '=', 'carts.order_id')
                ->where('carts.product_id', '=', $product)
                ->whereBetween('orders.created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])
                ->select('orders.*', 'carts.*')
                ->paginate(50);

        } else {
            $orders = DB::table('orders')

                ->orderBy('orders.id', 'desc')
                ->join('carts', 'orders.id', '=', 'carts.order_id')
                ->where('carts.product_id', '=', $product)
                ->select('orders.*', 'carts.*')
                ->paginate(50);

        }

        $product_details = Product::find($product);
        $users = User::where('role', 3)->get();

        $last = Order::first();

        return view('backend.pages.report.product_orders_search', compact('product', 'orders', 'last', 'users', 'product_details'));

    }

    public function total_order_product($date_from = null, $date_to = null, $prd = null)
    {

        $date_from = \Illuminate\Support\Facades\Date::parse($date_from)->format('Y-m-d');
        $date_to = \Illuminate\Support\Facades\Date::parse($date_to)->format('Y-m-d');

        $total_orders = DB::table('orders')
            ->orderBy('orders.id', 'desc')
            ->join('carts', 'orders.id', '=', 'carts.order_id')
            ->where('carts.product_id', '=', $prd)
            ->whereBetween('orders.created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])
            ->select('orders.*')
            ->get();

        $total = $total_orders->count();
        $processing = $total_orders->where('status', 1)->count();
        $pending_Delivery = $total_orders->where('status', 2)->count();
        $on_Hold = $total_orders->where('status', 3)->count();
        $cancel = $total_orders->where('status', 4)->count();
        $completed = $total_orders->where('status', 5)->count();
        $pending_Payment = $total_orders->where('status', 6)->count();
        $on_Delivery = $total_orders->where('status', 7)->count();

        $no_response1 = $total_orders->where('status', 8)->count();
        $no_response2 = $total_orders->where('status', 9)->count();
        $courier_hold = $total_orders->where('status', 11)->count();
        $return = $total_orders->where('status', 12)->count();

        return response()->json(['total' => $total, 'processing' => $processing, 'pending_Delivery' => $pending_Delivery, 'on_Hold' => $on_Hold, 'cancel' => $cancel, 'completed' => $completed, 'pending_Payment' => $pending_Payment, 'on_Delivery' => $on_Delivery, 'no_response1' => $no_response1, 'no_response2' => $no_response2, 'courier_hold' => $courier_hold, 'return' => $return]);

    }

    public function product_status($product, $status, $searchDays, $fromDate = null, $toDate = null)
    {

        // ok

        if ($searchDays == 0) {
            if ($status == 0) {
                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereDate('orders.created_at', \Illuminate\Support\Facades\Date::today())
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereDate('orders.created_at', \Illuminate\Support\Facades\Date::today())
                    ->select('orders.*')
                    ->get();
            } else {
                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereDate('orders.created_at', \Illuminate\Support\Facades\Date::today())
                    ->where('orders.status', $status)
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereDate('orders.created_at', \Illuminate\Support\Facades\Date::today())
                    ->select('orders.*')
                    ->get();
            }

        } elseif ($searchDays == 1) {
            $date = \Illuminate\Support\Facades\Date::today()->subDays(1)->format('Y-m-d');
            if ($status == 0) {
                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->select('orders.*')
                    ->get();
            } else {

                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->where('orders.status', $status)
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->select('orders.*')
                    ->get();
            }

        } elseif ($searchDays == 7) {
            $date = \Illuminate\Support\Facades\Date::today()->subDays(7)->format('Y-m-d');
            if ($status == 0) {
                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->select('orders.*')
                    ->get();
            } else {
                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->where('orders.status', $status)
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->select('orders.*')
                    ->get();
            }

        } elseif ($searchDays == 15) {
            $date = \Illuminate\Support\Facades\Date::today()->subDays(7)->format('Y-m-d');
            if ($status == 0) {
                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->select('orders.*')
                    ->get();
            } else {
                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->where('orders.status', $status)
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->select('orders.*')
                    ->get();
            }

        } elseif ($searchDays == 30) {
            $date = \Illuminate\Support\Facades\Date::today()->subDays(7)->format('Y-m-d');
            if ($status == 0) {
                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->select('orders.*')
                    ->get();
            } else {
                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->where('orders.status', $status)
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date.' 00:00:00', $today.' 23:59:59'])
                    ->select('orders.*')
                    ->get();
            }

        } elseif ($searchDays == 200) {
            $date_from = \Illuminate\Support\Facades\Date::parse($fromDate)->format('Y-m-d');
            $date_to = \Illuminate\Support\Facades\Date::parse($toDate)->format('Y-m-d');
            if ($status == 0) {
                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])
                    ->select('orders.*')
                    ->get();
            } else {
                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])
                    ->where('orders.status', $status)
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->whereBetween('orders.created_at', [$date_from.' 00:00:00', $date_to.' 23:59:59'])
                    ->select('orders.*')
                    ->get();
            }

        } else {

            if ($status == 0) {
                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->select('orders.*')
                    ->get();
            } else {

                $orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->where('orders.status', $status)
                    ->select('orders.*', 'carts.*')
                    ->paginate(50);
                $total_orders = DB::table('orders')
                    ->orderBy('orders.id', 'desc')
                    ->join('carts', 'orders.id', '=', 'carts.order_id')
                    ->where('carts.product_id', '=', $product)
                    ->select('orders.*')
                    ->get();
            }

        }

        $product_details = Product::find($product);
        $users = User::where('role', 3)->get();
        $carts = Cart::get();
        $last = Order::first();

        return view('backend.pages.report.product_orders_search', compact('product', 'orders', 'last', 'total_orders', 'carts', 'users', 'product_details'));
    }

    public function store(Request $request)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

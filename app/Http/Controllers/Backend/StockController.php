<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Settings;
use App\Models\StockLog;
use App\Services\StockManagementService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockController extends Controller
{
    public function __construct(protected StockManagementService $stockService) {}

    /**
     * Display the stock overview dashboard for base products.
     */
    public function index(Request $request): View
    {
        $settings = Settings::getSettings();

        $query = Product::whereNull('base_id')->with(['combos', 'category', 'brand']);

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'out') {
                $query->where(function ($q): void {
                    $q->whereNull('stock')
                        ->orWhere('stock', '<=', 0);
                });
            } elseif ($request->stock_status === 'low') {
                $query->whereBetween(DB::raw('CAST(COALESCE(stock, 0) AS SIGNED)'), [1, 5]);
            } elseif ($request->stock_status === 'in') {
                $query->where(DB::raw('CAST(COALESCE(stock, 0) AS SIGNED)'), '>', 5);
            }
        }

        $products = $query->orderBy(DB::raw('CAST(COALESCE(stock, 0) AS SIGNED)'), 'asc')->paginate(25)->withQueryString();

        // Summary Statistics
        $totalBaseProducts = Product::whereNull('base_id')->count();
        $totalCombos = Product::whereNotNull('base_id')->count();
        $totalStockUnits = (int) Product::whereNull('base_id')->sum(DB::raw('CAST(COALESCE(stock, 0) AS SIGNED)'));
        $lowStockCount = Product::whereNull('base_id')->where(DB::raw('CAST(COALESCE(stock, 0) AS SIGNED)'), '<=', 5)->count();
        $outOfStockCount = Product::whereNull('base_id')->where(function ($q): void {
            $q->whereNull('stock')->orWhere('stock', '<=', 0);
        })->count();

        $categories = Category::orderBy('title')->get();
        $baseProductsList = Product::whereNull('base_id')->orderBy('name')->get(['id', 'name', 'sku', 'stock']);

        return view('backend.pages.stock.index', compact(
            'products',
            'settings',
            'categories',
            'baseProductsList',
            'totalBaseProducts',
            'totalCombos',
            'totalStockUnits',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    /**
     * Display purchase / stock intake history.
     */
    public function purchases(Request $request): View
    {
        $settings = Settings::getSettings();

        $query = Purchase::with(['product', 'creator']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('supplier')) {
            $query->where('supplier', 'like', '%'.trim((string) $request->supplier).'%');
        }

        if ($request->filled('invoice_no')) {
            $query->where('invoice_no', 'like', '%'.trim((string) $request->invoice_no).'%');
        }

        if ($request->filled('from_date')) {
            $query->whereDate('purchase_date', '>=', Carbon::parse($request->from_date));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('purchase_date', '<=', Carbon::parse($request->to_date));
        }

        $purchases = $query->orderByDesc('purchase_date')->orderByDesc('id')->paginate(25)->withQueryString();

        $baseProducts = Product::whereNull('base_id')->orderBy('name')->get(['id', 'name', 'sku', 'stock']);

        $totalPurchasedUnits = (int) Purchase::sum('quantity');
        $totalPurchasedCost = (float) Purchase::sum('total_price');

        return view('backend.pages.stock.purchases', compact(
            'purchases',
            'settings',
            'baseProducts',
            'totalPurchasedUnits',
            'totalPurchasedCost'
        ));
    }

    /**
     * Store a newly created stock purchase / intake.
     */
    public function storePurchase(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'invoice_no' => 'nullable|string|max:100',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->stockService->recordPurchase($request->all(), auth()->id());

            return back()->with([
                'message' => 'Stock purchase recorded successfully! Product stock updated.',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return back()->withInput()->with([
                'message' => 'Failed to record purchase: '.$e->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }

    /**
     * Display complete stock ledger and movement logs.
     */
    public function logs(Request $request): View
    {
        $settings = Settings::getSettings();

        $query = StockLog::with(['product', 'order', 'creator', 'purchase']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->from_date));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->to_date));
        }

        $logs = $query->orderByDesc('id')->paginate(30)->withQueryString();

        $baseProducts = Product::whereNull('base_id')->orderBy('name')->get(['id', 'name', 'sku']);

        return view('backend.pages.stock.logs', compact('logs', 'settings', 'baseProducts'));
    }

    /**
     * Manual stock adjustment.
     */
    public function adjust(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'new_stock' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
        ]);

        $product = Product::whereNull('base_id')->findOrFail($request->product_id);

        $this->stockService->adjustStock($product, (int) $request->new_stock, $request->reason, auth()->id());

        return back()->with([
            'message' => "Stock adjusted for {$product->name} to {$request->new_stock} units.",
            'alert-type' => 'info',
        ]);
    }
}

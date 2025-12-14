<?php

namespace App\Http\Controllers\Frontend\IncompleteOrder;

use Log;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\IncompleteOrder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\WhatsAppService;

class IncompleteOrderController extends Controller
{
    /**
     * Auto-save incomplete order (for cart/session auto-save)
     */
    public function autoSave(Request $request)
    {
        // Log::info('autoSave called', $request->all());

        // Token is required for identifying the session/order
        $request->validate([
            'token' => 'required|string|max:100',
        ]);

        // Normalize phone: remove non-digits, drop leading '88' if present
        $phoneRaw = $request->input('phone', '');
        $phone = preg_replace('/\D/', '', (string) $phoneRaw);
        if (strlen($phone) === 13 && substr($phone, 0, 2) === '88') {
            $phone = substr($phone, 2);
        }

        // Validate BD 11-digit mobile
        if (!preg_match('/^01[13-9]\d{8}$/', $phone) || strlen($phone) !== 11) {
            return response()->json(['status' => 'invalid_phone'], 422);
        }

        $recentOrderExists = Order::where('phone', $phone)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->exists();

        if ($recentOrderExists) {
            IncompleteOrder::where('phone', $phone)->delete();
            return response()->json([
                'ok' => false,
                'skipped' => true,
                'reason' => 'recent_order_exists',
            ], 200);
        }

        $productIds   = (array) $request->input('product_ids', []);
        $productSlugs = (array) $request->input('product_slugs', []);
        $cartSnapshot = $this->decodeJson($request->input('cart_snapshot'));

        $saved = 0;

        foreach ($productIds as $i => $productId) {
            $slug = $productSlugs[$i] ?? null;

            // Check if an active incomplete order exists for this phone + product slug
            $existing = IncompleteOrder::where('phone', $phone)
                        ->where('product_slug', $slug)
                        ->where('status', 0)
                        ->first();

            if ($existing) {
                // Update existing row
                $existing->update([
                    'last_activity_at' => Carbon::now(),
                    'cart_snapshot' => $cartSnapshot,
                    'name' => $request->input('name'),
                    'address' => $request->input('address'),
                    'shipping_method_label' => $request->input('shipping_method_label'),
                    'shipping_amount' => $this->intValOrNull($request->input('shipping_amount')),
                    'sub_total' => $this->parseMoney($request->input('sub_total')),
                    'total' => $this->intValOrNull($request->input('total')),
                    'product_id' => $productId,
                ]);
                continue;
            }

            // Create new row with a fresh unique token
            try {
                IncompleteOrder::create([
                    'token' => Str::uuid()->toString(),
                    'user_id' => User::where('role', 3)->inRandomOrder()->where('status', 1)->first()->id,
                    'ip_address' => $request->ip(),
                    'name' => $request->input('name'),
                    'address' => $request->input('address'),
                    'phone' => $phone,
                    'shipping_method_label' => $request->input('shipping_method_label'),
                    'shipping_amount' => $this->intValOrNull($request->input('shipping_amount')),
                    'sub_total' => $this->parseMoney($request->input('sub_total')),
                    'total' => $this->intValOrNull($request->input('total')),
                    'product_id' => $productId,
                    'product_slug' => $slug,
                    'cart_snapshot' => $cartSnapshot,
                    'status' => 0,
                    'last_activity_at' => Carbon::now(),
                ]);
                $saved++;
            } catch (\Exception $e) {
                // Log::warning('IncompleteOrder create failed: '.$e->getMessage());
            }
        }

        return response()->json([
            'ok' => true,
            'saved' => $saved,
            'processed' => count($productIds),
        ]);
    }

    private function parseMoney($val)
    {
        if (!$val) return 0;
        // Remove ৳, commas, spaces
        $val = preg_replace('/[৳,\s]/', '', $val);
        // Divide by 100 to convert from "cents" to whole currency
        return round((float) $val / 100, 2);
    }

    /**
     * Convert value to integer or null
     */
    private function intValOrNull($value)
    {
        if ($value === null || $value === '') return null;
        return (int) preg_replace('/\D+/', '', (string) $value);
    }

    /**
     * Decode JSON safely
     */
    private function decodeJson($value)
    {
        if (!$value) return [];
        if (is_array($value)) return $value;

        try {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            return [];
        }
    }


   public function convertToOrder($id, WhatsAppService $whatsAppService)
{
    $incomplete = IncompleteOrder::findOrFail($id);
    // Log::info('Converting Incomplete Order', ['id' => $id, 'incomplete' => $incomplete]);

    // Collect product slugs
    $slugs = [];

    if (isset($incomplete->cart_snapshot) && is_array($incomplete->cart_snapshot) && !empty($incomplete->cart_snapshot)) {
        foreach ($incomplete->cart_snapshot as $item) {
            // Log::info('Processing cart item', $item);

            // If snapshot already has slug
            if (!empty($item['slug'])) {
                $slugs[] = $item['slug'];
                // Log::info('Slug found in snapshot', ['slug' => $item['slug']]);
                // continue;
            }

            // Otherwise lookup product
            if (!empty($item['product_id'])) {
                $product = \App\Models\Product::find($item['product_id']);
                // Log::info('Looked up product', ['product_id' => $item['product_id'], 'product' => $product]);

                if ($product && !empty($product->slug)) {
                    $slugs[] = $product->slug;
                    // Log::info('Slug added from product lookup', ['slug' => $product->slug]);
                } else {
                    // Log::warning('Product slug missing', ['product_id' => $item['product_id']]);
                }
            }
        }
    }

    // 🔥 Always add incomplete->product_slug if it exists
    if (!empty($incomplete->product_slug)) {
        $slugs[] = $incomplete->product_slug;
        // Log::info('Fallback slug added from incomplete order', ['slug' => $incomplete->product_slug]);
    }

    // Fetch random user for assignment
    // $user = User::where('role', 3)->inRandomOrder()->first();
    // Assign user based on role
    $user = auth()->user();
    if ($user && $user->role == 3) {
        $assignedUser = $user;
    } else if ($incomplete->user_id) {
        $assignedUser = $incomplete->user;
    } else {
        $assignedUser = User::where('role', 3)->inRandomOrder()->where('status', 1)->first();
    }

    // Create new order
    $order = new Order();
    $order->name            = $incomplete->name;
    $order->phone           = $incomplete->phone;
    $order->address         = $incomplete->address;
    $order->shipping_method = $incomplete->shipping_method_label ?? null;
    $order->product_id      = $incomplete->product_id ?? null;
    $order->shipping_cost   = $incomplete->shipping_amount ?? 0;
    $order->sub_total       = $incomplete->sub_total ?? 0;
    $order->total           = $incomplete->total ?? 0;
    $order->status          = 1; // mark as completed
    $order->ip_address      = $incomplete->ip_address;
    // $order->order_assign    = $user->id;
    $order->order_assign = $assignedUser?->id ?? null;
    $order->order_type = Order::TYPE_CONVERTED;

    // ✅ Save product slugs (unique)
    $order->product_slug = !empty($slugs) ? json_encode(array_values(array_unique($slugs))) : null;

    // Log::info('Final slugs to save in order', [
    //     'slugs' => $slugs,
    //     'order_product_slug' => $order->product_slug
    // ]);

    $order->save();

    $cart = new Cart();
    $product = $incomplete->product;
    $cart->product_id = $product->id ?? null;
    $cart->order_id   = $order->id;
    $cart->quantity   = 1;
    $cart->price      = $incomplete->sub_total;

    // Log::info('Saving cart row', [
    //     'product_id'   => $cart->product_id,
    //     'product_slug' => $cart->product_slug,
    // ]);

    $cart->save();

    // Send WhatsApp notification after products are attached
    $whatsAppService->sendOrderNotification($order);

    // Delete incomplete order after conversion
    $incomplete->delete();
    // Log::info('Incomplete order deleted', ['id' => $id]);

    return redirect()
        ->route('order.incomplete')
        ->with('success', 'Order created successfully from incomplete order.');
}




}

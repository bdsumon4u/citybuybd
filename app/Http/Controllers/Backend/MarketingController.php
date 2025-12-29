<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Channels\SmsChannel;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Notifications\BulkSmsNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class MarketingController extends Controller
{
    public function index()
    {
        $products = Product::latest()->select('id', 'name')->get();
        $statusOptions = [
            Order::STATUS_PROCESSING => 'Processing',
            Order::STATUS_PENDING_DELIVERY => 'Courier Entry',
            Order::STATUS_ON_HOLD => 'On Hold',
            Order::STATUS_CANCEL => 'Cancel',
            Order::STATUS_COMPLETED => 'Completed',
            Order::STATUS_PENDING_PAYMENT => 'Pending Payment',
            Order::STATUS_ON_DELIVERY => 'On Delivery',
            Order::STATUS_NO_RESPONSE1 => 'No Response 1',
            Order::STATUS_NO_RESPONSE2 => 'No Response 2',
            Order::STATUS_COURIER_HOLD => 'Courier Hold',
            Order::STATUS_ORDER_RETURN => 'Return',
        ];

        $orderCount = 0;
        $orders = collect();

        return view('backend.pages.marketing.index', compact('products', 'orderCount', 'orders', 'statusOptions'));
    }

    public function filter(Request $request)
    {
        $products = Product::latest()->select('id', 'name')->get();
        $orderCount = 0;
        $orders = collect();

        $query = Order::query()->distinct();

        if ($request->filled('date_range_type')) {
            if ($request->date_range_type === 'last_days') {
                if ($request->filled('last_days')) {
                    $days = (int) $request->last_days;
                    $query->where('created_at', '>=', Carbon::now()->subDays($days));
                }
            } elseif ($request->date_range_type === 'date_range') {
                if ($request->filled('from_date') && $request->filled('to_date')) {
                    $fromDate = Carbon::parse($request->from_date)->startOfDay();
                    $toDate = Carbon::parse($request->to_date)->endOfDay();
                    $query->whereBetween('created_at', [$fromDate, $toDate]);
                }
            }
        }

        if ($request->filled('product_ids')) {
            $productIds = is_array($request->product_ids) ? $request->product_ids : [$request->product_ids];
            $query->whereHas('many_cart', function ($q) use ($productIds) {
                $q->whereIn('product_id', $productIds);
            });
        }

        if ($request->filled('statuses')) {
            $statuses = is_array($request->statuses) ? $request->statuses : [$request->statuses];
            $query->whereIn('status', $statuses);
        }

        $orderCount = $query->count();
        $orders = $query->select('orders.id', 'orders.name', 'orders.phone', 'orders.created_at', 'orders.status')
            ->orderBy('orders.created_at', 'desc')
            ->limit(100)
            ->get();

        $statusOptions = [
            Order::STATUS_PROCESSING => 'Processing',
            Order::STATUS_PENDING_DELIVERY => 'Courier Entry',
            Order::STATUS_ON_HOLD => 'On Hold',
            Order::STATUS_CANCEL => 'Cancel',
            Order::STATUS_COMPLETED => 'Completed',
            Order::STATUS_PENDING_PAYMENT => 'Pending Payment',
            Order::STATUS_ON_DELIVERY => 'On Delivery',
            Order::STATUS_NO_RESPONSE1 => 'No Response 1',
            Order::STATUS_NO_RESPONSE2 => 'No Response 2',
            Order::STATUS_COURIER_HOLD => 'Courier Hold',
            Order::STATUS_ORDER_RETURN => 'Return',
        ];

        return view('backend.pages.marketing.index', compact('products', 'orderCount', 'orders', 'statusOptions'));
    }

    public function sendBulkSms(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'date_range_type' => 'nullable|in:last_days,date_range',
            'last_days' => 'nullable|integer|min:1',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'statuses' => 'nullable|array',
            'statuses.*' => 'integer',
        ], [
            'message.required' => 'SMS message is required.',
            'message.max' => 'SMS message cannot exceed 1000 characters.',
            'to_date.after_or_equal' => 'To date must be after or equal to from date.',
            'product_ids.*.exists' => 'One or more selected products are invalid.',
            'statuses.*.integer' => 'One or more selected statuses are invalid.',
        ]);

        $query = Order::query()->distinct();

        if ($request->filled('date_range_type')) {
            if ($request->date_range_type === 'last_days') {
                if ($request->filled('last_days')) {
                    $days = (int) $request->last_days;
                    $query->where('created_at', '>=', Carbon::now()->subDays($days));
                }
            } elseif ($request->date_range_type === 'date_range') {
                if ($request->filled('from_date') && $request->filled('to_date')) {
                    $fromDate = Carbon::parse($request->from_date)->startOfDay();
                    $toDate = Carbon::parse($request->to_date)->endOfDay();
                    $query->whereBetween('created_at', [$fromDate, $toDate]);
                }
            }
        }

        if ($request->filled('product_ids')) {
            $productIds = is_array($request->product_ids) ? $request->product_ids : [$request->product_ids];
            $query->whereHas('many_cart', function ($q) use ($productIds) {
                $q->whereIn('product_id', $productIds);
            });
        }

        if ($request->filled('statuses')) {
            $statuses = is_array($request->statuses) ? $request->statuses : [$request->statuses];
            $query->whereIn('status', $statuses);
        }

        $orders = $query->get();

        $uniquePhones = [];
        $ordersByPhone = [];

        foreach ($orders as $order) {
            if (empty($order->phone)) {
                continue;
            }

            $phone = preg_replace('/[^\d]/', '', (string) $order->phone);
            if (Str::startsWith($phone, '01')) {
                $phone = '88' . $phone;
            } elseif (Str::startsWith($phone, '+8801')) {
                $phone = Str::replaceFirst('+', '', $phone);
            }

            if (!isset($uniquePhones[$phone])) {
                $uniquePhones[$phone] = true;
                $ordersByPhone[$phone] = $order;
            }
        }

        if (empty($ordersByPhone)) {
            return redirect()->route('marketing.filter', $request->except(['message', '_token']))
                ->withErrors(['message' => 'No orders found with valid phone numbers matching the selected criteria.'])
                ->withInput();
        }

        $sentCount = 0;
        $failedCount = 0;
        $errors = [];

        // If single order, replace {name} with actual customer name
        // If multiple orders, collect all phone numbers and send once
        if (count($ordersByPhone) === 1) {
            // Single order - send with customer name replacement
            $order = reset($ordersByPhone);
            $phone = key($ordersByPhone);

            try {
                $customerName = $order->name ?? 'Customer';
                $order->notify(new BulkSmsNotification($validated['message'], $customerName));
                $sentCount++;
                Log::info('Bulk SMS sent to phone: ' . $phone . ', Order ID: ' . $order->id . ', Name: ' . $customerName);
            } catch (\Exception $e) {
                Log::error('Bulk SMS failed for phone ' . $phone . ' (Order ID: ' . $order->id . '): ' . $e->getMessage());
                $failedCount++;
                $errors[] = 'Failed to send SMS to ' . $phone . ': ' . $e->getMessage();
            }
        } else {
            // Multiple orders - collect all phone numbers and send once
            $phoneNumbers = array_keys($ordersByPhone);

            // Create a simple notifiable object with array of phone numbers
            $notifiable = new \stdClass();
            $notifiable->phone = $phoneNumbers;

            try {
                // Create notification instance
                $notification = new BulkSmsNotification($validated['message']);

                // Send directly via SmsChannel
                $smsChannel = new SmsChannel();
                $smsChannel->send($notifiable, $notification);

                $sentCount = count($phoneNumbers);
                Log::info('Bulk SMS sent to ' . count($phoneNumbers) . ' phone numbers: ' . implode(', ', $phoneNumbers));
            } catch (\Exception $e) {
                Log::error('Bulk SMS failed for multiple phones: ' . $e->getMessage());
                $failedCount = count($phoneNumbers);
                $errors[] = 'Failed to send SMS: ' . $e->getMessage();
            }
        }

        if ($sentCount > 0) {
            $notification = [
                'message' => "SMS sent to {$sentCount} customer(s). " . ($failedCount > 0 ? "{$failedCount} failed." : ''),
                'alert-type' => 'success',
            ];
        } else {
            $notification = [
                'message' => "Failed to send SMS to all customers. Please check the logs for details.",
                'alert-type' => 'error',
            ];
        }

        $redirectParams = $request->except(['message', '_token']);

        if (!empty($errors) && $failedCount > 0) {
            return redirect()->route('marketing.filter', $redirectParams)
                ->with($notification)
                ->withErrors(['sms_errors' => $errors])
                ->withInput();
        }

        return redirect()->route('marketing.filter', $redirectParams)
            ->with($notification)
            ->withInput();
    }
}

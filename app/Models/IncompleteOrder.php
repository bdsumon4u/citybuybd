<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncompleteOrder extends Model
{
    protected $fillable = [
        'token', 'user_id', 'ip_address',
        'name', 'address', 'phone',
        'shipping_method_label', 'shipping_amount', 'sub_total', 'total',
        'product_id', 'product_slug',
        'cart_snapshot', 'last_activity_at',
        'status', 'completed_at',
    ];

    protected $casts = [
        'cart_snapshot' => 'array',
        'last_activity_at' => 'datetime',
        'completed_at' => 'datetime',
        'status' => 'integer',
        'sub_total' => 'decimal:2',
    ];

    // helper
    public function isCompleted(): bool
    {
        return (int) $this->status === 1;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); // adjust column if different
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

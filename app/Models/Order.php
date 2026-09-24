<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    //
    protected $fillable = [
        'order_number',
        'user_id',
        'delivery_name',
        'delivery_phone',
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_country',
        'delivery_postal_code',
        'subtotal',
        'coupon_discount',
        'shipping_charge',
        'grand_total',
        'payment_method',
        'payment_status',
        'order_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_cart_value',
        'usage_limit',
        'used_count',
        'expires_at',
        'status',
    ];
}

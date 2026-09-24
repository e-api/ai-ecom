<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    //
    protected $fillable = [
        'min_amount',
        'max_amount',
        'shipping_charge',
    ];
}

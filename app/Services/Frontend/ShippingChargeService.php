<?php

namespace App\Services\Frontend;
use App\Models\ShippingCharge;

class ShippingChargeService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getShippingCharge($subtotal)
    {
        $shippingRule = ShippingCharge::where(
            'min_amount',
            '<=',
            $subtotal
        )
        ->where(function ($query) use ($subtotal) {
            $query->whereNull('max_amount')
                ->orWhere(
                    'max_amount',
                    '>=',
                    $subtotal
                );
        })
        ->first();
        return $shippingRule
            ? $shippingRule->shipping_charge
            : 0;
    }
}

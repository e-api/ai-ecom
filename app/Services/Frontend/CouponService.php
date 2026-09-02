<?php

namespace App\Services\Frontend;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CouponService
{
    public function applyCoupon($code)
    {
        /*
        |--------------------------------------------------------------------------
        | Find Coupon
        |--------------------------------------------------------------------------
        */
        $coupon = Coupon::with([
            'categories',
            'brands'
        ])
        ->where('code', trim($code))
        ->first();

        if (!$coupon) {
            throw new \Exception('Invalid coupon code.');
        }

        if (!$coupon->status) {
            throw new \Exception('This coupon is inactive.');
        }

        /*
        |--------------------------------------------------------------------------
        | Check Coupon Expiry
        |--------------------------------------------------------------------------
        */
        if (
            $coupon->expires_at &&
            Carbon::now()->gt($coupon->expires_at)
        ) {
            throw new \Exception('This coupon has expired.');
        }

        /*
        |--------------------------------------------------------------------------
        | Check Usage Limit
        |--------------------------------------------------------------------------
        */
        if (
            $coupon->usage_limit !== null &&
            $coupon->used_count >= $coupon->usage_limit
        ) {
            throw new \Exception(
                'This coupon has reached its usage limit.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Cart Items
        |--------------------------------------------------------------------------
        */
        if (Auth::check()) {
            $cartItems = CartItem::with([
                'product.category',
                'product.brand',
            ])
            ->where('user_id', Auth::id())
            ->get();
        } else {
            $cartItems = collect(session('cart', []));
        }

        if ($cartItems->isEmpty()) {
            throw new \Exception('Your cart is empty.');
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Cart Total
        |--------------------------------------------------------------------------
        */
        $cartTotal = 0;

        /*
        |--------------------------------------------------------------------------
        | Calculate Eligible Amount
        |--------------------------------------------------------------------------
        */
        $eligibleAmount = 0;

        foreach ($cartItems as $item) {
            /*
            |--------------------------------------------------------------------------
            | Logged In User
            |--------------------------------------------------------------------------
            */
            if (Auth::check()) {
                $product = $item->product;
                $quantity = $item->quantity;
            } else {
                /*
                |--------------------------------------------------------------------------
                | Guest User
                |--------------------------------------------------------------------------
                */
                $product = Product::with([
                    'category',
                    'brand',
                ])->find($item['product_id']);

                $quantity = $item['quantity'];

                if (!$product) {
                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Product Price 
            |--------------------------------------------------------------------------
            */
            $price = $product->sale_price ?? $product->price;

            $itemTotal = $price * $quantity;

            /*
            |--------------------------------------------------------------------------
            | Add to Cart Total 
            |--------------------------------------------------------------------------
            */
            $cartTotal += $itemTotal;

            /*
            |--------------------------------------------------------------------------
            | Check Category Restriction
            |--------------------------------------------------------------------------
            */
            if ($coupon->categories->isNotEmpty()) {
                $categoryIds = $coupon->categories
                    ->pluck('id')
                    ->toArray();

                if (
                    !in_array(
                        $product->category_id,
                        $categoryIds
                    )
                ) {
                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Check Brand Restriction
            |--------------------------------------------------------------------------
            */
            if ($coupon->brands->isNotEmpty()) {
                $brandIds = $coupon->brands
                    ->pluck('id')
                    ->toArray();

                if (
                    !in_array(
                        $product->brand_id,
                        $brandIds
                    )
                ) {
                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Add Eligible Product Amount
            |--------------------------------------------------------------------------
            */
            $eligibleAmount += $itemTotal;
        }

        /*
        |--------------------------------------------------------------------------
        | Check Minimum Cart Value 
        |--------------------------------------------------------------------------
        */
        if (
            $coupon->min_cart_value != null &&
            $cartTotal < $coupon->min_cart_value
        ) {
            throw new \Exception(
                'Minimum cart value of ₹' .
                number_format(
                    $coupon->min_cart_value,
                    2
                ) .
                ' is required to use this coupon.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Check Eligible Products 
        |--------------------------------------------------------------------------
        */
        if ($eligibleAmount <= 0) {
            throw new \Exception(
                'This coupon is not applicable to the products in your cart.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Discount
        |--------------------------------------------------------------------------
        */
        if ($coupon->type === 'percentage') {
            $discount = ($eligibleAmount * $coupon->value) / 100;
        } else {
            $discount = $coupon->value;
        }

        /*
        |--------------------------------------------------------------------------
        | Discount Cannot Be Greater Than Eligible Amount
        |--------------------------------------------------------------------------
        */
        $discount = min(
            $discount,
            $eligibleAmount
        );

        $discount = round($discount, 2);

        /*
        |--------------------------------------------------------------------------
        | Store Coupon in Session
        |--------------------------------------------------------------------------
        */
        session()->put('cart_coupon', [
            'coupon_id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $discount,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Return Response 
        |--------------------------------------------------------------------------
        */
        return [
            'status' => true,
            'message' => 'Coupon applied successfully.',
            'coupon_code' => $coupon->code,
            'discount' => $discount,
            'grand_total' => max(
                $cartTotal - $discount,
                0
            ),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Coupon
    |--------------------------------------------------------------------------
    */
    public function removeCoupon()
    {
        session()->forget('cart_coupon');

        return [
            'status' => true,
            'message' => 'Coupon removed successfully.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Get Applied Coupon
    |--------------------------------------------------------------------------
    */
    public function getAppliedCoupon()
    {
        return session('cart_coupon');
    }
}
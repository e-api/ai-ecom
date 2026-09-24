<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\CartService;
use App\Services\Frontend\CouponService;
use App\Services\Frontend\ShippingChargeService;
use App\Models\DeliveryAddress;

class CheckoutController extends Controller
{
    protected $shippingChargeService;
    public function __construct(
        ShippingChargeService $shippingChargeService
    ) {
        $this->shippingChargeService =
            $shippingChargeService;
    }

    public function index(CartService $cartService, CouponService $couponService)
    {
        $cartItems = $cartService->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum('line_total');

        $coupon = $couponService->getAppliedCoupon();
        $couponDiscount = $coupon['discount'] ?? 0;

        $grandTotal = max($subtotal - $couponDiscount, 0);
        $shippingAmount = $this->shippingChargeService->getShippingCharge($subtotal);
        $grandTotal = $grandTotal + $shippingAmount;

        $deliveryAddresses = DeliveryAddress::where(
            'user_id',
            auth()->id()
        )
        ->orderByDesc('is_default')
        ->latest()
        ->get();

        return view(
            'frontend.checkout.index',
            compact(
                'cartItems',
                'subtotal',
                'coupon',
                'couponDiscount',
                'grandTotal',
                'deliveryAddresses',
                'shippingAmount'
            )
        );
    }
}

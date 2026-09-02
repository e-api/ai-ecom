<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AddToCartRequest;
use App\Services\Frontend\CartService;
use App\Services\Frontend\CouponService;

class CartController extends Controller
{
    //
    protected $cartService;
    protected $couponService;

    public function __construct(
        CartService $cartService,
        CouponService $couponService
    ) {
        $this->cartService = $cartService;
        $this->couponService = $couponService;
    }

    /*
    |
    | Add To Cart
    |
    */

    public function add(AddToCartRequest $request)
    {
        try {
            $this->cartService->add(
                $request->validated()
            );
            return response()->json([
                'status' => true,
                'message' => 'Product added to cart successfully.',
                'cartCount' => $this->cartService->getCartCount(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /*
    | Cart Page
    */
    public function index()
    {
        $cartItems = $this->cartService
            ->getCartItems();

        $cartTotal = $this->cartService
            ->getCartTotal();

        $cartCount = $this->cartService
            ->getCartCount();

        /*
        | Get Applied Coupon
        */
        $coupon = $this->couponService
            ->getAppliedCoupon();

        $couponDiscount = $coupon['discount'] ?? 0;

        $grandTotal = max(
            $cartTotal - $couponDiscount,
            0
        );

        return view(
            'frontend.cart.index',
            compact(
                'cartItems',
                'cartTotal',
                'cartCount',
                'coupon',
                'couponDiscount',
                'grandTotal'
            )
        );
    }

    public function count()
    {
        return response()->json([
            'count' => $this->cartService->getCartCount(),
        ]);
    }

    public function update(Request $request)
    {
        return response()->json(
            $this->cartService->updateCartItem($request->all())
        );
    }

    public function delete(Request $request)
    {
        return response()->json(
            $this->cartService->deleteCartItem($request->all())
        );
    }
}

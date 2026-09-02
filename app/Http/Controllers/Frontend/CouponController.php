<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(
        CouponService $couponService
    ) {
        $this->couponService = $couponService;
    }

    /*
    |--------------------------------------------------------------------------
    | Apply Coupon
    |--------------------------------------------------------------------------
    */
    public function applyCoupon(
        Request $request
    ) {
        try {
            $result = $this->couponService
                ->applyCoupon(
                    $request->code
                );
            return response()->json(
                $result
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Coupon
    |--------------------------------------------------------------------------
    */
    public function removeCoupon()
    {
        try {
            $result = $this->couponService
                ->removeCoupon();

            return response()->json(
                $result
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
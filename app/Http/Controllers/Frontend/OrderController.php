<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Frontend\ShippingChargeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    //
    protected $shippingChargeService;

    public function __construct(
        ShippingChargeService $shippingChargeService
    ) {
        $this->shippingChargeService =
            $shippingChargeService;
    }

    public function store(Request $request)
    {
        // Validate the payment methods
        $validated = $request->validate([
            'payment_method' => [
                'required',
                'in:cod',
            ],
        ]);

        // Get the user cart items
        $cartItems = CartItem::with([
            'product',
            'product.images'
        ])
        ->where(
            'user_id',
            auth()->id()
        )
        ->get();
        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Your cart is empty.'
                );
        }

        // Get the default delivery address
        $deliveryAddress = DeliveryAddress::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'is_default',
            true
        )
        ->first();
        if (!$deliveryAddress) {
            return redirect()
                ->route('checkout.index')
                ->with(
                    'error',
                    'Please add and select a default delivery address.'
                );
        }

        // Calculate the order subtotal
        $subtotal = $cartItems->sum(
            function ($item) {
                $price =
                    $item->product->sale_price
                    ??
                    $item->product->price;
                return
                    $price
                    *
                    $item->quantity;
            }
        );

        // Calculate coupon discount and shipping charge
        $coupon =
            session('cart_coupon');
        $couponDiscount =
            $coupon['discount']
            ??
            0;
        $shippingCharge =
            $this->shippingChargeService
            ->getShippingCharge($subtotal);
        $grandTotal =
            max(
                $subtotal
                -
                $couponDiscount,
                0
            );
        $grandTotal =
            $grandTotal
            +
            $shippingCharge;


        // Create the order using a database transaction
        $order = DB::transaction(function () use (
            $validated,
            $deliveryAddress,
            $cartItems,
            $subtotal,
            $couponDiscount,
            $shippingCharge,
            $grandTotal
        ) {
            $order = Order::create([
                'order_number' =>
                    'ORD-' .
                    strtoupper(
                        Str::random(10)
                    ),
                'user_id' =>
                    auth()->id(),
                'delivery_name' =>
                    $deliveryAddress->name,
                'delivery_phone' =>
                    $deliveryAddress->phone,
                'delivery_address' =>
                    $deliveryAddress->address,
                'delivery_city' =>
                    $deliveryAddress->city,
                'delivery_state' =>
                    $deliveryAddress->state,
                'delivery_country' =>
                    $deliveryAddress->country,
                'delivery_postal_code' =>
                    $deliveryAddress->postal_code,
                'subtotal' =>
                    $subtotal,
                'coupon_discount' =>
                    $couponDiscount,
                'shipping_charge' =>
                    $shippingCharge,
                'grand_total' =>
                    $grandTotal,
                'payment_method' =>
                    $validated['payment_method'],
                'payment_status' =>
                    'pending',
                'order_status' =>
                    'pending',
            ]);

            // Create the order items
            foreach ($cartItems as $cartItem) {
                $price =
                    $cartItem->product->sale_price
                    ??
                    $cartItem->product->price;
                OrderItem::create([
                    'order_id' =>
                        $order->id,
                    'product_id' =>
                        $cartItem->product_id,
                    'product_name' =>
                        $cartItem->product->name,
                    'quantity' =>
                        $cartItem->quantity,
                    'price' =>
                        $price,
                    'subtotal' =>
                        $price *
                        $cartItem->quantity,
                ]);
            }

            // Clear the cart and complete the transaction
            CartItem::where(
                'user_id',
                auth()->id()
            )
            ->delete();
            session()->forget('cart_coupon');

            return $order;
        });

        return redirect()
        ->route(
            'orders.success',
            $order
        );
    }

    public function success(Order $order)
    {
        $order = auth()->user()
            ->orders()
            ->findOrFail(
                $order->id
            );
        return view(
            'frontend.orders.success',
            compact('order')
        );
    }
}

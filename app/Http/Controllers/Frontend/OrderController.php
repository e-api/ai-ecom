<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\OrderPaidMail;
use App\Models\CartItem;
use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Frontend\PayPalService;
use App\Services\Frontend\ShippingChargeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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

    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->latest()
            ->get();
        return view(
            'frontend.orders.index',
            compact('orders')
        );
    }

    public function show(Order $order)
    {
        $order = auth()->user()
            ->orders()
            ->with('orderItems')
            ->findOrFail(
                $order->id
            );
        return view(
            'frontend.orders.show',
            compact('order')
        );
    }

    public function store(Request $request)
    {
        // Validate the payment methods
        $validated = $request->validate([
            'payment_method' => [
                'required',
                'in:cod,paypal',
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
            // PayPal orders must retain the cart until payment succeeds.
            if ($order->payment_method === 'cod') {
                CartItem::where(
                    'user_id',
                    auth()->id()
                )->delete();
                session()->forget('cart_coupon');
            }

            return $order;
        });

        // Redirect PayPal orders to the PayPal checkout page.
        if ($order->payment_method === 'paypal') {
            return redirect()->route('paypal.checkout', [
                'order' => $order->id,
            ]);
        }
        // Keep our existing COD success redirect.
        return redirect()->route(
            'orders.success',
            $order
        );
    }

    public function success(Order $order)
    {
        $order = auth()->user()
            ->orders()
            ->with('orderItems')
            ->findOrFail($order->id);
        // PayPal orders must be paid before showing success.
        if (
            $order->payment_method === 'paypal'
            && $order->payment_status !== 'paid'
        ) {
            return redirect()
                ->route('paypal.checkout', $order)
                ->with(
                    'error',
                    'Please complete your PayPal payment first.'
                );
        }
        
        return view(
            'frontend.orders.success',
            compact('order')
        );
    }

    public function invoice(Order $order)
    {
        $order = auth()->user()
            ->orders()
            ->with('orderItems')
            ->findOrFail($order->id);
        return view(
            'frontend.orders.invoice',
            compact('order')
        );
    }

    public function paypalCheckout(Order $order)
    {
        abort_unless(
            (int) $order->user_id === (int) auth()->id(),
            403
        );

        abort_unless(
            $order->payment_method === 'paypal'
            && $order->payment_status === 'pending',
            403
        );

        $rate = (float) config('paypal.usd_to_khr_rate');

        abort_if(
            $rate <= 0,
            500,
            'Invalid PayPal demo conversion rate.'
        );

        $usdAmount = round(
            (float) $order->grand_total / $rate,
            2
        );

        abort_if(
            $usdAmount <= 0,
            400,
            'Invalid PayPal order amount.'
        );

        return view('frontend.checkout.paypal', [
            'order' => $order,
            'usdAmount' => number_format($usdAmount, 2, '.', ''),
            'currency' => config('paypal.currency', 'USD'),
        ]);
    }

    public function createPaypalOrder(
        Order $order,
        PayPalService $paypalService
    ) {
        abort_unless(
            (int) $order->user_id === (int) auth()->id(),
            403
        );

        abort_unless(
            $order->payment_method === 'paypal'
            && $order->payment_status === 'pending',
            403
        );

        $rate = (float) config('paypal.usd_to_khr_rate');

        abort_if(
            $rate <= 0,
            500,
            'Invalid PayPal demo conversion rate.'
        );

        // Calculate the amount on our server.
        $usdAmount = round(
            (float) $order->grand_total / $rate,
            2
        );

        abort_if(
            $usdAmount <= 0,
            400,
            'Invalid PayPal order amount.'
        );

        // Reuse the existing PayPal order only while PayPal still allows it
        // to be paid. An APPROVED order cannot be approved a second time, so
        // a failed capture must start a fresh PayPal order.
        if ($order->paypal_order_id) {
            try {
                $existingOrder = $paypalService->getOrder(
                    $order->paypal_order_id
                );
                $existingStatus = data_get($existingOrder, 'status');

                if ($existingStatus === 'CREATED') {
                    return response()->json([
                        'id' => $order->paypal_order_id,
                    ]);
                }

                if ($existingStatus === 'COMPLETED') {
                    // Funds were already captured; never charge the buyer twice.
                    return response()->json([
                        'message' => 'This payment was already completed. Please contact support if your order is still pending.',
                    ], 422);
                }
            } catch (\Throwable $e) {
                Log::warning(
                    'Unable to check the existing PayPal order status.',
                    [
                        'order_id' => $order->id,
                        'paypal_order_id' => $order->paypal_order_id,
                        'error' => $e->getMessage(),
                    ]
                );
            }
        }

        try {
            $paypalOrder = $paypalService->createOrder(
                $usdAmount,
                (string) $order->id
            );
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error(
                'PayPal create order request failed.',
                [
                    'order_id' => $order->id,
                    'status' => $e->response->status(),
                    'response' => $e->response->json(),
                ]
            );

            return response()->json([
                'message' => 'PayPal could not start the payment. Please try again.',
            ], 422);
        }

        $order->paypal_order_id = $paypalOrder['id'];
        $order->save();

        return response()->json([
            'id' => $paypalOrder['id'],
        ]);
    }

    public function capturePaypalOrder(
        Order $order,
        PayPalService $paypalService
    ) {
        // Verify that this order belongs to the logged-in customer.
        abort_unless(
            (int) $order->user_id === (int) auth()->id(),
            403
        );

        // Prevent capturing an already paid order again.
        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => true,
                'redirect' => route('orders.success', $order),
            ]);
        }

        // Only pending PayPal orders can be captured.
        abort_unless(
            $order->payment_method === 'paypal'
            && $order->payment_status === 'pending'
            && !empty($order->paypal_order_id),
            403
        );

        // Calculate the expected USD amount on our server.
        $rate = (float) config('paypal.usd_to_khr_rate');

        abort_if(
            $rate <= 0,
            500,
            'Invalid PayPal demo conversion rate.'
        );

        $expectedAmount = number_format(
            round((float) $order->grand_total / $rate, 2),
            2,
            '.',
            ''
        );

        $expectedCurrency = config('paypal.currency', 'USD');

        // Capture the approved PayPal order.
        try {
            $captureResponse = $paypalService->captureOrder(
                $order->paypal_order_id
            );
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error(
                'PayPal capture request failed.',
                [
                    'order_id' => $order->id,
                    'paypal_order_id' => $order->paypal_order_id,
                    'status' => $e->response->status(),
                    'response' => $e->response->json(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'PayPal could not complete the payment. Please try again.',
            ], 422);
        }

        // Get the captured payment details.
        $capture = data_get(
            $captureResponse,
            'purchase_units.0.payments.captures.0'
        );

        $capturedAmount = data_get(
            $capture,
            'amount.value'
        );

        $capturedCurrency = data_get(
            $capture,
            'amount.currency_code'
        );

        $customId = data_get(
            $captureResponse,
            'purchase_units.0.custom_id'
        );

        // Verify the PayPal response.
        if (
            data_get($captureResponse, 'status') !== 'COMPLETED'
            || data_get($capture, 'status') !== 'COMPLETED'
            || !$capture
            || $customId !== (string) $order->id
            || $capturedCurrency !== $expectedCurrency
            || number_format(
                (float) $capturedAmount,
                2,
                '.',
                ''
            ) !== $expectedAmount
        ) {
            return response()->json([
                'success' => false,
                'message' => 'PayPal payment verification failed.',
            ], 422);
        }

        // Update our order only after successful verification.
        $shouldSendEmail = DB::transaction(function () use ($order) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Avoid duplicate order updates and emails.
            if ($lockedOrder->payment_status === 'paid') {
                return false;
            }

            $lockedOrder->payment_status = 'paid';
            $lockedOrder->order_status = 'processing';

            $lockedOrder->save();

            return true;
        });

        // Refresh our order and load its related information.
        $order->refresh()->load([
            'user',
            'orderItems',
        ]);

        // Clear the cart only after successful payment.
        CartItem::where(
            'user_id',
            auth()->id()
        )->delete();

        session()->forget('cart_coupon');

        // Send the payment confirmation email.
        if ($shouldSendEmail && $order->user?->email) {
            try {
                Mail::to($order->user->email)
                    ->send(new OrderPaidMail($order));

            } catch (\Throwable $e) {

                // Log email errors without changing the successful payment.
                Log::error(
                    'PayPal payment confirmation email failed.',
                    [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'redirect' => route('orders.success', $order),
        ]);
    }
}

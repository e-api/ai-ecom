<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Payment Successful</title>
</head>
<body>

<h2>Thank You for Your Order!</h2>

<p>
    Hello {{ $order->delivery_name }},
</p>

<p>
    We have successfully received your payment.
    Your order has been confirmed.
</p>

<h3>Order Details</h3>

<p>
    <strong>Order Number:</strong>
    {{ $order->order_number }}
</p>

<p>
    <strong>Payment Method:</strong>
    PayPal
</p>

<p>
    <strong>Payment Status:</strong>
    Paid
</p>

<p>
    <strong>Order Status:</strong>
    Processing
</p>

<h3>Purchased Products</h3>

<table
    border="1"
    cellpadding="8"
    cellspacing="0"
    width="100%"
>
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
    </thead>

    <tbody>
        @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product_name }}</td>

                <td>
                    ៛{{ number_format($item->price, 2) }}
                </td>

                <td>{{ $item->quantity }}</td>

                <td>
                    ៛{{ number_format($item->subtotal, 2) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<h3>Order Summary</h3>

<p>
    Subtotal:
    ៛{{ number_format($order->subtotal, 2) }}
</p>

<p>
    Coupon Discount:
    ៛{{ number_format($order->coupon_discount, 2) }}
</p>

<p>
    Shipping Charge:
    ៛{{ number_format($order->shipping_charge, 2) }}
</p>

<h3>
    Grand Total:
    ៛{{ number_format($order->grand_total, 2) }}
</h3>

<h3>Delivery Address</h3>

<p>
    {{ $order->delivery_name }}<br>

    {{ $order->delivery_phone }}<br>

    {{ $order->delivery_address }}<br>

    {{ $order->delivery_city }},
    {{ $order->delivery_state }}<br>
    {{ $order->delivery_country }} -
    {{ $order->delivery_postal_code }}
</p>

<p>
    We will notify you when your order is shipped.
</p>

<p>
    Thank you for shopping with us!
</p>

</body>
</html>
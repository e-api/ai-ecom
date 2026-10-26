<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Order Invoice - {{ $order->order_number }}</title>

    <style>
      * {
        box-sizing: border-box;
      }

      body {
        margin: 0;
        padding: 25px;
        background: #f3f4f6;
        color: #222;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
        line-height: 1.5;
      }

      .invoice-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 0 auto 20px;
      }

      .invoice-button {
        display: inline-block;
        padding: 10px 18px;
        border: none;
        border-radius: 4px;
        background: #2563eb;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
      }

      .invoice-button.secondary {
        background: #4b5563;
      }

      .invoice-sheet {
        max-width: 900px;
        margin: 0 auto;
        padding: 40px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
      }

      .invoice-header {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding-bottom: 24px;
        border-bottom: 2px solid #2563eb;
      }

      .invoice-header h1 {
        margin: 0 0 5px;
        color: #2563eb;
        font-size: 30px;
        letter-spacing: 1px;
      }

      .invoice-header p {
        margin: 4px 0;
      }

      .invoice-number {
        text-align: right;
      }

      .invoice-number h2 {
        margin: 0 0 5px;
        font-size: 17px;
        overflow-wrap: anywhere;
      }

      .invoice-section {
        margin-top: 25px;
      }

      .invoice-section h3 {
        margin: 0 0 12px;
        padding-bottom: 7px;
        border-bottom: 1px solid #ddd;
        color: #333;
        font-size: 15px;
        text-transform: uppercase;
      }

      .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
      }

      .details-grid p {
        margin: 4px 0;
        overflow-wrap: anywhere;
      }

      .status {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 3px;
        background: #e5e7eb;
        font-size: 12px;
        font-weight: bold;
      }

      .invoice-table {
        width: 100%;
        margin-top: 12px;
        border-collapse: collapse;
      }

      .invoice-table th,
      .invoice-table td {
        padding: 10px 8px;
        border: 1px solid #ddd;
        text-align: left;
        overflow-wrap: anywhere;
      }

      .invoice-table th {
        background: #f3f4f6;
        font-weight: bold;
      }

      .invoice-table .amount {
        text-align: right;
        white-space: nowrap;
      }

      .invoice-table .quantity {
        text-align: center;
      }

      .invoice-summary {
        width: 100%;
        max-width: 360px;
        margin: 20px 0 0 auto;
        border-collapse: collapse;
      }

      .invoice-summary td {
        padding: 7px 4px;
      }

      .invoice-summary td:last-child {
        text-align: right;
        white-space: nowrap;
      }

      .invoice-summary .grand-total td {
        padding-top: 12px;
        border-top: 2px solid #333;
        font-size: 17px;
        font-weight: bold;
      }

      .invoice-footer {
        margin-top: 35px;
        padding-top: 15px;
        border-top: 1px solid #ddd;
        color: #666;
        text-align: center;
        font-size: 12px;
      }

      @media (max-width: 600px) {
        body {
          padding: 10px;
        }

        .invoice-sheet {
          padding: 20px 14px;
        }

        .invoice-header {
          flex-direction: column;
        }

        .invoice-number {
          text-align: left;
        }

        .details-grid {
          grid-template-columns: 1fr;
          gap: 15px;
        }

        .invoice-table th,
        .invoice-table td {
          padding: 7px 4px;
          font-size: 12px;
        }
      }

      @media print {
        @page {
          size: A4 portrait;
          margin: 12mm;
        }

        body {
          padding: 0;
          background: #fff;
          color: #000;
          font-size: 11px;
          -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
        }

        .invoice-actions {
          display: none !important;
        }

        .invoice-sheet {
          width: 100%;
          max-width: none;
          margin: 0;
          padding: 0;
          border: none;
          border-radius: 0;
          box-shadow: none;
        }

        .invoice-section {
          margin-top: 16px;
        }

        .invoice-header {
          padding-bottom: 15px;
        }

        .invoice-header h1 {
          font-size: 24px;
        }

        .invoice-table {
          page-break-inside: auto;
        }

        .invoice-table thead {
          display: table-header-group;
        }

        .invoice-table tr {
          break-inside: avoid;
          page-break-inside: avoid;
        }

        .invoice-table th,
        .invoice-table td {
          padding: 7px 6px;
        }

        .invoice-summary {
          break-inside: avoid;
          page-break-inside: avoid;
        }

        .invoice-footer {
          margin-top: 20px;
        }
      }
    </style>
  </head>

  <body>
    <div class="invoice-actions">
      <button type="button" class="invoice-button" onclick="window.print()">Print / Save as PDF</button>

      <a href="{{ route('orders.show', $order) }}" class="invoice-button secondary"> Back to Order </a>
    </div>

    <main class="invoice-sheet">
      <header class="invoice-header">
        <div>
          <h1>INVOICE</h1>

          <p>Order purchase details</p>

          <p>
            <strong>Order Date:</strong>
            {{ $order->created_at->format('d M Y, h:i A') }}
          </p>
        </div>

        <div class="invoice-number">
          <h2>{{ $order->order_number }}</h2>

          <p>
            <strong>Status:</strong>
            <span class="status"> {{ ucfirst($order->order_status) }} </span>
          </p>

          <p>
            <strong>Payment:</strong>
            {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}
          </p>

          <p>
            <strong>Payment Status:</strong>
            {{ ucfirst($order->payment_status) }}
          </p>
        </div>
      </header>

      <section class="invoice-section details-grid">
        <div>
          <h3>Customer Details</h3>

          <p>
            <strong> {{ $order->user?->name ?? 'Customer' }} </strong>
          </p>

          <p>{{ $order->user?->email ?? '' }}</p>
        </div>

        <div>
          <h3>Delivery Address</h3>

          <p>
            <strong> {{ $order->delivery_name }} </strong>
          </p>

          <p>{{ $order->delivery_phone }}</p>

          <p>{{ $order->delivery_address }}</p>

          <p>{{ $order->delivery_city }} @if($order->delivery_state) , {{ $order->delivery_state }} @endif</p>

          <p>
            {{ $order->delivery_country }} @if($order->delivery_postal_code) - {{ $order->delivery_postal_code }} @endif
          </p>
        </div>
      </section>

      <section class="invoice-section">
        <h3>Purchased Items</h3>

        <table class="invoice-table">
          <thead>
            <tr>
              <th>Product</th>
              <th class="amount">Price</th>
              <th class="quantity">Qty</th>
              <th class="amount">Subtotal</th>
            </tr>
          </thead>

          <tbody>
            @foreach($order->orderItems as $item)
            <tr>
              <td>{{ $item->product_name }}</td>

              <td class="amount">${{ number_format($item->price, 2) }}</td>

              <td class="quantity">{{ $item->quantity }}</td>

              <td class="amount">${{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>

        <table class="invoice-summary">
          <tbody>
            <tr>
              <td>Subtotal</td>
              <td>${{ number_format($order->subtotal, 2) }}</td>
            </tr>

            <tr>
              <td>Coupon Discount</td>
              <td>- ${{ number_format($order->coupon_discount, 2) }}</td>
            </tr>

            <tr>
              <td>Shipping Charges</td>
              <td>${{ number_format($order->shipping_charge, 2) }}</td>
            </tr>

            <tr class="grand-total">
              <td>Grand Total</td>
              <td>${{ number_format($order->grand_total, 2) }}</td>
            </tr>
          </tbody>
        </table>
      </section>

      <footer class="invoice-footer">Thank you for your order!</footer>
    </main>
  </body>
</html>
@extends('frontend.layouts.app')
@section('title', 'Order Details')
@section('content')
<div class="col-span-full">
  <section class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb-card px-4 py-3 text-sm" aria-label="Breadcrumb">
      <ol class="flex flex-wrap items-center gap-2 text-gray-600">
        <li><a class="breadcrumb-link" href="{{ url('/') }}">Home</a></li>
        <li aria-hidden="true">/</li>
        <li><a class="breadcrumb-link" href="{{ route('orders.index') }}">My Orders</a></li>
        <li aria-hidden="true">/</li>
        <li class="font-medium text-gray-900" aria-current="page">{{ $order->order_number }}</li>
      </ol>
    </nav>

    <div class="space-y-8">
      <div class="relative bg-white p-4 md:p-8">

        {{-- Heading --}}
        <div class="mb-10 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <p class="text-xs font-medium uppercase tracking-[0.15em] text-gray-400">Order details</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">{{ $order->order_number }}</h1>
            <p class="mt-1 text-sm text-gray-500">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
          </div>
          <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-gray-900">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Orders
          </a>
        </div>

        {{-- Order info + Delivery address --}}
        <div class="grid gap-10 md:grid-cols-2 md:gap-12">
          <section>
            <h2 class="mb-4 text-xs font-medium uppercase tracking-[0.15em] text-gray-400">Order</h2>
            <dl class="divide-y divide-gray-100 text-sm">
              <div class="flex items-start justify-between gap-4 py-3">
                <dt class="text-gray-500">Order number</dt>
                <dd class="font-medium text-gray-900 sm:text-right">{{ $order->order_number }}</dd>
              </div>
              <div class="flex items-start justify-between gap-4 py-3">
                <dt class="text-gray-500">Order date</dt>
                <dd class="font-medium text-gray-900 sm:text-right">{{ $order->created_at->format('d M Y, h:i A') }}</dd>
              </div>
              <div class="flex items-start justify-between gap-4 py-3">
                <dt class="text-gray-500">Order status</dt>
                <dd class="sm:text-right">
                  @php
                    $statusColors = [
                      'pending' => 'bg-amber-50 text-amber-700',
                      'processing' => 'bg-blue-50 text-blue-700',
                      'shipped' => 'bg-indigo-50 text-indigo-700',
                      'delivered' => 'bg-green-50 text-green-700',
                      'completed' => 'bg-green-50 text-green-700',
                      'cancelled' => 'bg-red-50 text-red-700',
                      'failed' => 'bg-red-50 text-red-700',
                    ];
                    $statusClass = $statusColors[$order->order_status] ?? 'bg-gray-50 text-gray-700';
                  @endphp
                  <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                    {{ ucfirst($order->order_status) }}
                  </span>
                </dd>
              </div>
              <div class="flex items-start justify-between gap-4 py-3">
                <dt class="text-gray-500">Payment method</dt>
                <dd class="font-medium text-gray-900 sm:text-right">
                  {{ strtoupper($order->payment_method) === 'COD' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}
                </dd>
              </div>
              <div class="flex items-start justify-between gap-4 py-3">
                <dt class="text-gray-500">Payment status</dt>
                <dd class="font-medium text-gray-900 sm:text-right">{{ ucfirst($order->payment_status) }}</dd>
              </div>
            </dl>
          </section>

          <section>
            <h2 class="mb-4 text-xs font-medium uppercase tracking-[0.15em] text-gray-400">Delivery address</h2>
            <dl class="divide-y divide-gray-100 text-sm">
              <div class="flex items-start justify-between gap-4 py-3">
                <dt class="text-gray-500">Full name</dt>
                <dd class="font-medium text-gray-900 sm:text-right">{{ $order->delivery_name }}</dd>
              </div>
              <div class="flex items-start justify-between gap-4 py-3">
                <dt class="text-gray-500">Phone number</dt>
                <dd class="font-medium text-gray-900 sm:text-right">{{ $order->delivery_phone }}</dd>
              </div>
              <div class="flex items-start justify-between gap-4 py-3">
                <dt class="text-gray-500">Address</dt>
                <dd class="font-medium text-gray-900 sm:text-right">{{ $order->delivery_address }}</dd>
              </div>
              <div class="flex items-start justify-between gap-4 py-3">
                <dt class="text-gray-500">City</dt>
                <dd class="font-medium text-gray-900 sm:text-right">{{ $order->delivery_city }}</dd>
              </div>
              <div class="flex items-start justify-between gap-4 py-3">
                <dt class="text-gray-500">State</dt>
                <dd class="font-medium text-gray-900 sm:text-right">{{ $order->delivery_state }}</dd>
              </div>
              <div class="flex items-start justify-between gap-4 py-3">
                <dt class="text-gray-500">Postal code</dt>
                <dd class="font-medium text-gray-900 sm:text-right">{{ $order->delivery_postal_code }}</dd>
              </div>
            </dl>
          </section>
        </div>

        {{-- Order items --}}
        <section class="mt-12">
          <h2 class="mb-4 text-xs font-medium uppercase tracking-[0.15em] text-gray-400">Items</h2>
          <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] border-collapse text-sm">
              <thead>
                <tr class="text-xs uppercase tracking-wider text-gray-400">
                  <th class="py-2 pr-4 text-left font-medium">Product</th>
                  <th class="px-4 py-2 text-right font-medium">Price</th>
                  <th class="px-4 py-2 text-center font-medium">Quantity</th>
                  <th class="py-2 pl-4 text-right font-medium">Subtotal</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @foreach($order->orderItems as $item)
                  <tr>
                    <td class="py-4 pr-4">
                      @if($item->product)
                        <a href="{{ url('product/' . $item->product->slug) }}" class="font-medium text-gray-900 transition hover:text-gray-600">{{ $item->product_name }}</a>
                      @else
                        <span class="font-medium text-gray-900">{{ $item->product_name }}</span>
                      @endif
                    </td>
                    <td class="px-4 py-4 text-right text-gray-700">${{ number_format($item->price, 2) }}</td>
                    <td class="px-4 py-4 text-center text-gray-700">{{ $item->quantity }}</td>
                    <td class="py-4 pl-4 text-right font-medium text-gray-900">${{ number_format($item->subtotal, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </section>

        {{-- Totals --}}
        <div class="mt-12 flex justify-end">
          <dl class="w-full max-w-xs space-y-3 border-t border-gray-200 pt-6 text-sm">
            <div class="flex justify-between gap-4">
              <dt class="text-gray-500">Subtotal</dt>
              <dd class="font-medium text-gray-900">${{ number_format($order->subtotal, 2) }}</dd>
            </div>
            <div class="flex justify-between gap-4">
              <dt class="text-gray-500">Coupon discount</dt>
              <dd class="font-medium text-gray-900">-${{ number_format($order->coupon_discount, 2) }}</dd>
            </div>
            <div class="flex justify-between gap-4">
              <dt class="text-gray-500">Shipping charges</dt>
              <dd class="font-medium text-gray-900">${{ number_format($order->shipping_charge, 2) }}</dd>
            </div>
            <div class="flex justify-between gap-4 border-t border-gray-200 pt-4">
              <dt class="font-medium text-gray-900">Grand total</dt>
              <dd class="font-semibold text-gray-900">${{ number_format($order->grand_total, 2) }}</dd>
            </div>
          </dl>
        </div>

        {{-- Actions --}}
        <div class="mt-12 flex justify-center border-t border-gray-100 pt-8">
          <a href="{{ url('/') }}" class="inline-flex items-center gap-2 rounded-full border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4"/></svg>
            Continue shopping
          </a>
        </div>

      </div>
    </div>
  </section>
</div>
@endsection
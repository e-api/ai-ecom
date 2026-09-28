@extends('frontend.layouts.app')
@section('title', 'Order Placed Successfully')
@section('content')
<div class="col-span-full">
  <section class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb-card px-4 py-3 text-sm" aria-label="Breadcrumb">
      <ol class="flex flex-wrap items-center gap-2 text-gray-600">
        <li><a class="breadcrumb-link" href="{{ url('/') }}">Home</a></li>
        <li aria-hidden="true">/</li>
        <li><a class="breadcrumb-link" href="{{ route('cart.index') }}">Cart</a></li>
        <li aria-hidden="true">/</li>
        <li><a class="breadcrumb-link" href="{{ route('checkout.index') }}">Checkout</a></li>
        <li aria-hidden="true">/</li>
        <li class="font-bold text-gray-900" aria-current="page">Order Placed Successfully</li>
      </ol>
    </nav>

    <div class="space-y-8">
      <div class="relative rounded-lg border border-gray-200 bg-white p-4 md:p-6">

        {{-- Success Header --}}
        <div class="flex flex-col items-center gap-4 text-center">
          <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
            <svg class="h-9 w-9 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
          </div>
          <div>
            <p class="text-sm font-bold uppercase tracking-wider text-green-600">Order Placed Successfully</p>
            <h1 class="mt-1 text-3xl font-black">Thank You!</h1>
            <p class="mt-2 text-sm text-gray-600">Your order has been placed successfully. We will contact you shortly to confirm your delivery.</p>
          </div>
        </div>

        <hr class="my-6 border-gray-200"/>

        {{-- Order Details --}}
        <div class="mx-auto max-w-2xl rounded-lg border border-gray-200 bg-gray-50 p-5">
          <h2 class="mb-4 text-lg font-black uppercase tracking-wide">Order Summary</h2>

          <dl class="space-y-3 text-sm">
            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between sm:gap-4">
              <dt class="text-gray-600">Order Number</dt>
              <dd class="font-black text-gray-900 sm:text-right">{{ $order->order_number }}</dd>
            </div>

            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between sm:gap-4">
              <dt class="text-gray-600">Order Amount</dt>
              <dd class="font-black text-red-600 sm:text-right">${{ number_format($order->grand_total, 2) }}</dd>
            </div>

            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between sm:gap-4">
              <dt class="text-gray-600">Payment Method</dt>
              <dd class="font-black text-gray-900 sm:text-right">
                {{ strtoupper($order->payment_method) === 'COD' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}
              </dd>
            </div>

            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between sm:gap-4">
              <dt class="text-gray-600">Payment Status</dt>
              <dd class="font-black text-gray-900 sm:text-right">{{ ucfirst($order->payment_status) }}</dd>
            </div>

            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between sm:gap-4">
              <dt class="text-gray-600">Order Date</dt>
              <dd class="font-black text-gray-900 sm:text-right">{{ $order->created_at->format('d M Y, h:i A') }}</dd>
            </div>
          </dl>
        </div>

        {{-- Actions --}}
        <div class="mt-6 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
          <a href="{{ url('/') }}" class="btn-go inline-flex w-full items-center justify-center gap-2 rounded-md px-8 py-3 text-base font-black sm:w-auto">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20a1 1 0 11-2 0 1 1 0 012 0zm10 0a1 1 0 11-2 0 1 1 0 012 0z"/>
            </svg>
            Continue Shopping
          </a>
          <a href="{{ route('cart.index') }}" class="inline-flex w-full items-center justify-center gap-1 rounded-md border border-gray-300 bg-white px-5 py-3 text-sm font-bold text-gray-700 transition hover:bg-gray-50 sm:w-auto">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            View Cart
          </a>
        </div>

      </div>
    </div>
  </section>
</div>
@endsection

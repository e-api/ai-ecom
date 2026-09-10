@extends('frontend.layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="col-span-full">
  <section class="space-y-6">
    <div class="space-y-8">
      <div class="relative rounded-lg border border-gray-200 bg-white p-4 md:p-6">

        {{-- Page Heading --}}
        <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
          <div>
            <p class="text-sm font-bold uppercase tracking-wider text-red-600">Checkout</p>
            <h1 class="text-3xl font-black">Order Review</h1>
          </div>
          <div class="flex items-center gap-3">
            <p class="text-sm font-semibold text-gray-500">{{ $cartItems->sum('quantity') }} item(s)</p>
            <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-1 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
              Back to Cart
            </a>
          </div>
        </div>

        <hr class="border-gray-200 mb-6"/>

        {{-- ======================================================== --}}
        {{-- Delivery Addresses --}}
        {{-- ======================================================== --}}
        @include('frontend.delivery-addresses.partials.address-section')

        {{-- ======================================================== --}}
        {{-- Order Items --}}
        {{-- ======================================================== --}}
        <div class="mb-6">
          <h2 class="text-lg font-black uppercase tracking-wide mb-3">2. Order Items</h2>

          <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] border-collapse text-left">
              <thead>
                <tr class="border-y bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                  <th class="px-3 py-3">Product</th>
                  <th class="px-3 py-3">Description</th>
                  <th class="px-3 py-3 text-center">Qty</th>
                  <th class="px-3 py-3 text-right">Unit Price</th>
                  <th class="px-3 py-3 text-right">Subtotal</th>
                </tr>
              </thead>
              <tbody class="divide-y">
                @foreach($cartItems as $item)
                  @php
                    $name = $item['name'] ?? 'Product';
                    $price = $item['price'] ?? 0;
                    $quantity = $item['quantity'] ?? 1;
                    $itemSubtotal = $price * $quantity;

                    $imageUrl = !empty($item['image']) && file_exists(Storage::url($item['image']))
                      ? Storage::url($item['image'])
                      : Storage::url($item['image']);
                  @endphp

                  <tr>
                    <td class="px-3 py-4">
                      <div class="h-20 w-16 overflow-hidden rounded-md border border-gray-200 bg-gray-100">
                        <img src="{{ $imageUrl }}" alt="{{ $name }}" class="h-full w-full object-cover">
                      </div>
                    </td>
                    <td class="px-3 py-4 align-top">
                      <p class="font-black text-gray-900">{{ $name }}</p>
                      @if(!empty($item['variant_size']))
                        <p class="mt-0.5 text-sm text-gray-500">Size: {{ $item['variant_size'] }}</p>
                      @endif
                      @if(!empty($item['sku']))
                        <p class="text-xs text-gray-400">SKU: {{ $item['sku'] }}</p>
                      @endif
                    </td>
                    <td class="px-3 py-4 text-center align-top font-bold text-gray-700">{{ $quantity }}</td>
                    <td class="px-3 py-4 text-right align-top font-bold">${{ number_format($price, 2) }}</td>
                    <td class="px-3 py-4 text-right align-top font-black">${{ number_format($itemSubtotal, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <hr class="border-gray-200 mb-6"/>

        {{-- ======================================================== --}}
        {{-- Payment Method + Order Summary --}}
        {{-- ======================================================== --}}
        <div class="grid gap-5 lg:grid-cols-2">

          {{-- Payment Method --}}
          <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
            <h2 class="mb-4 text-lg font-black uppercase tracking-wide">3. Payment Method</h2>

            <label data-payment-option class="mb-3 flex cursor-pointer items-start gap-3 rounded-md border-2 border-green-500 bg-white p-4 transition hover:border-green-600">
              <input type="radio" name="payment_method" value="cod" checked class="mt-0.5 h-4 w-4 text-green-600 focus:ring-green-500">
              <div>
                <p class="font-black text-gray-900">Cash on Delivery (COD)</p>
                <p class="mt-0.5 text-sm text-gray-500">Pay with cash when your order is delivered.</p>
              </div>
            </label>

            <label data-payment-option class="flex cursor-pointer items-start gap-3 rounded-md border border-gray-200 bg-white p-4 transition hover:border-gray-300">
              <input type="radio" name="payment_method" value="paypal" class="mt-0.5 h-4 w-4 text-blue-600 focus:ring-blue-500">
              <div>
                <p class="font-black text-gray-900">PayPal</p>
                <p class="mt-0.5 text-sm text-gray-500">You will be redirected to PayPal to complete payment securely.</p>
              </div>
            </label>
          </div>

          {{-- Payment Method Scripts --}}
          @push('scripts')
          <script>
            $(document).ready(function () {
              function syncPaymentSelection() {
                $('input[name="payment_method"]').each(function () {
                  var $option = $(this).closest('[data-payment-option]');
                  if ($(this).is(':checked')) {
                    $option.removeClass('border-gray-200 hover:border-gray-300 border').addClass('border-2 border-green-500');
                  } else {
                    $option.removeClass('border-2 border-green-500').addClass('border border-gray-200 hover:border-gray-300');
                  }
                });
              }

              $('input[name="payment_method"]').on('change', syncPaymentSelection);
              syncPaymentSelection();
            });
          </script>
          @endpush

          {{-- Order Summary --}}
          <div class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="mb-4 text-lg font-black uppercase tracking-wide">4. Order Summary</h2>

            <div class="space-y-3 text-sm">
              <div class="flex justify-between gap-4">
                <span class="text-gray-600">Subtotal</span>
                <strong>${{ number_format($subtotal, 2) }}</strong>
              </div>

              @if(isset($coupon) && $coupon && isset($couponDiscount) && $couponDiscount > 0)
              <div class="flex justify-between gap-4">
                <span class="text-gray-600">Coupon Discount ({{ $coupon['code'] ?? '' }})</span>
                <strong class="text-green-600">-${{ number_format($couponDiscount, 2) }}</strong>
              </div>
              @endif

              <div class="flex justify-between gap-4">
                <span class="text-gray-600">Shipping</span>
                <span class="text-gray-400">To be calculated</span>
              </div>

              <hr class="border-gray-200">

              <div class="flex justify-between gap-4">
                <span class="text-base font-black">Grand Total</span>
                <strong class="text-lg font-black text-red-600">${{ number_format($grandTotal, 2) }}</strong>
              </div>
            </div>
          </div>
        </div>

        <hr class="border-gray-200 my-6"/>

        {{-- ======================================================== --}}
        {{-- Checkout Actions --}}
        {{-- ======================================================== --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between">
          <a href="{{ route('cart.index') }}" class="inline-flex items-center justify-center gap-1 rounded-md border border-gray-300 bg-white px-5 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Cart
          </a>

          <button type="button" id="placeOrderBtn" class="inline-flex items-center justify-center gap-2 rounded-md bg-green-600 px-8 py-3 text-base font-black text-white hover:bg-green-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Place Order
          </button>
        </div>

      </div>
    </div>
  </section>
</div>
@endsection

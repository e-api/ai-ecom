@extends('frontend.layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="col-span-full">
  <section class="space-y-6">
    <div class="space-y-8">
      <div class="relative bg-white p-4 md:p-8">

        {{-- Heading --}}
        <div class="mb-10 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <p class="text-xs font-medium uppercase tracking-[0.15em] text-gray-400">Checkout</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Order Review</h1>
          </div>
          <div class="flex items-center gap-4">
            <p class="text-sm text-gray-500">{{ $cartItems->sum('quantity') }} item(s)</p>
            <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-gray-900">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
              Back to Cart
            </a>
          </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
          <div class="mb-8 flex items-center gap-2 border border-green-100 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
            <svg class="h-4 w-4 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
          </div>
        @endif

        {{-- Delivery Addresses --}}
        @include('frontend.delivery-addresses.partials.address-section')

        {{-- Order Items --}}
        <div class="mb-10">
          <h2 class="mb-5 text-xs font-medium uppercase tracking-[0.15em] text-gray-400">2. Order Items</h2>

          <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] border-collapse text-sm">
              <thead>
                <tr class="text-xs uppercase tracking-wider text-gray-400">
                  <th class="py-2 pr-4 text-left font-medium">Product</th>
                  <th class="px-4 py-2 text-left font-medium">Description</th>
                  <th class="px-4 py-2 text-center font-medium">Qty</th>
                  <th class="px-4 py-2 text-right font-medium">Unit Price</th>
                  <th class="py-2 pl-4 text-right font-medium">Subtotal</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
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
                    <td class="py-4 pr-4">
                      <div class="h-20 w-16 overflow-hidden rounded-md bg-gray-100">
                        <img src="{{ $imageUrl }}" alt="{{ $name }}" class="h-full w-full object-cover">
                      </div>
                    </td>
                    <td class="px-4 py-4 align-top">
                      <p class="font-medium text-gray-900">{{ $name }}</p>
                      @if(!empty($item['variant_size']))
                        <p class="mt-0.5 text-sm text-gray-500">Size: {{ $item['variant_size'] }}</p>
                      @endif
                      @if(!empty($item['sku']))
                        <p class="text-xs text-gray-400">SKU: {{ $item['sku'] }}</p>
                      @endif
                    </td>
                    <td class="px-4 py-4 text-center align-top text-gray-700">{{ $quantity }}</td>
                    <td class="px-4 py-4 text-right align-top text-gray-700">${{ number_format($price, 2) }}</td>
                    <td class="py-4 pl-4 text-right align-top font-medium text-gray-900">${{ number_format($itemSubtotal, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="my-10 border-t border-gray-100"></div>

        {{-- Payment Method + Order Summary --}}
        <div class="grid gap-10 lg:grid-cols-2 lg:gap-16">

          {{-- Payment Method --}}
          <section>
            <h2 class="mb-5 text-xs font-medium uppercase tracking-[0.15em] text-gray-400">3. Payment Method</h2>

            <div class="space-y-4">
              <div data-payment-card data-payment-method="cod" tabindex="0" role="button" aria-pressed="true"
                class="flex cursor-pointer items-start gap-3 rounded-md border bg-white p-4 transition hover:border-gray-300 border-green-500">
                <span class="inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-600">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </span>
                <div class="min-w-0 flex-1">
                  <div class="flex flex-wrap items-center gap-2">
                    <p class="font-medium text-gray-900">Cash on Delivery</p>
                    <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700">COD</span>
                  </div>
                  <p class="mt-1 text-sm leading-relaxed text-gray-500">Pay in cash when your order is delivered. No advance payment required.</p>
                </div>
                <span data-payment-check class="hidden inline-flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-green-500 text-white">
                  <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </span>
              </div>

              <div data-payment-card data-payment-method="paypal" aria-disabled="true"
                class="flex cursor-not-allowed items-start gap-3 rounded-md border border-gray-200 bg-gray-50 p-4 opacity-70">
                <span class="inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-white text-gray-400">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3C7.03 3 3 6.58 3 11c0 3.31 2.15 6.16 5.25 7.1-.36-1.2-.08-2.94.31-3.72l1.1-4.64s-.28-.56-.28-1.38c0-1.29.75-2.25 1.68-2.25.79 0 1.18.6 1.18 1.31 0 .8-.51 2-.78 3.11-.22.93.47 1.69 1.4 1.69 1.68 0 2.97-1.77 2.97-4.33 0-2.26-1.63-3.85-3.95-3.85-2.69 0-4.27 2.02-4.27 4.1 0 .82.31 1.69.7 2.17.08.09.09.16.06.25l-.27 1.09c-.04.18-.15.22-.34.13-1.27-.59-2.07-2.44-2.07-3.93 0-3.2 2.33-6.14 6.71-6.14 3.52 0 6.26 2.51 6.26 5.86 0 3.5-2.2 6.31-5.27 6.31-1.03 0-2-.54-2.33-1.18l-.63 2.42c-.23.88-.85 1.98-1.26 2.65.95.29 1.95.45 3 .45 4.97 0 9-4.03 9-9s-4.03-9-9-9z"/>
                  </svg>
                </span>
                <div class="min-w-0 flex-1">
                  <div class="flex flex-wrap items-center gap-2">
                    <p class="font-medium text-gray-500">PayPal</p>
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">Coming soon</span>
                  </div>
                  <p class="mt-1 text-sm leading-relaxed text-gray-400">You will be redirected to PayPal to complete your payment securely.</p>
                </div>
              </div>
            </div>

            <input type="hidden" name="payment_method" id="paymentMethodInput" form="placeOrderForm" value="cod">
          </section>

          {{-- Payment Method Scripts --}}
          @push('scripts')
          <script>
            $(document).ready(function () {
              var $cards = $('[data-payment-card]');
              var $input = $('#paymentMethodInput');

              function selectPaymentCard($card) {
                if ($card.data('disabled') || $card.attr('aria-disabled') === 'true') {
                  return;
                }

                var method = $card.data('payment-method');
                $input.val(method);
                $cards.each(function () {
                  var $el = $(this);
                  if ($el.data('payment-method') === method) {
                    $el.removeClass('border-gray-200 hover:border-gray-300').addClass('border-green-500');
                    $el.attr('aria-pressed', 'true');
                    $el.find('[data-payment-check]').removeClass('hidden');
                  } else {
                    $el.removeClass('border-green-500').addClass('border-gray-200 hover:border-gray-300');
                    $el.attr('aria-pressed', 'false');
                    $el.find('[data-payment-check]').addClass('hidden');
                  }
                });
              }

              $cards.on('click', function () {
                selectPaymentCard($(this));
              });

              $cards.on('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                  e.preventDefault();
                  selectPaymentCard($(this));
                }
              });

              selectPaymentCard($cards.filter('[data-payment-method="cod"]'));
            });
          </script>
          @endpush

          {{-- Order Summary --}}
          <section class="h-fit">
            <h2 class="mb-5 text-xs font-medium uppercase tracking-[0.15em] text-gray-400">4. Order Summary</h2>

            <div class="space-y-3 text-sm">
              <div class="flex justify-between gap-4">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-medium text-gray-900">${{ number_format($subtotal, 2) }}</span>
              </div>

              @if(isset($coupon) && $coupon && isset($couponDiscount) && $couponDiscount > 0)
              <div class="flex justify-between gap-4">
                <span class="text-gray-500">Coupon discount ({{ $coupon['code'] ?? '' }})</span>
                <span class="font-medium text-green-600">-${{ number_format($couponDiscount, 2) }}</span>
              </div>
              @endif

              <div class="flex justify-between gap-4">
                <span class="text-gray-500">Shipping</span>
                @if($shippingAmount > 0)
                  <span class="font-medium text-gray-900">${{ number_format($shippingAmount, 2) }}</span>
                @else
                  <span class="font-medium text-green-600">Free</span>
                @endif
              </div>

              <div class="my-5 border-t border-gray-100"></div>

              <div class="flex justify-between gap-4">
                <span class="font-medium text-gray-900">Grand total</span>
                <strong class="font-semibold text-gray-900">${{ number_format($grandTotal, 2) }}</strong>
              </div>
            </div>
          </section>
        </div>

        <div class="my-10 border-t border-gray-100"></div>

        {{-- Checkout Actions --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <a href="{{ route('cart.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-full px-3 py-2 text-sm font-medium text-gray-500 transition hover:text-gray-900">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Cart
          </a>

          <form id="placeOrderForm" method="POST" action="{{ route('orders.store') }}">
            @csrf
            <button type="submit" id="placeOrderBtn" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-gray-900 px-8 py-3 text-sm font-medium text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-60">
              Place Order
            </button>
          </form>
        </div>

      </div>
    </div>
  </section>
</div>
@endsection
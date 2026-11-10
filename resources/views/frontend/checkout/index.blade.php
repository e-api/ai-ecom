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

        {{-- Error Message --}}
        @if(session('error'))
          <div class="mb-8 flex items-center gap-2 border border-red-100 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            <svg class="h-4 w-4 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-2.99L13.73 4.01c-.77-1.33-2.69-1.33-3.46 0L3.34 16.01c-.77 1.32.19 2.99 1.73 2.99z"/></svg>
            {{ session('error') }}
          </div>
        @endif

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

              <div data-payment-card data-payment-method="paypal" tabindex="0" role="button" aria-pressed="false" class="flex cursor-pointer items-start gap-3 rounded-md border border-gray-200 bg-white p-4 transition hover:border-gray-300">
                <span class="inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-paypal" viewBox="0 0 16 16">
                    <path d="M14.06 3.713c.12-1.071-.093-1.832-.702-2.526C12.628.356 11.312 0 9.626 0H4.734a.7.7 0 0 0-.691.59L2.005 13.509a.42.42 0 0 0 .415.486h2.756l-.202 1.28a.628.628 0 0 0 .62.726H8.14c.429 0 .793-.31.862-.731l.025-.13.48-3.043.03-.164.001-.007a.35.35 0 0 1 .348-.297h.38c1.266 0 2.425-.256 3.345-.91q.57-.403.993-1.005a4.94 4.94 0 0 0 .88-2.195c.242-1.246.13-2.356-.57-3.154a2.7 2.7 0 0 0-.76-.59l-.094-.061ZM6.543 8.82a.7.7 0 0 1 .321-.079H8.3c2.82 0 5.027-1.144 5.672-4.456l.003-.016q.326.186.548.438c.546.623.679 1.535.45 2.71-.272 1.397-.866 2.307-1.663 2.874-.802.57-1.842.815-3.043.815h-.38a.87.87 0 0 0-.863.734l-.03.164-.48 3.043-.024.13-.001.004a.35.35 0 0 1-.348.296H5.595a.106.106 0 0 1-.105-.123l.208-1.32z"/>
                  </svg>
                </span>
                <div class="min-w-0 flex-1">
                  <div class="flex flex-wrap items-center gap-2">
                    <p class="font-medium text-gray-900">PayPal</p>
                  </div>
                  <p class="mt-1 text-sm leading-relaxed text-gray-500">You will be redirected to PayPal to complete your payment securely.</p>
                </div>
                <span data-payment-check class="hidden inline-flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-green-500 text-white">
                  <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </span>
              </div>
            </div>
            {{-- Radio inputs to actually submit the value --}}
            <input type="radio" name="payment_method" value="cod" form="placeOrderForm" id="payment_cod" checked class="hidden">
            <input type="radio" name="payment_method" value="paypal" form="placeOrderForm" id="payment_paypal" class="hidden">
          </section>

          {{-- Payment Method Scripts --}}
          @push('scripts')
          <script>
            $(document).ready(function () {
              var $cards = $('[data-payment-card]');

              function selectPaymentCard($card) {
                // Ignore if the card is disabled
                if ($card.attr('aria-disabled') === 'true') {
                  return;
                }

                var method = $card.data('payment-method');
                
                // Check the correct radio button for form submission
                if (method === 'paypal') {
                    $('#payment_paypal').prop('checked', true);
                } else {
                    $('#payment_cod').prop('checked', true);
                }

                // Update the UI styles
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

              // Default select COD on page load
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
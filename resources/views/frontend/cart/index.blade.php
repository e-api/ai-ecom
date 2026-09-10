@extends('frontend.layouts.app')
@section('title', 'Shopping Cart')
@section('content')
@php
  $itemLabel = $cartCount === 1 ? 'item' : 'items';
@endphp
<div class="col-span-full">
  <section class="space-y-6">
    <div class="space-y-8">
      <div class="relative rounded-lg border border-gray-200 bg-white p-4 md:p-6">
        <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
          <div>
            <p class="text-sm font-bold uppercase tracking-wider text-red-600">Cart Review</p>
            <h1 class="text-3xl font-black">Shopping Cart</h1>
          </div>
          <p class="text-sm font-semibold text-gray-500">{{ $cartCount }} {{ $itemLabel }} in your cart</p>
        </div>

        @if($cartItems->isEmpty())
          <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center">
            <h2 class="text-xl font-black text-gray-900">Your cart is empty</h2>
            <p class="mt-2 text-sm text-gray-600">Add a product to your cart and it will show up here.</p>
            <a href="{{ url('/') }}" class="mt-5 inline-flex rounded-md bg-primary px-5 py-3 text-sm font-bold hover:bg-primary-hover">Continue Shopping</a>
          </div>
        @else
          <div data-coupon-discount="{{ $couponDiscount }}" class="overflow-x-auto cart-responsive">
            <table class="w-full min-w-[900px] border-collapse text-left">
              <thead>
                <tr class="border-y bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                  <th class="px-3 py-3">Item</th>
                  <th class="px-3 py-3">Description</th>
                  <th class="px-3 py-3 text-center">Quantity</th>
                  <th class="px-3 py-3 text-right">Price</th>
                  <th class="px-3 py-3 text-right">Discount</th>
                  <th class="px-3 py-3 text-right">Tax</th>
                  <th class="px-3 py-3 text-right">Sub-Total</th>
                  <th class="px-3 py-3 text-right">Delete</th>
                </tr>
              </thead>
              <tbody class="divide-y">
                @foreach($cartItems as $item)
                  @php
                    $imageUrl = $item['image']
                      ? Storage::url($item['image'])
                      : 'https://placehold.co/160x200/e5e7eb/6b7280?text=Product';
                    $metaRows = collect([
                      $item['variant_size'] ? 'Size: '.$item['variant_size'] : null,
                      $item['color'] ? 'Color: '.$item['color'] : null,
                      $item['service_provider'] ? 'Service provider: '.$item['service_provider'] : null,
                      $item['product_grade'] ? 'Grade: '.$item['product_grade'] : null,
                      $item['sku'] ? 'SKU: '.$item['sku'] : null,
                    ])->filter();
                    $itemId = $item['key'];
                  @endphp
                  <tr data-cart-id="{{ $itemId }}">
                    <td class="px-3 py-4">
                      <div class="h-24 w-20 overflow-hidden rounded-md border border-gray-200 bg-gray-100">
                        <img src="{{ $imageUrl }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                      </div>
                    </td>
                    <td class="px-3 py-4 align-top">
                      @if($item['slug'])
                        <a href="{{ url('product/'.$item['slug']) }}" class="font-black text-gray-900 hover:text-primary hover:underline">{{ $item['name'] }}</a>
                      @else
                        <h2 class="font-black text-gray-900">{{ $item['name'] }}</h2>
                      @endif
                      <div class="mt-1 space-y-0.5 text-sm text-gray-600">
                        @foreach($metaRows as $meta)
                          <p>{{ $meta }}</p>
                        @endforeach
                      </div>
                    </td>
                    <td class="px-3 py-4 text-center align-top">
                      <div class="inline-flex items-center border border-gray-300 rounded-md overflow-hidden">
                        <button type="button" class="cartQtyBtn cartQtyMinus w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition border-r border-gray-300 text-lg font-medium select-none"
                          data-id="{{ $itemId }}">−</button>
                        <input type="number" value="{{ $item['quantity'] }}" min="1"
                          class="cartQty w-14 h-9 text-center text-sm font-medium text-gray-900 border-0 focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                          data-id="{{ $itemId }}">
                        <button type="button" class="cartQtyBtn cartQtyPlus w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition border-l border-gray-300 text-lg font-medium select-none"
                          data-id="{{ $itemId }}">+</button>
                      </div>
                    </td>
                    <td class="px-3 py-4 text-right align-top font-bold">${{ number_format($item['price'], 2) }}</td>
                    <td class="px-3 py-4 text-right align-top">$0.00</td>
                    <td class="px-3 py-4 text-right align-top">$0.00</td>
                    <td data-id="{{ $itemId }}" class="px-3 py-4 text-right align-top font-black subTotal">{{ number_format($item['line_total'], 2) }}</td>
                    <td class="px-3 py-4 text-right align-top">
                      <button class="removeCartItem inline-flex items-center justify-center w-9 h-9 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition" data-id="{{ $itemId }}" title="Remove item">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="mt-6 grid gap-5 lg:grid-cols-[1fr_380px]">

            {{-- Voucher Section --}}
            <section class="h-fit rounded-lg border border-gray-200 bg-white p-5">
              <h2 class="flex items-center gap-2 text-lg font-black uppercase tracking-wide">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                Voucher Code
              </h2>
              <p class="mt-1 text-sm text-gray-500">Apply a voucher code to save on your order.</p>

              <div class="mt-4">
                @if($coupon)
                  <div class="flex items-center justify-between gap-3 rounded-md border-2 border-dashed border-green-500 bg-green-50 p-4">
                    <div class="flex items-center gap-3">
                      <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-green-500 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                      </div>
                      <div>
                        <p class="font-black uppercase tracking-wider text-green-800">{{ $coupon['code'] }}</p>
                        <p class="text-xs text-green-600">-${{ number_format($coupon['discount'], 2) }} discount applied</p>
                      </div>
                    </div>
                    <button class="removeCouponBtn inline-flex flex-shrink-0 items-center gap-1 rounded-md border border-red-200 bg-white px-3 py-2 text-sm font-bold text-red-600 hover:bg-red-50 transition" type="button">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                      Remove
                    </button>
                  </div>
                @else
                  <div class="flex flex-col gap-2 sm:flex-row">
                    <div class="relative flex-1">
                      <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                      <input id="couponCode" class="w-full rounded-md border border-gray-300 py-3 pl-10 pr-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none" type="text" placeholder="Enter voucher code">
                    </div>
                    <button id="applyCouponBtn" class="rounded-md bg-gray-900 px-6 py-3 text-sm font-black text-white hover:bg-gray-800 transition" type="button">
                      Apply
                    </button>
                  </div>
                @endif
              </div>
            </section>

            {{-- Order Summary --}}
            <section class="h-fit rounded-lg border border-gray-200 bg-white p-5">
              <h2 class="mb-4 text-lg font-black uppercase tracking-wide">Order Summary</h2>

              <div class="space-y-3 text-sm">
                <div class="flex justify-between gap-4">
                  <span class="text-gray-600">Subtotal</span>
                  <strong>${{ number_format($cartTotal, 2) }}</strong>
                </div>
                <div class="flex justify-between gap-4">
                  <span class="text-gray-600">Coupon Discount</span>
                  <strong id="discountValue" class="{{ $couponDiscount > 0 ? 'text-green-600' : 'text-gray-400' }}">-${{ number_format($couponDiscount, 2) }}</strong>
                </div>
                <div class="flex justify-between gap-4">
                  <span class="text-gray-600">Tax</span>
                  <strong>$0.00</strong>
                </div>
              </div>

              <hr class="border-gray-200 my-4">

              <div class="flex items-center justify-between gap-4">
                <span class="text-base font-black">Grand Total</span>
                <strong class="grandTotalValue text-lg font-black text-red-600">${{ number_format($grandTotal, 2) }}</strong>
              </div>

              <div class="mt-5 space-y-3">
                <a href="{{ route('checkout.index') }}" id="checkoutBtn" class="block w-full rounded-md bg-green-600 px-5 py-4 text-center text-base font-black text-white hover:bg-green-700 transition">
                  Proceed to Checkout
                </a>
                <p class="text-center text-xs text-gray-400">You can select a delivery address at checkout.</p>
              </div>
            </section>
          </div>
        @endif
        <div class="cart-cursor" aria-hidden="true"></div>
      </div>
    </div>
  </section>
</div>
@endsection
@extends('frontend.layouts.app')
@section('title', 'Shopping Cart')
@section('content')
@php
  $itemLabel = $cartCount === 1 ? 'item' : 'items';
@endphp
<div class="col-span-full">
  <section class="space-y-6">
    <div class="space-y-8">
      <div class="relative bg-white p-4 md:p-8">
        <div class="mb-10 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <p class="text-xs font-medium uppercase tracking-[0.15em] text-gray-400">Cart review</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Shopping Cart</h1>
          </div>
          <p class="text-sm text-gray-500">{{ $cartCount }} {{ $itemLabel }} in your cart</p>
        </div>

        @if($cartItems->isEmpty())
          <div class="py-16 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
              <svg class="h-5 w-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4"/><circle cx="9" cy="20" r="1"/><circle cx="20" cy="20" r="1"/></svg>
            </div>
            <h2 class="mt-4 text-lg font-medium text-gray-900">Your cart is empty</h2>
            <p class="mt-1 text-sm text-gray-500">Add a product to your cart and it will show up here.</p>
            <a href="{{ url('/') }}" class="mt-6 inline-flex items-center gap-2 rounded-full border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white">
              Continue shopping
            </a>
          </div>
        @else
          <div data-coupon-discount="{{ $couponDiscount }}" class="overflow-x-auto cart-responsive">
            <table class="w-full min-w-[700px] border-collapse text-sm">
              <thead>
                <tr class="text-xs uppercase tracking-wider text-gray-400">
                  <th class="py-2 pr-4 text-left font-medium">Item</th>
                  <th class="px-4 py-2 text-left font-medium">Description</th>
                  <th class="px-4 py-2 text-center font-medium">Quantity</th>
                  <th class="px-4 py-2 text-right font-medium">Price</th>
                  <th class="py-2 pl-4 text-right font-medium">Sub-Total</th>
                  <th class="py-2 pl-4 text-right font-medium">Delete</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
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
                    <td class="py-4 pr-4">
                      <div class="h-24 w-20 overflow-hidden rounded-md bg-gray-100">
                        <img src="{{ $imageUrl }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                      </div>
                    </td>
                    <td class="px-4 py-4 align-top">
                      @if($item['slug'])
                        <a href="{{ url('product/'.$item['slug']) }}" class="font-medium text-gray-900 transition hover:text-gray-600">{{ $item['name'] }}</a>
                      @else
                        <h2 class="font-medium text-gray-900">{{ $item['name'] }}</h2>
                      @endif
                      <div class="mt-1 space-y-0.5 text-sm text-gray-500">
                        @foreach($metaRows as $meta)
                          <p>{{ $meta }}</p>
                        @endforeach
                      </div>
                    </td>
                    <td class="px-4 py-4 text-center align-top">
                      <div class="inline-flex items-center rounded-md border border-gray-200">
                        <button type="button" class="cartQtyBtn cartQtyMinus flex h-9 w-9 items-center justify-center text-gray-500 transition hover:bg-gray-50 hover:text-gray-900 select-none" data-id="{{ $itemId }}">−</button>
                        <input type="number" value="{{ $item['quantity'] }}" min="1"
                          class="cartQty h-9 w-14 border-0 text-center text-sm font-medium text-gray-900 focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                          data-id="{{ $itemId }}">
                        <button type="button" class="cartQtyBtn cartQtyPlus flex h-9 w-9 items-center justify-center text-gray-500 transition hover:bg-gray-50 hover:text-gray-900 select-none" data-id="{{ $itemId }}">+</button>
                      </div>
                    </td>
                    <td class="px-4 py-4 text-right align-top text-gray-700">${{ number_format($item['price'], 2) }}</td>
                    <td data-id="{{ $itemId }}" class="subTotal px-4 py-4 text-right align-top font-medium text-gray-900">{{ number_format($item['line_total'], 2) }}</td>
                    <td class="py-4 pl-4 text-right align-top">
                      <button class="removeCartItem inline-flex items-center justify-center rounded-md text-gray-300 transition hover:text-red-600" data-id="{{ $itemId }}" title="Remove item">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="mt-8 grid gap-10 lg:grid-cols-[1fr_360px]">

            {{-- Voucher Section --}}
            <section class="h-fit">
              <h2 class="text-xs font-medium uppercase tracking-[0.15em] text-gray-400">Voucher Code</h2>
              <p class="mt-1 text-sm text-gray-500">Apply a voucher code to save on your order.</p>

              <div class="mt-4">
                @if($coupon)
                  <div class="flex items-center justify-between gap-3 rounded-md border border-green-200 bg-green-50 px-4 py-3">
                    <div class="flex items-center gap-2.5">
                      <p class="font-medium text-green-800">{{ $coupon['code'] }}</p>
                      <p class="text-sm text-green-600">-${{ number_format($coupon['discount'], 2) }} discount applied</p>
                    </div>
                    <button class="removeCouponBtn inline-flex flex-shrink-0 items-center gap-1 text-sm font-medium text-gray-500 transition hover:text-red-600" type="button">
                      Remove
                    </button>
                  </div>
                @else
                  <div class="flex flex-col gap-2 sm:flex-row">
                    <input id="couponCode" class="w-full flex-1 rounded-md border border-gray-300 px-3 py-2.5 text-sm transition focus:border-gray-900 focus:outline-none" type="text" placeholder="Enter voucher code">
                    <button id="applyCouponBtn" class="rounded-md border border-gray-900 px-6 py-2.5 text-sm font-medium text-gray-900 transition hover:bg-gray-900 hover:text-white" type="button">
                      Apply
                    </button>
                  </div>
                @endif
              </div>
            </section>

            {{-- Order Summary --}}
            <section class="h-fit">
              <h2 class="mb-5 text-xs font-medium uppercase tracking-[0.15em] text-gray-400">Order Summary</h2>

              <div class="space-y-3 text-sm">
                <div class="flex justify-between gap-4">
                  <span class="text-gray-500">Subtotal</span>
                  <span class="font-medium text-gray-900">${{ number_format($cartTotal, 2) }}</span>
                </div>
                <div class="flex justify-between gap-4">
                  <span class="text-gray-500">Coupon discount</span>
                  <span id="discountValue" class="font-medium {{ $couponDiscount > 0 ? 'text-green-600' : 'text-gray-400' }}">-${{ number_format($couponDiscount, 2) }}</span>
                </div>
              </div>

              <div class="my-5 border-t border-gray-100"></div>

              <div class="flex items-center justify-between gap-4">
                <span class="font-medium text-gray-900">Grand total</span>
                <strong class="grandTotalValue font-semibold text-gray-900">${{ number_format($grandTotal, 2) }}</strong>
              </div>

              <div class="mt-6 space-y-3">
                <a href="{{ route('checkout.index') }}" id="checkoutBtn" class="block w-full rounded-full bg-gray-900 px-6 py-3 text-center text-sm font-medium text-white transition hover:bg-gray-800">
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
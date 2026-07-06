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
          <div class="overflow-x-auto cart-responsive">
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

          <div class="mt-6 grid gap-5 lg:grid-cols-[1fr_360px]">
            <section class="rounded-lg border border-gray-200 bg-gray-50 p-5">
              <h2 class="mb-3 text-lg font-black uppercase tracking-wide">Vouchers Code</h2>
              <div class="flex flex-col gap-3 sm:flex-row">
                <input class="form-control" type="text" placeholder="Enter voucher code">
                <button class="btn-danger rounded-md px-6 py-3 font-black" type="button">ADD</button>
              </div>
            </section>
            <section class="rounded-lg border border-gray-200 bg-white p-5">
              <div class="space-y-3 text-sm">
                <div class="flex justify-between gap-4"><span>Grand Total:</span><strong class="grandTotalValue">${{ number_format($cartTotal, 2) }}</strong></div>
                <div class="flex justify-between gap-4"><span>Total Discount:</span><strong>$0.00</strong></div>
                <div class="flex justify-between gap-4"><span>Total Tax:</span><strong>$0.00</strong></div>
              </div>
              <button class="btn-danger mt-5 flex w-full items-center justify-between rounded-md px-5 py-4 text-left text-lg font-black" type="button"><span>TOTAL</span><span class="grandTotalValue">${{ number_format($cartTotal, 2) }}</span></button>
            </section>
          </div>
        @endif
        <div class="cart-cursor" aria-hidden="true"></div>
      </div>
    </div>
  </section>
</div>
@endsection
@extends('frontend.layouts.app')
@section('title', __('Orders'))
@section('content')
<div class="col-span-full">
  <section class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb-card px-4 py-3 text-sm" aria-label="Breadcrumb">
      <ol class="flex flex-wrap items-center gap-2 text-gray-600">
        <li><a class="breadcrumb-link" href="{{ url('/') }}">Home</a></li>
        <li aria-hidden="true">/</li>
        <li class="font-medium text-gray-900" aria-current="page">My Orders</li>
      </ol>
    </nav>

    <div class="space-y-8">
      <div class="relative bg-white p-4 md:p-8">

        {{-- Heading --}}
        <div class="mb-10 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <p class="text-xs font-medium uppercase tracking-[0.15em] text-gray-400">Account</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">My Orders</h1>
          </div>
          <p class="text-sm text-gray-500">{{ $orders->count() }} {{ Str::plural('order', $orders->count()) }}</p>
        </div>

        @if($orders->isEmpty())
          <div class="py-16 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
              <svg class="h-5 w-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h2 class="mt-4 text-lg font-medium text-gray-900">No orders yet</h2>
            <p class="mt-1 text-sm text-gray-500">When you place an order, it will appear here.</p>
            <a href="{{ url('/') }}" class="mt-6 inline-flex items-center gap-2 rounded-full border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white">
              Start shopping
            </a>
          </div>
        @else
          <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] border-collapse text-sm">
              <thead>
                <tr class="text-xs uppercase tracking-wider text-gray-400">
                  <th class="py-2 pr-4 text-left font-medium">Order number</th>
                  <th class="px-4 py-2 text-left font-medium">Date</th>
                  <th class="px-4 py-2 text-right font-medium">Amount</th>
                  <th class="px-4 py-2 text-left font-medium">Payment Method</th>
                  <th class="px-4 py-2 text-left font-medium">Payment Status</th>
                  <th class="px-4 py-2 text-left font-medium">Order Status</th>
                  <th class="py-2 pl-4 text-right font-medium">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @foreach($orders as $order)
                  <tr>
                    <td class="py-4 pr-4 font-medium text-gray-900">{{ $order->order_number }}</td>
                    <td class="whitespace-nowrap px-4 py-4 text-gray-500">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                    <td class="px-4 py-4 text-right font-medium text-gray-900">${{ number_format($order->grand_total, 2) }}</td>
                    <td class="px-4 py-4 text-gray-700">
                      {{ strtoupper($order->payment_method) === 'COD' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}
                    </td>
                    <td class="px-4 py-4">
                      <span class="inline-flex items-center rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-700">
                        {{ ucfirst($order->payment_status) }}
                      </span>
                    </td>
                    <td class="px-4 py-4">
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
                    </td>
                    <td class="py-4 pl-4 text-right">
                      <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-gray-900">
                        View
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </section>
</div>
@endsection
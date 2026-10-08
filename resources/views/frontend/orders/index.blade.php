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
        <li class="font-bold text-gray-900" aria-current="page">My Orders</li>
      </ol>
    </nav>

    <div class="space-y-8">
      <div class="relative rounded-lg border border-gray-200 bg-white p-4 md:p-6">
        <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
          <div>
            <p class="text-sm font-bold uppercase tracking-wider text-red-600">Account</p>
            <h1 class="text-3xl font-black">My Orders</h1>
          </div>
          <p class="text-sm font-semibold text-gray-500">{{ $orders->count() }} {{ Str::plural('order', $orders->count()) }}</p>
        </div>

        <hr class="border-gray-200 mb-6"/>

        @if($orders->isEmpty())
          <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center">
            <h2 class="text-xl font-black text-gray-900">You have no orders yet</h2>
            <p class="mt-2 text-sm text-gray-600">Place an order and it will show up here.</p>
            <a href="{{ url('/') }}" class="mt-5 inline-flex rounded-md bg-primary px-5 py-3 text-sm font-bold text-white hover:bg-primary-hover transition">Start Shopping</a>
          </div>
        @else
          <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] border-collapse text-left">
              <thead>
                <tr class="border-y bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                  <th class="px-3 py-3">Order Number</th>
                  <th class="px-3 py-3">Date</th>
                  <th class="px-3 py-3 text-right">Amount</th>
                  <th class="px-3 py-3">Payment</th>
                  <th class="px-3 py-3">Status</th>
                  <th class="px-3 py-3 text-center">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y">
                @foreach($orders as $order)
                  <tr>
                    <td class="px-3 py-4 align-top font-black text-gray-900">{{ $order->order_number }}</td>
                    <td class="px-3 py-4 align-top text-sm text-gray-600">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                    <td class="px-3 py-4 align-top text-right font-black text-red-600">${{ number_format($order->grand_total, 2) }}</td>
                    <td class="px-3 py-4 align-top">
                      <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-bold uppercase tracking-wide text-gray-700">
                        {{ strtoupper($order->payment_method) === 'COD' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}
                      </span>
                    </td>
                    <td class="px-3 py-4 align-top">
                      @php
                        $statusColors = [
                          'pending' => 'amber',
                          'processing' => 'blue',
                          'shipped' => 'indigo',
                          'delivered' => 'green',
                          'completed' => 'green',
                          'cancelled' => 'red',
                          'failed' => 'red',
                        ];
                        $statusColor = $statusColors[$order->order_status] ?? 'gray';
                      @endphp
                      <span class="inline-flex items-center rounded-full bg-{{ $statusColor }}-100 px-2 py-0.5 text-xs font-bold uppercase tracking-wide text-{{ $statusColor }}-700">
                        {{ ucfirst($order->order_status) }}
                      </span>
                    </td>
                    <td class="px-3 py-4 align-top text-center">
                      <a href="#" class="inline-flex items-center gap-1.5 rounded-md border border-blue-200 bg-blue-50 px-2.5 py-1.5 text-xs font-bold text-blue-600 transition hover:bg-blue-100 hover:text-blue-800">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        View Order
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

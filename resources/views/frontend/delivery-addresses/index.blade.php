@extends('frontend.layouts.app')

@section('title', 'Delivery Addresses')

@section('content')
<div class="col-span-full">
  <section class="space-y-6">
    <div class="space-y-8">
      <div class="relative rounded-lg border border-gray-200 bg-white p-4 md:p-6">

        {{-- Page Heading --}}
        <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
          <div>
            <p class="text-sm font-bold uppercase tracking-wider text-red-600">Account</p>
            <h1 class="text-3xl font-black">Delivery Addresses</h1>
          </div>
        </div>

        <hr class="border-gray-200 mb-6"/>

        {{-- Success Message --}}
        @if(session('success'))
          <div class="mb-6 flex items-center gap-3 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-bold text-green-700">
            <svg class="h-5 w-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
          </div>
        @endif

        {{-- Delivery Addresses Section --}}
        @include('frontend.delivery-addresses.partials.address-section')

      </div>
    </div>
  </section>
</div>
@endsection

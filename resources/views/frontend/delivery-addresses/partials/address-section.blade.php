@php
    $isCheckout = request()->routeIs('checkout.*');
@endphp

{{-- ========================================================================= --}}
{{-- Delivery Addresses Section --}}
{{-- ========================================================================= --}}
<div class="rounded-lg border border-gray-200 bg-gray-50 p-5 mb-6">
  <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-lg font-black uppercase tracking-wide">
      {{ $isCheckout ? '1. Delivery Addresses' : 'Delivery Addresses' }}
    </h2>
    <button type="button" id="openAddAddressModal" class="inline-flex items-center gap-1 rounded-md bg-blue-600 px-3 py-1.5 text-sm font-bold text-white hover:bg-blue-700 transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add New Address
    </button>
  </div>

  @if($deliveryAddresses->count())
    <div class="grid gap-4 sm:grid-cols-2">
      @foreach($deliveryAddresses as $address)
        @if($isCheckout)
          {{-- Checkout: Radio selector --}}
          <div data-address-card class="rounded-md border-2 {{ $address->is_default ? 'border-green-500' : 'border-gray-200 hover:border-gray-300' }} bg-white p-4 transition">
            <div class="mb-2 flex items-center justify-between">
              <label class="flex cursor-pointer items-center gap-2">
                <input type="radio" name="delivery_address" value="{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500">
                <span class="{{ $address->is_default ? 'text-xs font-bold uppercase tracking-wider text-gray-500' : 'font-bold text-gray-700' }}">{{ $address->is_default ? 'Default' : $address->name }}</span>
              </label>
            </div>
            <p class="font-black text-gray-900">{{ $address->name }}</p>
            <p class="mt-1 text-sm text-gray-600">
              {{ $address->address }}<br>
              {{ $address->city }}{{ $address->state ? ', ' . $address->state : '' }}<br>
              {{ $address->country }}{{ $address->postal_code ? ' ' . $address->postal_code : '' }}
            </p>
            <p class="mt-1 text-sm text-gray-500">Phone: {{ $address->phone }}</p>
          </div>
        @else
          {{-- Delivery Addresses page: Card view --}}
          <div class="rounded-md border {{ $address->is_default ? 'border-green-500' : 'border-gray-200' }} bg-white p-4 transition hover:border-gray-300">
            <div class="mb-2 flex items-center justify-between">
              <span class="{{ $address->is_default ? 'text-xs font-bold uppercase tracking-wider text-gray-500' : 'font-bold text-gray-700' }}">
                {{ $address->is_default ? 'Default' : 'Address' }}
              </span>
              @if($address->is_default)
                <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-bold text-green-700">Default</span>
              @endif
            </div>
            <p class="font-black text-gray-900">{{ $address->name }}</p>
            <p class="mt-1 text-sm text-gray-600">
              {{ $address->address }}<br>
              {{ $address->city }}{{ $address->state ? ', ' . $address->state : '' }}<br>
              {{ $address->country }}{{ $address->postal_code ? ' ' . $address->postal_code : '' }}
            </p>
            <p class="mt-1 text-sm text-gray-500">Phone: {{ $address->phone }}</p>
            <div class="mt-3 flex items-center gap-3">
              <button type="button" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">Edit</button>
              <button type="button" class="text-xs font-bold text-red-500 hover:text-red-700 transition">Delete</button>
            </div>
          </div>
        @endif
      @endforeach
    </div>
  @else
    <div class="rounded-md border border-dashed border-gray-300 bg-white p-8 text-center">
      <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      <p class="mt-3 text-sm font-bold text-gray-600">No delivery addresses yet.</p>
      <p class="mt-1 text-sm text-gray-400">Click "Add New Address" to get started.</p>
    </div>
  @endif
</div>

{{-- ========================================================================= --}}
{{-- Add Delivery Address Modal --}}
{{-- ========================================================================= --}}
<div
  id="addAddressModal"
  class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 p-4"
  onclick="if(event.target===this) $(this).fadeOut(200).removeClass('flex')"
>
  <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
    <form action="{{ route('delivery-addresses.store') }}" method="POST">
      @csrf

      <input
        type="hidden"
        name="redirect_to"
        value="{{ $isCheckout ? 'checkout' : 'delivery-addresses' }}"
      >

      {{-- Modal Header --}}
      <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
        <h3 class="text-lg font-black text-gray-900">Add New Delivery Address</h3>
        <button type="button" onclick="$('#addAddressModal').fadeOut(200).removeClass('flex')" class="text-gray-400 hover:text-gray-600 transition">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      {{-- Modal Body --}}
      <div class="max-h-[500px] overflow-y-auto px-6 py-4 space-y-4">

        {{-- Full Name --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">Full Name <span class="text-red-500">*</span></label>
          <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
          @error('name')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        {{-- Phone Number --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">Phone Number <span class="text-red-500">*</span></label>
          <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
          @error('phone')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        {{-- Address --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">Address <span class="text-red-500">*</span></label>
          <textarea name="address" rows="3" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">{{ old('address') }}</textarea>
          @error('address')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        {{-- City --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">City <span class="text-red-500">*</span></label>
          <input type="text" name="city" value="{{ old('city') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
          @error('city')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        {{-- State --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">State</label>
          <input type="text" name="state" value="{{ old('state') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
          @error('state')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        {{-- Country --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">Country <span class="text-red-500">*</span></label>
          <input type="text" name="country" value="{{ old('country') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
          @error('country')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        {{-- Postal Code --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">Postal Code</label>
          <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
          @error('postal_code')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

      </div>

      {{-- Modal Footer --}}
      <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4">
        <button type="button" onclick="$('#addAddressModal').fadeOut(200).removeClass('flex')" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
          Cancel
        </button>
        <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-green-600 px-4 py-2 text-sm font-bold text-white hover:bg-green-700 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Save Address
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ========================================================================= --}}
{{-- Modal Scripts --}}
{{-- ========================================================================= --}}
@push('scripts')
<script>
  $(document).ready(function () {
    $('#openAddAddressModal').on('click', function () {
      $('#addAddressModal').removeClass('hidden').addClass('flex').hide().fadeIn(200);
    });

    @if($isCheckout)
      function syncAddressSelection() {
        $('input[name="delivery_address"]').each(function () {
          var $card = $(this).closest('[data-address-card]');
          if ($(this).is(':checked')) {
            $card.removeClass('border-gray-200 hover:border-gray-300').addClass('border-green-500');
          } else {
            $card.removeClass('border-green-500').addClass('border-gray-200 hover:border-gray-300');
          }
        });
      }

      $('input[name="delivery_address"]').on('change', syncAddressSelection);
      syncAddressSelection();
    @endif
  });

  @if($errors->any())
    $(document).ready(function () {
      $('#addAddressModal').removeClass('hidden').addClass('flex').show();
    });
  @endif
</script>
@endpush

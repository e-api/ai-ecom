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
          {{-- Checkout: Radio selector (whole card clickable like payment methods) --}}
          <label data-address-card class="block cursor-pointer rounded-md border-2 {{ $address->is_default ? 'border-green-500' : 'border-gray-200 hover:border-gray-300' }} bg-white p-4 transition">
            <div class="mb-2 flex min-h-5 items-center justify-end">
              <input type="radio" name="delivery_address" value="{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }} class="sr-only">
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
            <div class="mt-3 flex items-center gap-2">
              @if(!$address->is_default)
                <form
                  method="POST"
                  action="{{ route('delivery-addresses.make-default', $address->id) }}"
                  class="inline-flex"
                  onsubmit="return confirm('Are you sure you want to make this your default delivery address?');"
                >
                  @csrf
                  <input type="hidden" name="redirect_to" value="checkout">
                  <button
                    type="submit"
                    class="inline-flex items-center gap-1.5 rounded-md border border-green-200 bg-green-50 px-2.5 py-1.5 text-xs font-bold text-green-600 transition hover:bg-green-100 hover:text-green-700"
                  >
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Make Default
                  </button>
                </form>
              @endif
              <button
                type="button"
                class="open-edit-modal inline-flex items-center gap-1.5 rounded-md border border-blue-200 bg-blue-50 px-2.5 py-1.5 text-xs font-bold text-blue-600 transition hover:bg-blue-100 hover:text-blue-800"
                data-id="{{ $address->id }}"
                data-name="{{ $address->name }}"
                data-phone="{{ $address->phone }}"
                data-address="{{ $address->address }}"
                data-city="{{ $address->city }}"
                data-state="{{ $address->state }}"
                data-country="{{ $address->country }}"
                data-postal-code="{{ $address->postal_code }}"
              >
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </button>
              <button
                type="button"
                class="open-delete-modal inline-flex items-center gap-1.5 rounded-md border border-red-200 bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-500 transition hover:bg-red-100 hover:text-red-700"
                data-id="{{ $address->id }}"
                data-name="{{ $address->name }}"
              >
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete
              </button>
            </div>
          </label>
        @else
          {{-- Delivery Addresses page: Card view --}}
          <div class="rounded-md border {{ $address->is_default ? 'border-green-500' : 'border-gray-200' }} bg-white p-4 transition hover:border-gray-300">
            <div class="mb-2 flex min-h-5 items-center justify-end">
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
            <div class="mt-3 flex items-center gap-2">
              @if(!$address->is_default)
                <form
                  method="POST"
                  action="{{ route('delivery-addresses.make-default', $address->id) }}"
                  class="inline-flex"
                  onsubmit="return confirm('Are you sure you want to make this your default delivery address?');"
                >
                  @csrf
                  <button
                    type="submit"
                    class="inline-flex items-center gap-1.5 rounded-md border border-green-200 bg-green-50 px-2.5 py-1.5 text-xs font-bold text-green-600 transition hover:bg-green-100 hover:text-green-700"
                  >
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Make Default
                  </button>
                </form>
              @endif
              <button
                type="button"
                class="open-edit-modal inline-flex items-center gap-1.5 rounded-md border border-blue-200 bg-blue-50 px-2.5 py-1.5 text-xs font-bold text-blue-600 transition hover:bg-blue-100 hover:text-blue-800"
                data-id="{{ $address->id }}"
                data-name="{{ $address->name }}"
                data-phone="{{ $address->phone }}"
                data-address="{{ $address->address }}"
                data-city="{{ $address->city }}"
                data-state="{{ $address->state }}"
                data-country="{{ $address->country }}"
                data-postal-code="{{ $address->postal_code }}"
              >
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </button>
              <button
                type="button"
                class="open-delete-modal inline-flex items-center gap-1.5 rounded-md border border-red-200 bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-500 transition hover:bg-red-100 hover:text-red-700"
                data-id="{{ $address->id }}"
                data-name="{{ $address->name }}"
              >
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete
              </button>
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
{{-- Edit Delivery Address Modal --}}
{{-- ========================================================================= --}}
<div
  id="editAddressModal"
  class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 p-4"
  onclick="if(event.target===this) $(this).fadeOut(200).removeClass('flex')"
>
  <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
    <form id="editAddressForm" action="{{ route('delivery-addresses.update', ['deliveryAddress' => 0]) }}" method="POST">
      @csrf
      @method('PUT')

      <input
        type="hidden"
        id="edit_address_id"
        name="id"
        value=""
      >

      <input
        type="hidden"
        id="edit_redirect_to"
        name="redirect_to"
        value="{{ $isCheckout ? 'checkout' : 'delivery-addresses' }}"
      >

      {{-- Modal Header --}}
      <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
        <h3 class="text-lg font-black text-gray-900">Edit Delivery Address</h3>
        <button type="button" onclick="$('#editAddressModal').fadeOut(200).removeClass('flex')" class="text-gray-400 hover:text-gray-600 transition">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      {{-- Modal Body --}}
      <div class="max-h-[500px] overflow-y-auto px-6 py-4 space-y-4">

        {{-- Full Name --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">Full Name <span class="text-red-500">*</span></label>
          <input type="text" id="edit_name" name="name" value="" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
          <p class="edit-error mt-1 hidden text-xs text-red-500">Please enter your full name.</p>
        </div>

        {{-- Phone Number --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">Phone Number <span class="text-red-500">*</span></label>
          <input type="text" id="edit_phone" name="phone" value="" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
          <p class="edit-error mt-1 hidden text-xs text-red-500">Please enter a valid phone number.</p>
        </div>

        {{-- Address --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">Address <span class="text-red-500">*</span></label>
          <textarea id="edit_address" name="address" rows="3" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"></textarea>
          <p class="edit-error mt-1 hidden text-xs text-red-500">Please enter your address.</p>
        </div>

        {{-- City --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">City <span class="text-red-500">*</span></label>
          <input type="text" id="edit_city" name="city" value="" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
          <p class="edit-error mt-1 hidden text-xs text-red-500">Please enter your city.</p>
        </div>

        {{-- State --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">State</label>
          <input type="text" id="edit_state" name="state" value="" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
        </div>

        {{-- Country --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">Country <span class="text-red-500">*</span></label>
          <input type="text" id="edit_country" name="country" value="" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
          <p class="edit-error mt-1 hidden text-xs text-red-500">Please enter your country.</p>
        </div>

        {{-- Postal Code --}}
        <div>
          <label class="mb-1 block text-sm font-bold text-gray-700">Postal Code</label>
          <input type="text" id="edit_postal_code" name="postal_code" value="" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
        </div>

      </div>

      {{-- Modal Footer --}}
      <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4">
        <button type="button" onclick="$('#editAddressModal').fadeOut(200).removeClass('flex')" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
          Cancel
        </button>
        <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-green-600 px-4 py-2 text-sm font-bold text-white hover:bg-green-700 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Update Address
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ========================================================================= --}}
{{-- Delete Address Confirmation Modal --}}
{{-- ========================================================================= --}}
<div
  id="deleteAddressModal"
  class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 p-4"
  onclick="if(event.target===this) $(this).fadeOut(200).removeClass('flex')"
>
  <div class="w-full max-w-md rounded-lg bg-white shadow-xl">
    <form id="deleteAddressForm" action="{{ route('delivery-addresses.destroy', ['deliveryAddress' => 0]) }}" method="POST">
      @csrf
      @method('DELETE')

      <input
        type="hidden"
        name="redirect_to"
        value="{{ $isCheckout ? 'checkout' : 'delivery-addresses' }}"
      >

      {{-- Modal Header --}}
      <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
        <h3 class="text-lg font-black text-gray-900">Delete Address</h3>
        <button type="button" onclick="$('#deleteAddressModal').fadeOut(200).removeClass('flex')" class="text-gray-400 hover:text-gray-600 transition">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      {{-- Modal Body --}}
      <div class="px-6 py-4">
        <div class="flex items-start gap-3">
          <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
            <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          </div>
          <p class="text-sm text-gray-600">
            Are you sure you want to delete the delivery address
            <span id="deleteAddressName" class="font-bold text-gray-900"></span>?
            This action cannot be undone.
          </p>
        </div>
      </div>

      {{-- Modal Footer --}}
      <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4">
        <button type="button" onclick="$('#deleteAddressModal').fadeOut(200).removeClass('flex')" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
          Cancel
        </button>
        <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-red-600 px-4 py-2 text-sm font-bold text-white hover:bg-red-700 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete Address
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

    {{-- Edit Address Modal --}}
    $('.open-edit-modal').on('click', function () {
      var id = $(this).data('id');
      var action = "{{ route('delivery-addresses.update', ['deliveryAddress' => '__ID__']) }}".replace('__ID__', id);

      $('#edit_address_id').val(id);
      $('#edit_name').val($(this).data('name'));
      $('#edit_phone').val($(this).data('phone'));
      $('#edit_address').val($(this).data('address'));
      $('#edit_city').val($(this).data('city'));
      $('#edit_state').val($(this).data('state'));
      $('#edit_country').val($(this).data('country'));
      $('#edit_postal_code').val($(this).data('postal-code'));
      $('#editAddressForm').attr('action', action);

      $('#editAddressModal').removeClass('hidden').addClass('flex').hide().fadeIn(200);
      clearEditErrors();
    });

    {{-- Delete Address Modal --}}
    $('.open-delete-modal').on('click', function () {
      var id = $(this).data('id');
      var action = "{{ route('delivery-addresses.destroy', ['deliveryAddress' => '__ID__']) }}".replace('__ID__', id);

      $('#deleteAddressName').text($(this).data('name'));
      $('#deleteAddressForm').attr('action', action);

      $('#deleteAddressModal').removeClass('hidden').addClass('flex').hide().fadeIn(200);
    });

    {{-- Edit Address Client-Side Validation --}}
    $('#editAddressForm').on('submit', function (e) {
      var valid = true;
      clearEditErrors();

      var name = $.trim($('#edit_name').val());
      var phone = $.trim($('#edit_phone').val());
      var address = $.trim($('#edit_address').val());
      var city = $.trim($('#edit_city').val());
      var country = $.trim($('#edit_country').val());

      if (!name) {
        showEditError('#edit_name', 'Please enter your full name.');
        valid = false;
      }
      if (!phone || !/^[0-9+\-\s()]+$/.test(phone)) {
        showEditError('#edit_phone', 'Please enter a valid phone number.');
        valid = false;
      }
      if (!address) {
        showEditError('#edit_address', 'Please enter your address.');
        valid = false;
      }
      if (!city) {
        showEditError('#edit_city', 'Please enter your city.');
        valid = false;
      }
      if (!country) {
        showEditError('#edit_country', 'Please enter your country.');
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
        $('#editAddressModal .max-h-\\[500px\\]')
          .scrollTop(0);
      }
    });

    {{-- Re-open Edit modal when server-side validation fails --}}
    $('input, textarea', '#editAddressForm').on('input change', function () {
      clearEditError(this);
    });
  });

  function showEditError(field, message) {
    var $field = $(field);
    $field.addClass('border-red-500 focus:border-red-500 focus:ring-red-500');
    var $error = $field.closest('div').find('.edit-error');
    if ($error.length) {
      $error.text(message).removeClass('hidden');
    }
  }

  function clearEditError(field) {
    var $field = $(field);
    $field.removeClass('border-red-500 focus:border-red-500 focus:ring-red-500');
    var $error = $field.closest('div').find('.edit-error');
    if ($error.length) {
      $error.addClass('hidden');
    }
  }

  function clearEditErrors() {
    $('#editAddressForm input, #editAddressForm textarea').removeClass(
      'border-red-500 focus:border-red-500 focus:ring-red-500'
    );
    $('#editAddressForm .edit-error').addClass('hidden');
  }

  @if($errors->any())
    $(document).ready(function () {
      @if(!old('id'))
        $('#addAddressModal').removeClass('hidden').addClass('flex').show();
      @endif

      @if(old('id'))
        var id = @json(old('id'));
        var action = "{{ route('delivery-addresses.update', ['deliveryAddress' => '__ID__']) }}".replace('__ID__', id);

        $('#edit_address_id').val(id);
        $('#edit_name').val(@json(old('name')));
        $('#edit_phone').val(@json(old('phone')));
        $('#edit_address').val(@json(old('address')));
        $('#edit_city').val(@json(old('city')));
        $('#edit_state').val(@json(old('state')));
        $('#edit_country').val(@json(old('country')));
        $('#edit_postal_code').val(@json(old('postal_code')));
        $('#editAddressForm').attr('action', action);

        $('#editAddressModal').removeClass('hidden').addClass('flex').show();

        @foreach($errors->getMessages() as $field => $messages)
          @if(in_array($field, ['name', 'phone', 'address', 'city', 'country']))
            showEditError('#edit_{{ $field }}', @json($messages[0]));
          @endif
        @endforeach
      @endif
    });
  @endif
</script>
@endpush

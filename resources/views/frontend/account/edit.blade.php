@extends('frontend.layouts.app')

@section('title', 'Update Account Details')

@section('content')
<div class="col-span-full">
  <section class="space-y-6">
    <div class="space-y-8">
      <div class="relative rounded-lg border border-gray-200 bg-white p-4 md:p-6">

        {{-- Page Heading --}}
        <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
          <div>
            <p class="text-sm font-bold uppercase tracking-wider text-red-600">Account</p>
            <h1 class="text-3xl font-black">Update Account Details</h1>
          </div>
        </div>

        <hr class="border-gray-200 mb-6"/>

        {{-- Success Message --}}
        <div id="successMessage"
          class="mb-6 hidden items-center gap-3 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-bold text-green-700">
          <svg class="h-5 w-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          <span id="successMessageText"></span>
        </div>

        {{-- Account Details Section --}}
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
          <h2 class="mb-5 text-lg font-black uppercase tracking-wide">Personal Information</h2>

          <form method="POST"
            id="accountForm"
            action="{{ route('account.update') }}">
            @csrf

            <div class="grid gap-5 sm:grid-cols-2">

              {{-- Name --}}
              <div>
                <label for="name" class="mb-1 block text-sm font-bold text-gray-700">
                  Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                  id="name"
                  name="name"
                  value="{{ old('name', $user->name) }}"
                  placeholder="Enter Name"
                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                <span id="name-error" class="validation-error mt-1 block text-xs text-red-500"></span>
              </div>

              {{-- Mobile --}}
              <div>
                <label for="phone" class="mb-1 block text-sm font-bold text-gray-700">
                  Mobile <span class="text-red-500">*</span>
                </label>
                <input type="tel"
                  id="phone"
                  name="phone"
                  value="{{ old('phone', $user->phone) }}"
                  placeholder="Enter Mobile Number"
                  maxlength="20"
                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                <span id="phone-error" class="validation-error mt-1 block text-xs text-red-500"></span>
              </div>

              {{-- Email --}}
              <div class="sm:col-span-2">
                <label for="email" class="mb-1 block text-sm font-bold text-gray-700">
                  Email
                </label>
                <input type="email"
                  id="email"
                  value="{{ $user->email }}"
                  readonly
                  class="w-full cursor-not-allowed rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-500 focus:outline-none">
                <p class="mt-1 text-xs text-gray-500">Email address cannot be changed.</p>
              </div>

            </div>

            {{-- Submit Button --}}
            <div class="mt-6 flex items-center gap-3">
              <button type="submit"
                class="inline-flex items-center gap-1 rounded-md bg-green-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-green-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Update Details
              </button>
            </div>

          </form>
        </div>

        {{-- Change Password Section --}}
        <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-5">
          <h2 class="mb-2 text-lg font-black uppercase tracking-wide">Change Password</h2>
          <p class="mb-4 text-sm text-gray-600">
            Want to update your account password?
            Click the button below to change it.
          </p>
          <a href="{{ route('account.password.edit') }}"
            class="inline-flex items-center gap-1.5 rounded-md bg-blue-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.313-2.687 6-6 6m2-3H5m9 0a3 3 0 110-6 3 3 0 010 6zm-6 0a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Change Password
          </a>
        </div>

      </div>
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // Submit Account Details Form
    $('#accountForm').submit(function (e) {

        e.preventDefault();

        $('.validation-error').html('');
        $('#successMessage').addClass('hidden').removeClass('flex');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            beforeSend: function () {

                $('#accountForm button[type="submit"]')
                    .prop('disabled', true)
                    .text('Updating...');

            },
            success: function (response) {

                $('#successMessageText').text(response.message);
                $('#successMessage').removeClass('hidden').addClass('flex');

            },
            error: function (xhr) {

                if (xhr.status == 422) {

                    let response = xhr.responseJSON;

                    $.each(response.errors, function (field, messages) {

                        $('#' + field + '-error')
                            .html(messages[0]);

                    });

                }

            },
            complete: function () {

                $('#accountForm button[type="submit"]')
                    .prop('disabled', false)
                    .html('<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Update Details');

            }

        });

    });

    // Clear Validation Errors While Typing
    $('#accountForm input').on('keyup change', function () {

        let field = $(this).attr('name');

        if (field) {

            $('#' + field + '-error').html('');

        }

    });

});
</script>
@endpush

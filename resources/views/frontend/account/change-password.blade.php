@extends('frontend.layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="col-span-full">
  <section class="space-y-6">
    <div class="space-y-8">
      <div class="relative rounded-lg border border-gray-200 bg-white p-4 md:p-6">

        {{-- Page Heading --}}
        <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
          <div>
            <p class="text-sm font-bold uppercase tracking-wider text-red-600">Account</p>
            <h1 class="text-3xl font-black">Change Password</h1>
          </div>
          <a href="{{ route('account.edit') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-gray-900">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to My Account
          </a>
        </div>

        <hr class="border-gray-200 mb-6"/>

        {{-- Success Message --}}
        <div id="successMessage"
          class="mb-6 hidden items-center gap-3 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-bold text-green-700">
          <svg class="h-5 w-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          <span id="successMessageText"></span>
        </div>

        {{-- Password Information Section --}}
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
          <h2 class="mb-5 text-lg font-black uppercase tracking-wide">Password Information</h2>

          <form method="POST"
            id="changePasswordForm"
            action="{{ route('account.password.update') }}">
            @csrf

            <div class="grid gap-5 sm:grid-cols-2">

              {{-- Current Password --}}
              <div class="sm:col-span-2">
                <label for="current_password" class="mb-1 block text-sm font-bold text-gray-700">
                  Current Password <span class="text-red-500">*</span>
                </label>
                <input type="password"
                  id="current_password"
                  name="current_password"
                  placeholder="Enter Current Password"
                  autocomplete="current-password"
                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                <span id="current_password-error" class="validation-error mt-1 block text-xs text-red-500"></span>
              </div>

              {{-- New Password --}}
              <div>
                <label for="password" class="mb-1 block text-sm font-bold text-gray-700">
                  New Password <span class="text-red-500">*</span>
                </label>
                <input type="password"
                  id="password"
                  name="password"
                  placeholder="Enter New Password"
                  autocomplete="new-password"
                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                <p class="mt-1 text-xs text-gray-500">At least 8 characters.</p>
                <span id="password-error" class="validation-error mt-1 block text-xs text-red-500"></span>
              </div>

              {{-- Confirm Password --}}
              <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-bold text-gray-700">
                  Confirm Password <span class="text-red-500">*</span>
                </label>
                <input type="password"
                  id="password_confirmation"
                  name="password_confirmation"
                  placeholder="Confirm New Password"
                  autocomplete="new-password"
                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                <span id="password_confirmation-error" class="validation-error mt-1 block text-xs text-red-500"></span>
              </div>

            </div>

            {{-- Actions --}}
            <div class="mt-6 flex items-center gap-3">
              <button type="submit"
                class="inline-flex items-center gap-1 rounded-md bg-green-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-green-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Change Password
              </button>
              <a href="{{ route('account.edit') }}"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-700 transition hover:bg-gray-50">
                Cancel
              </a>
            </div>

          </form>
        </div>

      </div>
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // Submit Change Password Form
    $('#changePasswordForm').submit(function (e) {

        e.preventDefault();

        $('.validation-error').html('');
        $('#successMessage').addClass('hidden').removeClass('flex');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),

            // Request JSON response from Laravel
            headers: {
                'Accept': 'application/json'
            },

            dataType: 'json',

            beforeSend: function () {

                $('#changePasswordForm button[type="submit"]')
                    .prop('disabled', true)
                    .text('Updating...');

            },

            success: function (response) {

                $('#successMessageText').text(response.message);
                $('#successMessage').removeClass('hidden').addClass('flex');

                // Clear password fields
                $('#changePasswordForm')[0].reset();

            },

            error: function (xhr) {

                if (xhr.status == 422) {

                    // Get validation errors
                    let response = xhr.responseJSON;

                    if (!response && xhr.responseText) {

                        response = JSON.parse(xhr.responseText);

                    }

                    if (response && response.errors) {

                        $.each(response.errors, function (field, messages) {

                            $('#' + field + '-error')
                                .text(messages[0]);

                        });

                    }

                } else {

                    console.log(xhr.responseText);

                    alert('Something went wrong. Please try again.');

                }

            },

            complete: function () {

                $('#changePasswordForm button[type="submit"]')
                    .prop('disabled', false)
                    .html('<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Change Password');

            }

        });

    });

    // Clear Validation Errors While Typing
    $('#changePasswordForm input').on('keyup change', function () {

        let field = $(this).attr('name');

        if (field) {

            $('#' + field + '-error').html('');

        }

    });

});
</script>
@endpush
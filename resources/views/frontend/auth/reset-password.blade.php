@extends('frontend.layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="col-span-full">
  <section class="space-y-6">
    <div class="space-y-8">
      <div class="relative rounded-lg border border-gray-200 bg-white p-4 md:p-6">

        {{-- Page Heading --}}
        <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
          <div>
            <p class="text-sm font-bold uppercase tracking-wider text-red-600">Account</p>
            <h1 class="text-3xl font-black">Reset Password</h1>
          </div>
          <a href="{{ route('login') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-gray-900">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Login
          </a>
        </div>

        <hr class="border-gray-200 mb-6"/>

        {{-- Success Message --}}
        <div id="successMessage"
          class="mb-6 hidden items-center gap-3 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-bold text-green-700">
          <svg class="h-5 w-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          <span id="successMessageText"></span>
        </div>

        {{-- Error Message --}}
        <div id="errorMessage"
          class="mb-6 hidden items-center gap-3 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700">
          <svg class="h-5 w-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-2.99L13.73 4.01c-.77-1.33-2.69-1.33-3.46 0L3.34 16.01c-.77 1.32.19 2.99 1.73 2.99z"/></svg>
          <span id="errorMessageText"></span>
        </div>

        {{-- Create New Password Section --}}
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
          <h2 class="mb-5 text-lg font-black uppercase tracking-wide">Create New Password</h2>

          <form method="POST"
            id="resetPasswordForm"
            action="{{ route('password.update') }}">
            @csrf

            <input type="hidden"
              name="token"
              value="{{ $token }}">

            <div class="grid gap-5 sm:grid-cols-2">

              {{-- Email --}}
              <div class="sm:col-span-2">
                <label for="email" class="mb-1 block text-sm font-bold text-gray-700">
                  Email
                </label>
                <input type="email"
                  id="email"
                  name="email"
                  value="{{ $email }}"
                  readonly
                  class="w-full cursor-not-allowed rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-500 focus:outline-none">
                <span id="email-error" class="validation-error mt-1 block text-xs text-red-500"></span>
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

            {{-- Submit Button --}}
            <div class="mt-6">
              <button type="submit"
                class="inline-flex items-center gap-1.5 rounded-md bg-green-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-green-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Reset Password
              </button>
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

    $('#resetPasswordForm').submit(function (e) {

        e.preventDefault();

        let form = $(this);

        $('.validation-error').html('');
        $('#successMessageText').text('');
        $('#successMessage').addClass('hidden').removeClass('flex');
        $('#errorMessageText').text('');
        $('#errorMessage').addClass('hidden').removeClass('flex');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),

            headers: {
                'Accept': 'application/json'
            },

            dataType: 'json',

            beforeSend: function () {
                form.find('button[type="submit"]')
                    .prop('disabled', true)
                    .text('Updating...');
            },

            success: function (response) {

                if (response.status === true) {

                    $('#successMessageText').text(response.message);
                    $('#successMessage').removeClass('hidden').addClass('flex');

                    form.find('button[type="submit"]')
                        .hide();

                    // Redirect to Login page after successful reset
                    window.setTimeout(function () {
                        window.location.href = "{{ route('login') }}";
                    }, 1500);

                } else {

                    $('#errorMessageText').text(response.message || 'Unable to reset password.');
                    $('#errorMessage').removeClass('hidden').addClass('flex');

                }

            },

            error: function (xhr) {

                let response = xhr.responseJSON;

                if (xhr.status === 422 && response) {

                    if (response.errors) {

                        $.each(response.errors, function (field, messages) {
                            $('#' + field + '-error')
                                .text(messages[0])
                                .show();
                        });

                    } else if (response.message) {

                        $('#errorMessageText').text(response.message);
                        $('#errorMessage').removeClass('hidden').addClass('flex');

                    }

                } else {

                    $('#errorMessageText').text(
                        response && response.message
                            ? response.message
                            : 'Something went wrong. Please try again.'
                    );
                    $('#errorMessage').removeClass('hidden').addClass('flex');

                    console.log(xhr.responseText);

                }

            },

            complete: function () {

                let button = form.find('button[type="submit"]');

                if (button.is(':visible')) {
                    button.prop('disabled', false)
                        .html('<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Reset Password');
                }

            }

        });

    });

    // Clear validation messages while typing
    $('#resetPasswordForm input').on('keyup change', function () {

        let field = $(this).attr('name');

        if (field) {
            $('#' + field + '-error').html('');
        }

        $('#errorMessageText').text('');
        $('#errorMessage').addClass('hidden').removeClass('flex');

    });

});
</script>
@endpush
@extends('frontend.layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="col-span-full">
  <section class="space-y-6">
    <div class="space-y-8">
      <div class="relative rounded-lg border border-gray-200 bg-white p-4 md:p-6">

        {{-- Page Heading --}}
        <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
          <div>
            <p class="text-sm font-bold uppercase tracking-wider text-red-600">Account</p>
            <h1 class="text-3xl font-black">Forgot Password</h1>
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

        {{-- Reset Your Password Section --}}
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
          <h2 class="mb-1 text-lg font-black uppercase tracking-wide">Reset Your Password</h2>
          <p class="mb-5 text-sm text-gray-600">
            Enter your registered email address.
            We will send you a link to reset your password.
          </p>

          <form method="POST"
            id="forgotPasswordForm"
            action="{{ route('password.email') }}">
            @csrf

            <div class="grid gap-5 sm:grid-cols-2">

              {{-- Email --}}
              <div class="sm:col-span-2">
                <label for="email" class="mb-1 block text-sm font-bold text-gray-700">
                  Email <span class="text-red-500">*</span>
                </label>
                <input type="email"
                  id="email"
                  name="email"
                  value="{{ old('email') }}"
                  placeholder="Enter Email"
                  autocomplete="email"
                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                <span id="email-error" class="validation-error mt-1 block text-xs text-red-500"></span>
              </div>

            </div>

            {{-- Submit Button --}}
            <div class="mt-6">
              <button type="submit"
                class="inline-flex items-center gap-1.5 rounded-md bg-green-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-green-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Send Reset Link
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

    $('#forgotPasswordForm').submit(function (e) {

        e.preventDefault();

        $('.validation-error').html('');
        $('#successMessage').addClass('hidden').removeClass('flex');

        $.ajax({

            url: $(this).attr('action'),

            type: 'POST',

            data: $(this).serialize(),

            headers: {
                'Accept': 'application/json'
            },

            dataType: 'json',

            beforeSend: function () {

                $('#forgotPasswordForm button[type="submit"]')
                    .prop('disabled', true)
                    .text('Sending...');

            },

            success: function (response) {

                $('#successMessageText').text(response.message);
                $('#successMessage').removeClass('hidden').addClass('flex');

                $('#forgotPasswordForm')[0].reset();

            },

            error: function (xhr) {

                if (xhr.status == 422) {

                    let response = xhr.responseJSON;

                    if (response && response.errors) {

                        $.each(
                            response.errors,
                            function (field, messages) {

                                $('#' + field + '-error')
                                    .text(messages[0]);

                            }
                        );

                    }

                } else {

                    alert('Something went wrong. Please try again.');

                }

            },

            complete: function () {

                $('#forgotPasswordForm button[type="submit"]')
                    .prop('disabled', false)
                    .html('<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> Send Reset Link');

            }

        });

    });

    $('input').on('keyup change', function () {

        let field = $(this).attr('name');

        if (field) {

            $('#' + field + '-error').html('');

        }

    });

});
</script>

@endpush
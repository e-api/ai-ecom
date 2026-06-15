@extends('frontend.layouts.app')
@section('title', 'User Registration')
@section('body-class', 'register-page')

@section('content')
  <div class="col-span-full flex items-center justify-center min-h-[70vh] py-8">
    <div class="w-full max-w-md">
      <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
          <h1 class="text-2xl font-black text-gray-800">Create Account</h1>
          <p class="text-sm text-gray-500 mt-1">Join us and start shopping today</p>
        </div>

        {{-- Alert Message Container --}}
        <div id="alert-message" class="hidden mb-4 p-4 rounded-lg text-sm font-medium" role="alert"></div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5" id="register-form">
          @csrf

          {{-- Name --}}
          <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" autofocus
              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition"
              placeholder="John Doe">
            @error('name')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Email --}}
          <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition"
              placeholder="john@example.com">
            @error('email')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Phone --}}
          <div>
            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition"
              placeholder="0123456789">
            @error('phone')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Password --}}
          <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password"
              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition"
              placeholder="••••••••">
            @error('password')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Password Confirmation --}}
          <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition"
              placeholder="••••••••">
            @error('password')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Terms & Conditions --}}
          <div>
            <div class="flex items-start">
              <input id="terms" type="checkbox" name="terms"
                class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <label for="terms" class="ml-2 text-sm text-gray-600">
                I agree to the
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Terms of Service</a>
                and
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Privacy Policy</a>
              </label>
            </div>
            @error('terms')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Submit Button --}}
          <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Create Account
          </button>
        </form>

        {{-- Divider --}}
        <div class="relative my-6">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="bg-white px-4 text-gray-400">or</span>
          </div>
        </div>

        {{-- Login Link --}}
        <p class="text-center text-sm text-gray-600">
          Already have an account?
          <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Sign in</a>
        </p>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  $(function() {
    $('#register-form').on('submit', function(e) {
      e.preventDefault();
      var form = $(this);
      var alertBox = $('#alert-message');
      var submitBtn = form.find('button[type="submit"]');
      submitBtn.prop('disabled', true).text('Creating...');

      // Hide previous alert
      alertBox.addClass('hidden').removeClass('bg-green-100 text-green-800 bg-red-100 text-red-800');

      $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: form.serialize(),
        success: function(response) {
          if (response.status) {
            alertBox.removeClass('hidden')
              .addClass('bg-green-100 text-green-800')
              .html('<span class="font-bold">Success!</span> ' + response.message);
            form[0].reset();
          }
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            var errors = xhr.responseJSON.errors;
            form.find('.text-red-500').remove();
            form.find('input').removeClass('border-red-500');

            // Show first error as alert
            var firstField = Object.keys(errors)[0];
            if (firstField) {
              alertBox.removeClass('hidden')
                .addClass('bg-red-100 text-red-800')
                .html('<span class="font-bold">Error!</span> ' + errors[firstField][0]);
            }

            // Show inline errors per field
            $.each(errors, function(field, messages) {
              var input = form.find('[name="' + field + '"]');
              input.addClass('border-red-500');
              $.each(messages, function(i, message) {
                input.closest('div').append('<p class="text-red-500 text-xs mt-1">' + message + '</p>');
              });
            });
          } else {
            alertBox.removeClass('hidden')
              .addClass('bg-red-100 text-red-800')
              .html('<span class="font-bold">Error!</span> Something went wrong. Please try again.');
          }
        },
        complete: function() {
          submitBtn.prop('disabled', false).text('Create Account');
        }
      });
    });
  });
</script>
@endpush
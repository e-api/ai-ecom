<script nonce="$2y$10$y1wzkQBMMTJ6U7of/7UFBek2ZdAMSENu59zb5moaOkdL9ZZ5.Ov7i">
  window.siteConfig = {
    csrfToken: "{{ csrf_token() }}",
    routes: {
      cartAdd: "{{ route('cart.add') }}",
      cartCount: "{{ route('cart.count') }}"
    }
  };
</script>
<script nonce="$2y$10$y1wzkQBMMTJ6U7of/7UFBek2ZdAMSENu59zb5moaOkdL9ZZ5.Ov7i" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script nonce="$2y$10$y1wzkQBMMTJ6U7of/7UFBek2ZdAMSENu59zb5moaOkdL9ZZ5.Ov7i" src="{{ asset('themes/js/site.js') }}"></script>
@stack('scripts')
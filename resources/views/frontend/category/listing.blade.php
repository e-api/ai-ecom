@extends('frontend.layouts.app')

@section('title', $category->name ?? 'Product Listing')

@section('content')

@include('frontend.category.partials.filters')

@include('frontend.category.partials.products')

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Function to get URL parameters
    function getUrlParams() {
      let params = new URLSearchParams(window.location.search);
      let prices = params.get('price');
      return prices ? prices.split(',') : [];
    }

    // Function to sync checkboxes with URL
    function syncCheckboxesWithUrl() {
        let selectedPrices = getUrlParams();
        
        $('.price-filter').each(function() {
          if (selectedPrices.includes($(this).val())) {
            $(this).prop('checked', true);
          } else {
            $(this).prop('checked', false);
          }
        });
    }

    // Sync checkboxes on page load
    syncCheckboxesWithUrl();

    // Price Filter Change
    $('.price-filter').on('change', function() {
        loadProducts();
    });

    function loadProducts() {
        let prices = [];

        $('.price-filter:checked').each(function() {
            prices.push($(this).val());
        });

        let params = new URLSearchParams();
        if (prices.length > 0) {
            params.set('price', prices.join(','));
        }

        let newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');

        window.history.pushState({}, "", newUrl);

        $.ajax({
            url: newUrl,
            type: "GET",
            beforeSend: function() {
                $('#productsSection').html('<div class="flex justify-center items-center min-h-[200px]"><p>Loading Products...</p></div>');
            },
            success: function(response) {
                $('#productsSection').html(response);
            }
        });
    }

    // Handle browser back/forward buttons
    $(window).on('popstate', function() {
        syncCheckboxesWithUrl();
        loadProducts();
    });
});
</script>
@endpush
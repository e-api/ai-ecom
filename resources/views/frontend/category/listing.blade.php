@extends('frontend.layouts.app')

@section('title', $category->name ?? 'Product Listing')

@section('content')

@include('frontend.category.partials.filters')

@include('frontend.category.partials.products')

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    function getUrlParams() {
        let params = new URLSearchParams(window.location.search);
        let prices = params.get('price');
        let categories = params.get('categories');
        return {
            prices: prices ? prices.split(',') : [],
            categories: categories ? categories.split(',') : []
        };
    }

    function syncCheckboxesWithUrl() {
        let selected = getUrlParams();
        
        $('.price-filter').each(function() {
            $(this).prop('checked', selected.prices.includes($(this).val()));
        });
        
        $('.category-filter').each(function() {
            $(this).prop('checked', selected.categories.includes($(this).val()));
        });
    }

    syncCheckboxesWithUrl();

    $('.price-filter').on('change', function() {
        loadProducts();
    });

    $('.category-filter').on('change', function() {
        loadProducts();
    });

    function loadProducts() {
        let prices = [];
        let categories = [];

        $('.price-filter:checked').each(function() {
            prices.push($(this).val());
        });

        $('.category-filter:checked').each(function() {
            categories.push($(this).val());
        });

        let params = new URLSearchParams();
        
        if (prices.length > 0) {
            params.set('price', prices.join(','));
        }
        
        if (categories.length > 0) {
            params.set('categories', categories.join(','));
        }

        let newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');

        window.history.pushState({}, "", newUrl);

        $.ajax({
            url: newUrl,
            type: "GET",
            beforeSend: function() {
                $('#productsSection').html('<div class="flex justify-center items-center min-h-[200px]"><div class="text-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div><p class="mt-2">Loading Products...</p></div></div>');
            },
            success: function(response) {
                $('#productsSection').html(response);
            }
        });
    }

    $(window).on('popstate', function() {
        syncCheckboxesWithUrl();
        loadProducts();
    });
});
</script>
@endpush
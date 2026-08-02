@extends('frontend.layouts.app')

@section('title', 'Search Results')

@section('content')

<div class="col-span-full py-8">
    <div class="container mx-auto px-4">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Search Results</h3>

        @if($keyword)
        <p class="text-gray-600 mb-2">
            Search Keyword: <strong class="text-gray-800">{{ $keyword }}</strong>
        </p>
        @endif

        <p class="text-gray-600 mb-4">
            {{ $products->count() }}
            {{ Str::plural('Product', $products->count()) }}
            Found
        </p>

        <hr class="border-gray-200 mb-6">

        <div class="related-products grid gap-3 sm:gap-6 grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse($products as $product)
            <article class="product-card relative group bg-white rounded-lg sm:rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 h-full flex flex-col">
                {{-- Image Container --}}
                <div class="product-image-container relative w-full overflow-hidden bg-gray-100 flex-shrink-0">
                    @php
                        $imageUrl = $product->image ? Storage::url($product->image) : 'https://placehold.co/400x400/e5e7eb/9ca3af?text=No+Image';
                    @endphp
                    <a href="{{ url('product/'.$product->slug) }}" class="absolute inset-0">
                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" 
                            style="background-image: url('{{ $imageUrl }}');">
                        </div>
                    </a>
                    
                    {{-- Sale Badge --}}
                    @if($product->sale_price)
                        <div class="absolute top-0 left-2 z-10">
                            <span class="bg-red-500 text-white text-[8px] sm:text-xs font-bold px-1.5 py-0.5 rounded">SALE</span>
                        </div>
                    @endif
                </div>
                
                {{-- Product Info --}}
                <div class="product-info flex flex-col flex-1 p-4">
                    <a href="{{ url('product/'.$product->slug) }}" class="card-link block">
                        <h3 class="font-bold text-gray-800 hover:text-primary transition-colors line-clamp-2">{{ $product->name }}</h3>
                    </a>
                    
                    <div class="price-container mt-auto flex items-center justify-between gap-2">
                        <div class="flex items-baseline gap-1 flex-wrap">
                            @if($product->sale_price)
                                <span class="current-price font-bold text-primary">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="old-price text-gray-400 line-through text-[9px]">${{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="current-price font-bold text-gray-800">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        
                        <a class="btn-go flex items-center justify-center transition-transform hover:scale-105" 
                          href="{{ url('product/'.$product->slug) }}">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-full">
                <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4 rounded-md">
                    <p class="font-medium">No products found.</p>
                    <p class="text-sm">Try adjusting your search terms.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
<section class="space-y-6">
      <nav class="breadcrumb-card px-4 py-3 text-sm" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-2 text-gray-600">
          <li><a class="breadcrumb-link" href="index.html">Home</a></li>
          <li aria-hidden="true">/</li>
          <li class="font-bold text-gray-900" aria-current="page">Product Listing</li>
        </ol>
      </nav>
      <div>
      
        <div class="mb-5 flex flex-col gap-3 rounded-lg border border-gray-200 bg-white p-5 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-blue-700">Collection</p>
                <h1 class="text-3xl font-black">Product Listing</h1>
            </div>
            <div class="flex items-center justify-between sm:justify-end gap-3">
            {{-- Grid/List Toggle - Hidden on mobile, visible on tablet+ --}}
                <div class="hidden sm:inline-flex rounded-md border border-gray-300 bg-white p-0.5">
                    <button class="view-toggle rounded px-2.5 py-1.5 text-xs sm:text-sm font-black active" type="button" data-view-toggle="grid" data-target="latest-products" aria-pressed="true">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    </button>
                    <button class="view-toggle rounded px-2.5 py-1.5 text-xs sm:text-sm font-black" type="button" data-view-toggle="list" data-target="latest-products" aria-pressed="false">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    </button>
                </div>
                <a class="text-xs sm:text-sm font-bold text-blue-700 whitespace-nowrap" href="{{ url('listing.html') }}">View all →</a>
            </div>
        </div>
        {{-- Products Grid - Responsive columns --}}
        <div id="category-products" data-related-products class="related-products grid gap-3 sm:gap-6 grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse($products as $product)
                <article class="product-card relative group bg-white rounded-lg sm:rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 h-full flex flex-col">
                    {{-- Image Container --}}
                    <div class="product-image-container relative w-full overflow-hidden bg-gray-100 flex-shrink-0" style="padding-top: 100%;">
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
                    <div class="product-info flex flex-col flex-1 p-2 sm:p-3">
                        <a href="{{ url('product/'.$product->slug) }}" class="card-link block">
                            <h3 class="font-bold text-gray-800 hover:text-primary transition-colors line-clamp-2 text-sm sm:text-base">
                                {{ $product->name }}
                            </h3>
                        </a>
                        
                        <div class="price-container mt-auto flex flex-wrap items-center justify-between gap-2 pt-2">
                            {{-- Price Section --}}
                            <div class="flex items-baseline gap-1 flex-wrap">
                                @if($product->sale_price)
                                    <span class="current-price font-bold text-primary text-sm sm:text-base">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="old-price text-gray-400 line-through text-[9px] sm:text-xs">${{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="current-price font-bold text-gray-800 text-sm sm:text-base">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            {{-- Buttons Container --}}
                            <div class="flex items-center gap-1 sm:gap-1">
                                {{-- View Button --}}
                                <a class="btn-go flex items-center justify-center transition-transform hover:scale-105 bg-gray-100 text-gray-700 hover:bg-primary hover:text-white rounded-md px-1 py-1 sm:px-2 sm:py-1 text-xs sm:text-xs" 
                                href="{{ url('product/'.$product->slug) }}">
                                    <i class="fa-solid fa-magnifying-glass-plus sm:mr-1"></i>
                                    <span class="hidden sm:inline">View</span>
                                </a>
                                
                                {{-- Add to Cart Button --}}
                                <a class="btn-go flex items-center justify-center transition-transform hover:scale-105 bg-gray-100 text-gray-700 hover:bg-primary hover:text-white rounded-md px-1.5 py-1 sm:px-2 sm:py-1 text-xs sm:text-xs" 
                                href="{{ url('#') }}">
                                    <i class="fa-solid fa-cart-arrow-down sm:mr-1"></i>
                                    <span class="hidden sm:inline">Add</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-8 sm:py-12">
                    <p class="text-gray-500 text-sm sm:text-base">No products found in this category.</p>
                </div>
            @endforelse
        </div>
        <nav class="mt-6 flex items-center justify-center" aria-label="Pagination">
          <ul class="inline-flex items-center gap-2 pagination">
            <li><a href="#" class="px-3 py-2 rounded-md">‹ Prev</a></li>
            <li><a href="#" class="px-3 py-2 rounded-md active">1</a></li>
            <li><a href="#" class="px-3 py-2 rounded-md">2</a></li>
            <li><a href="#" class="px-3 py-2 rounded-md">3</a></li>
            <li><a href="#" class="px-3 py-2 rounded-md">Next ›</a></li>
          </ul>
        </nav>
      </div>
    </section>
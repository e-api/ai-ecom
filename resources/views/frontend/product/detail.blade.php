@extends('frontend.layouts.app')
@section('title', $product->meta_title ?? $product->name)
@section('content')
<div class="col-span-full">
  <section class="space-y-6">
      {{-- Breadcrumb --}}
      <nav class="breadcrumb-card px-4 py-3 text-sm" aria-label="Breadcrumb">
          <ol class="flex flex-wrap items-center gap-2 text-gray-600">
              <li><a class="breadcrumb-link" href="{{ url('/') }}">Home</a></li>
              <li aria-hidden="true">/</li>
              <li><a class="breadcrumb-link" href="{{ url($product->category->slug) }}">{{ $product->category->name }}</a></li>
              <li aria-hidden="true">/</li>
              <li class="font-bold text-gray-900" aria-current="page">{{ $product->name }}</li>
          </ol>
      </nav>

      <div class="space-y-8">
          {{-- Product Main Section --}}
          <div class="grid gap-6 rounded-lg border border-gray-200 bg-white p-5 md:grid-cols-[1fr_420px_1fr] md:p-7">
              
              {{-- Product Images with Size Thumbnails --}}
              <div id="image-wrapper" class="relative">
                  {{-- Main Image --}}
                  @php $primaryImage = $product->images->where('position', 1)->first(); @endphp
                  <div class="relative overflow-hidden rounded-lg bg-gray-100 mb-3">
                      <img id="main-image" 
                          src="{{ Storage::url($primaryImage->image ?? $product->image) }}" 
                          data-zoom-src="{{ Storage::url($primaryImage->image ?? $product->image) }}"
                          alt="{{ $product->name }}"
                          class="w-full aspect-square object-cover cursor-crosshair">
                      
                      {{-- Zoom Lens --}}
                      <div id="zoom-lens" class="absolute border-2 border-primary bg-black/10 pointer-events-none" style="display: none; width: 120px; height: 120px;"></div>
                  </div>
                  
                  {{-- Zoom Result column will be displayed in the middle column on desktop --}}
                  
                  {{-- Square Size Thumbnails --}}
                  @if($product->variants->count() > 0)
                  <div class="mt-4">
                      <h3 class="text-sm font-semibold mb-2">Select Size:</h3>
                      <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-4 gap-2">
                          @foreach($product->variants as $index => $variant)
                          @php $variantImage = $product->images->where('position', $index + 1)->first(); @endphp
                          <div class="size-thumbnail cursor-pointer rounded-lg overflow-hidden border-2 {{ $loop->first ? 'border-primary' : 'border-gray-200' }} hover:border-primary transition-all" 
                              data-size="{{ $variant->size }}"
                              data-price="{{ $variant->price }}"
                              data-sku="{{ $variant->sku }}"
                              data-stock="{{ $variant->stock }}"
                              data-image="{{ $variantImage ? Storage::url($variantImage->image) : Storage::url($product->image) }}"
                              data-zoom="{{ $variantImage ? Storage::url($variantImage->image) : Storage::url($product->image) }}"
                              onclick="selectSize(this, '{{ Storage::url($variantImage ? $variantImage->image : $product->image) }}', '{{ $variant->price }}', '{{ $variant->size }}')">
                              
                              <img src="{{ $variantImage ? Storage::url($variantImage->image) : Storage::url($product->image) }}" 
                                  alt="{{ $variant->size }}"
                                  class="w-full aspect-square object-cover">
                              <div class="p-1 text-center text-xs font-medium bg-white">
                                  {{ $variant->size }}
                              </div>
                          </div>
                          @endforeach
                      </div>
                  </div>
                  @endif
              </div>

              {{-- Zoom Column (in-flow) --}}
              <div id="zoom-column" class="hidden md:flex md:items-start md:justify-center">
                  <div id="zoom-result" class="hidden z-50 overflow-hidden rounded-lg shadow-xl bg-white border border-gray-200 pointer-events-none" style="width:var(--zoom-size,420px); height:var(--zoom-size,420px);">
                      <img id="zoomed-image" src="{{ Storage::url($primaryImage->image ?? $product->image) }}" alt="{{ $product->name }}" class="absolute top-0 left-0">
                  </div>
              </div>

              {{-- Product Info --}}
              <div>
                  <p class="mb-2 text-sm font-bold uppercase tracking-wider text-blue-700">{{ $product->category->name }} / {{ $product->brand->name ?? 'General' }}</p>
                  <h1 class="text-3xl font-black tracking-tight md:text-4xl">{{ $product->name }}</h1>
                  
                  <div class="mt-3">
                      <span id="product-price" class="text-3xl font-black">${{ number_format($product->price, 2) }}</span>
                      @if($product->sale_price)
                          <span class="text-lg text-gray-400 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                      @endif
                  </div>
                  
                  <p class="mt-5 leading-7 text-gray-700">{{ $product->description }}</p>
                  
                  {{-- Selected Size Display --}}
                  <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                      <span class="text-sm text-gray-600">Selected Size:</span>
                      <span id="selected-size" class="font-semibold ml-2">{{ $product->variants->first()->size ?? 'N/A' }}</span>
                      <span class="text-sm text-gray-600 ml-4">Available:</span>
                      <span id="selected-stock" class="font-semibold">{{ $product->variants->first()->stock ?? $product->stock }}</span>
                  </div>
                  
                  <div class="mt-6 grid gap-3 sm:grid-cols-2">
                      <label class="block">
                          <span class="mb-1 block text-sm font-bold">Quantity</span>
                          <input class="form-control" type="number" id="quantity" value="1" min="1" max="{{ $product->variants->first()->stock ?? $product->stock }}">
                      </label>
                  </div>
                  
                  <div class="mt-6 flex flex-wrap gap-3">
                      <button data-cart-add class="btn-go rounded-md px-6 py-3 font-bold">Add to cart</button>
                      <a class="rounded-md border border-gray-300 bg-white px-6 py-3 font-bold text-gray-800 hover:bg-gray-50 transition" href="{{ url('cart.html') }}">View cart</a>
                  </div>
              </div>
          </div>
        {{-- Tabs Section (Description, Details, Reviews) --}}
          <section class="muted-box overflow-hidden">
              <div class="flex flex-wrap border-b border-gray-200 bg-gray-50 p-2" role="tablist" aria-label="Product information">
                  <button class="tab-button active rounded-md px-4 py-2 text-sm font-black" type="button" role="tab" aria-selected="true" data-tab-target="product-details">Product Details</button>
                  <button class="tab-button rounded-md px-4 py-2 text-sm font-black" type="button" role="tab" aria-selected="false" data-tab-target="product-description">Description</button>
                  <button class="tab-button rounded-md px-4 py-2 text-sm font-black" type="button" role="tab" aria-selected="false" data-tab-target="product-reviews">Reviews</button>
              </div>
              <div class="p-5 md:p-7">
                  {{-- Product Details Tab --}}
                  <div data-tab-panel id="product-details">
                      <h2 class="mb-4 text-2xl font-black">Product Details</h2>
                      <div class="grid gap-4 text-sm text-gray-700 md:grid-cols-2">
                          <div class="rounded-md bg-gray-50 p-4">
                              <strong class="block text-gray-950">SKU</strong>{{ $product->sku }}
                          </div>
                          <div class="rounded-md bg-gray-50 p-4">
                              <strong class="block text-gray-950">Category</strong>{{ $product->category->name }}
                          </div>
                          @if($product->brand)
                          <div class="rounded-md bg-gray-50 p-4">
                              <strong class="block text-gray-950">Brand</strong>{{ $product->brand->name }}
                          </div>
                          @endif
                          <div class="rounded-md bg-gray-50 p-4">
                              <strong class="block text-gray-950">Stock Status</strong>
                              @if($product->stock > 0)
                                  <span class="text-green-600">In Stock ({{ $product->stock }})</span>
                              @else
                                  <span class="text-red-600">Out of Stock</span>
                              @endif
                          </div>
                          @foreach($product->attributeValues as $attributeValue)
                          <div class="rounded-md bg-gray-50 p-4">
                              <strong class="block text-gray-950">{{ $attributeValue->attribute->name }}</strong>{{ $attributeValue->value }}
                          </div>
                          @endforeach
                      </div>
                  </div>

                  {{-- Description Tab --}}
                  <div class="hidden" data-tab-panel id="product-description">
                      <h2 class="mb-4 text-2xl font-black">Description</h2>
                      <div class="grid gap-6 leading-7 text-gray-700">
                          <p>{{ $product->description }}</p>
                          @if($product->short_description)
                          <div class="bg-gray-50 p-4 rounded-lg">
                              <h3 class="font-bold mb-2">Key Features</h3>
                              <p>{{ $product->short_description }}</p>
                          </div>
                          @endif
                      </div>
                  </div>

                  {{-- Reviews Tab --}}
                  <div class="hidden" data-tab-panel id="product-reviews">
                      <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                          <div>
                              <h2 class="text-2xl font-black">Customer Reviews</h2>
                              <p class="text-sm text-gray-600">Be the first to review this product.</p>
                          </div>
                          <button class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-black text-gray-800 hover:bg-gray-50 transition" type="button">Write a review</button>
                      </div>
                      <div class="text-center py-8 text-gray-500">
                          <p>No reviews yet. Be the first to review this product!</p>
                      </div>
                  </div>
              </div>
          </section>

          {{-- Related Products Section --}}
          @if($relatedProducts->count() > 0)
          <section>
              <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                  <div>
                      <h2 class="text-2xl font-black">Related Products</h2>
                      <p class="text-sm text-gray-600">More products from the same category.</p>
                  </div>
                  <div class="flex items-center gap-3">
                      <div class="rounded-md border border-gray-300 bg-white p-1">
                          <button class="view-toggle active rounded px-3 py-2 text-sm font-black" type="button" data-view-toggle="grid" data-target="related-products" aria-pressed="true">Grid</button>
                          <button class="view-toggle rounded px-3 py-2 text-sm font-black" type="button" data-view-toggle="list" data-target="related-products" aria-pressed="false">List</button>
                      </div>
                      <a class="text-link text-sm font-bold" href="{{ url($product->category->slug) }}">View all</a>
                  </div>
              </div>
                            <div class="relative">
                                    <div class="flex items-center justify-between md:hidden mb-3">
                                            <button id="related-prev" class="related-nav-btn px-3 py-2 rounded-md border bg-white shadow-sm text-lg">‹</button>
                                            <div class="text-sm text-gray-600">Related</div>
                                            <button id="related-next" class="related-nav-btn px-3 py-2 rounded-md border bg-white shadow-sm text-lg">›</button>
                                    </div>

                                    <div id="related-products" data-related-products class="related-products grid gap-3 sm:gap-6 grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                            @foreach($relatedProducts as $relatedProduct)
                                            @php $imageUrl = $relatedProduct->image ? Storage::url($relatedProduct->image) : 'https://placehold.co/400x400/e5e7eb/9ca3af?text=No+Image'; @endphp
                                            <article class="product-card relative group bg-white rounded-lg sm:rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 h-full flex flex-col">
                                                <div class="product-image-container relative w-full overflow-hidden bg-gray-100 flex-shrink-0">
                                                    <a href="{{ url('product/'.$relatedProduct->slug) }}" class="absolute inset-0">
                                                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" style="background-image: url('{{ $imageUrl }}');"></div>
                                                    </a>
                                                    @if($relatedProduct->sale_price)
                                                        <div class="absolute top-0 left-2 z-10">
                                                            <span class="bg-red-500 text-white text-[8px] sm:text-xs font-bold px-1.5 py-0.5 rounded">SALE</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="product-info flex flex-col flex-1 p-4">
                                                    <a href="{{ url('product/'.$relatedProduct->slug) }}" class="card-link block">
                                                        <h3 class="font-bold text-gray-800 hover:text-primary transition-colors line-clamp-2">{{ $relatedProduct->name }}</h3>
                                                    </a>

                                                    <div class="price-container mt-auto flex items-center justify-between gap-2">
                                                        <div class="flex items-baseline gap-1 flex-wrap">
                                                            @if($relatedProduct->sale_price)
                                                                <span class="current-price font-bold text-primary">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                                                <span class="old-price text-gray-400 line-through text-[9px]">${{ number_format($relatedProduct->price, 2) }}</span>
                                                            @else
                                                                <span class="current-price font-bold text-gray-800">${{ number_format($relatedProduct->price, 2) }}</span>
                                                            @endif
                                                        </div>

                                                        <div class="flex items-center gap-1 sm:gap-1">
                                                            <a class="btn-go flex items-center justify-center transition-transform hover:scale-105 bg-gray-100 text-gray-700 hover:bg-primary hover:text-white rounded-md px-2 py-1 text-xs" href="{{ url('product/'.$relatedProduct->slug) }}">
                                                                <i class="fa-solid fa-magnifying-glass-plus sm:mr-1"></i>
                                                                <span class="hidden sm:inline">View</span>
                                                            </a>
                                                            <button type="button" class="btn-go flex items-center justify-center transition-transform hover:scale-105 bg-gray-100 text-gray-700 hover:bg-primary hover:text-white rounded-md px-2 py-1 text-xs" data-product-id="{{ $relatedProduct->id }}" data-cart-add>
                                                                <i class="fa-solid fa-cart-arrow-down sm:mr-1"></i>
                                                                <span class="hidden sm:inline">Add</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </article>
                                            @endforeach
                                    </div>
                            </div>
          </section>
          @endif
      </div>
  </section>
</div>


<script>
function changeMainImage(imageUrl) {
    document.querySelector('.product-swatch').style.backgroundImage = "url('" + imageUrl + "')";
}

// Variant price update
document.addEventListener('DOMContentLoaded', function() {
    const variantSelect = document.getElementById('variant-size');
    if (variantSelect) {
        variantSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const stock = selectedOption.getAttribute('data-stock');
            
            // Update price using the specific ID
            const priceElement = document.getElementById('product-price');
            if (priceElement && price) {
                priceElement.innerHTML = '$' + parseFloat(price).toLocaleString(undefined, {
                    minimumFractionDigits: 2, 
                    maximumFractionDigits: 2
                });
            }
            
            // Update quantity max
            const quantityInput = document.getElementById('quantity');
            if (quantityInput && stock) {
                quantityInput.max = stock;
                if (quantityInput.value > stock) {
                    quantityInput.value = stock;
                }
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('main-image');
    const zoomLens = document.getElementById('zoom-lens');
    const zoomResult = document.getElementById('zoom-result');
    const zoomedImage = document.getElementById('zoomed-image');
    const imageWrapper = document.getElementById('image-wrapper');

    // Only enable desktop hover zoom
    if (!mainImage || !zoomLens || !zoomResult || !zoomedImage || !imageWrapper || window.innerWidth < 768) {
        if (zoomLens) zoomLens.style.display = 'none';
        if (zoomResult) { zoomResult.style.display = 'none'; zoomResult.classList.add('hidden'); }
        return;
    }

    // Preload high-res zoom image (data-zoom-src if provided)
    const initialZoomSrc = mainImage.getAttribute('data-zoom-src') || mainImage.src;
    const preloadedImg = new Image();
    preloadedImg.src = initialZoomSrc;
    preloadedImg.onload = function() {
        zoomedImage.src = initialZoomSrc;
    };
        function updateZoomTop() {
            if (!mainImage || !zoomResult) return;
            // Compute header height (fixed/sticky header) to offset sticky top
            let headerHeight = 0;
            const headerEl = document.querySelector('header, .site-header, .navbar, .topbar, .main-header'); 
            if (headerEl && (getComputedStyle(headerEl).position === 'fixed' || getComputedStyle(headerEl).position === 'sticky')) {
                headerHeight = Math.round(headerEl.getBoundingClientRect().height);
            } else {
                try {
                    const candidates = Array.from(document.querySelectorAll('body *'));
                    for (const el of candidates) {
                        const cs = getComputedStyle(el);
                        if ((cs.position === 'fixed' || cs.position === 'sticky') && Math.round(el.getBoundingClientRect().top) <= 6 && el.offsetHeight > 0) {
                            headerHeight = Math.max(headerHeight, Math.round(el.getBoundingClientRect().height));
                        }
                    }
                } catch (e) {}
            }

            const topOffset = Math.max(12, headerHeight + 8);
            zoomResult.style.setProperty('--zoom-top', topOffset + 'px');

            // Adjust zoom size responsively (desktop sizes)
            const winW = window.innerWidth || document.documentElement.clientWidth;
            let zoomW = 420;
            if (winW < 1100) zoomW = Math.round(Math.min(420, winW * 0.32));
            zoomResult.style.setProperty('--zoom-size', zoomW + 'px');
        }

        // Show zoom column on desktop, but keep the zoom box hidden until hover
        function showZoomResultIfDesktop() {
            const col = document.getElementById('zoom-column');
            if (!zoomResult || !col) return;
            if (window.innerWidth >= 768) {
                col.classList.remove('hidden');
            } else {
                col.classList.add('hidden');
                zoomResult.classList.add('hidden');
                zoomResult.style.display = 'none';
            }
        }

        // Hover show/hide with short delay so user can move pointer between image and zoom box
        let _hideTimeout = null;
        function showZoom() {
            if (!zoomResult) return;
            if (_hideTimeout) { clearTimeout(_hideTimeout); _hideTimeout = null; }
            zoomResult.classList.remove('hidden');
            zoomResult.style.display = 'block';
            zoomLens.style.display = 'block';
            updateZoomTop();
            setInitialZoom();
        }

        function hideZoom(delay = 150) {
            if (_hideTimeout) clearTimeout(_hideTimeout);
            _hideTimeout = setTimeout(() => {
                if (!zoomResult) return;
                // only hide if neither element is hovered
                if (!zoomResult.matches(':hover') && !mainImage.matches(':hover')) {
                    zoomResult.classList.add('hidden');
                    zoomResult.style.display = 'none';
                    zoomLens.style.display = 'none';
                }
                _hideTimeout = null;
            }, delay);
        }

        function setInitialZoom() {
            if (!mainImage || !zoomResult || !zoomedImage) return;
            const rect = mainImage.getBoundingClientRect();
            const lensSize = Math.max(90, Math.min(180, Math.round(rect.width * 0.14)));
            zoomLens.style.width = lensSize + 'px';
            zoomLens.style.height = lensSize + 'px';
            const lensX = Math.max(0, Math.round((rect.width - lensSize) / 2));
            const lensY = Math.max(0, Math.round((rect.height - lensSize) / 2));
            zoomLens.style.left = lensX + 'px';
            zoomLens.style.top = lensY + 'px';

            const Zw = zoomResult.offsetWidth || parseInt(getComputedStyle(zoomResult).width) || 420;
            const Zh = zoomResult.offsetHeight || Zw;

            const Wn = (zoomedImage.naturalWidth && zoomedImage.naturalWidth > 0) ? zoomedImage.naturalWidth : (preloadedImg.naturalWidth || mainImage.naturalWidth || rect.width);
            const Hn = (zoomedImage.naturalHeight && zoomedImage.naturalHeight > 0) ? zoomedImage.naturalHeight : (preloadedImg.naturalHeight || mainImage.naturalHeight || rect.height);

            const cx = Zw / lensSize;
            const cy = Zh / lensSize;

            zoomedImage.style.width = (Wn * cx) + 'px';
            zoomedImage.style.height = (Hn * cy) + 'px';

            const lensX_n = lensX * (Wn / rect.width);
            const lensY_n = lensY * (Hn / rect.height);
            zoomedImage.style.left = -(lensX_n * cx) + 'px';
            zoomedImage.style.top = -(lensY_n * cy) + 'px';
        }

        window.addEventListener('resize', function() {
            showZoomResultIfDesktop();
            updateZoomTop();
            setInitialZoom();
        }, { passive: true });

        window.addEventListener('scroll', function() {
            updateZoomTop();
        }, { passive: true });

        // Expose for external calls (e.g., selectSize)
        window.updateZoomTop = updateZoomTop;
        window.setInitialZoom = setInitialZoom;
        showZoomResultIfDesktop();
        updateZoomTop();
        setInitialZoom();

    mainImage.addEventListener('mouseenter', function() {
        // compute sizes and show zoom
        const rect = mainImage.getBoundingClientRect();
        const lensSize = Math.max(90, Math.min(180, Math.round(rect.width * 0.14)));
        zoomLens.style.width = lensSize + 'px';
        zoomLens.style.height = lensSize + 'px';
        showZoom();
    });

    mainImage.addEventListener('mouseleave', function() {
        // start hide timer (allows moving to zoom box)
        hideZoom(180);
    });

    // Keep zoom visible while hovering zoom box; hide after leaving both
    if (zoomResult) {
        zoomResult.addEventListener('mouseenter', function() {
            showZoom();
        });
        zoomResult.addEventListener('mouseleave', function() {
            hideZoom(120);
        });
    }

    mainImage.addEventListener('mousemove', function(e) {
        const rect = this.getBoundingClientRect();
        const lensWidth = parseInt(getComputedStyle(zoomLens).width) || 120;
        const lensHeight = parseInt(getComputedStyle(zoomLens).height) || lensWidth;

        let lensX = e.clientX - rect.left - lensWidth / 2;
        let lensY = e.clientY - rect.top - lensHeight / 2;

        lensX = Math.max(0, Math.min(lensX, rect.width - lensWidth));
        lensY = Math.max(0, Math.min(lensY, rect.height - lensHeight));

        zoomLens.style.left = lensX + 'px';
        zoomLens.style.top = lensY + 'px';

        const Zw = zoomResult.offsetWidth || 400;
        const Zh = zoomResult.offsetHeight || 400;

        const cx = Zw / lensWidth;
        const cy = Zh / lensHeight;

        // Prefer the zoom image natural size so the zoom isn't stretched.
        const Wn = (zoomedImage.naturalWidth && zoomedImage.naturalWidth > 0) ? zoomedImage.naturalWidth : (preloadedImg.naturalWidth || mainImage.naturalWidth || rect.width);
        const Hn = (zoomedImage.naturalHeight && zoomedImage.naturalHeight > 0) ? zoomedImage.naturalHeight : (preloadedImg.naturalHeight || mainImage.naturalHeight || rect.height);

        // Size the zoomed image based on its natural size and the zoom factor
        zoomedImage.style.width = (Wn * cx) + 'px';
        zoomedImage.style.height = (Hn * cy) + 'px';

        // Map the lens position (display coords) to the natural coords of the zoom image
        const lensX_n = lensX * (Wn / rect.width);
        const lensY_n = lensY * (Hn / rect.height);

        zoomedImage.style.left = -(lensX_n * cx) + 'px';
        zoomedImage.style.top = -(lensY_n * cy) + 'px';
    });

    
});

// Size selection function
function selectSize(element, imageUrl, price, size) {
    // Update main image
    const mainImage = document.getElementById('main-image');
    const zoomedImage = document.getElementById('zoomed-image');
    
    if (mainImage) mainImage.src = imageUrl;
    // Load a higher-res zoom image if provided via data-zoom, otherwise fallback to the same image
    const zoomSrc = element.getAttribute('data-zoom') || imageUrl;
    if (zoomedImage) {
        const preload = new Image();
        preload.src = zoomSrc;
        preload.onload = function() {
            zoomedImage.src = zoomSrc;
        };
        // set a fallback immediately
        zoomedImage.src = zoomSrc;
    }
    // Recalculate sticky top in case header/viewport changed
    if (typeof window.updateZoomTop === 'function') window.updateZoomTop();
    
    // Update price
    const priceElement = document.getElementById('product-price');
    if (priceElement) priceElement.innerHTML = '$' + parseFloat(price).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    // Update selected size display
    const selectedSizeSpan = document.getElementById('selected-size');
    if (selectedSizeSpan) selectedSizeSpan.innerHTML = size;
    
    // Update stock
    const stock = element.getAttribute('data-stock');
    const stockSpan = document.getElementById('selected-stock');
    const quantityInput = document.getElementById('quantity');
    if (stockSpan) stockSpan.innerHTML = stock;
    if (quantityInput) quantityInput.max = stock;
    
    // Update active thumbnail border
    document.querySelectorAll('.size-thumbnail').forEach(thumb => {
        thumb.classList.remove('border-primary');
        thumb.classList.add('border-gray-200');
    });
    element.classList.remove('border-gray-200');
    element.classList.add('border-primary');
}
// Mobile navigation for related products (prev/next)
(function() {
    function initRelatedNav() {
        const prev = document.getElementById('related-prev');
        const next = document.getElementById('related-next');
        const container = document.getElementById('related-products');
        if (!container) return;

        const scrollAmount = () => Math.max(200, Math.round(container.clientWidth * 0.8));

        if (prev) prev.addEventListener('click', function() {
            container.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
        });
        if (next) next.addEventListener('click', function() {
            container.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
        });

        // Hide nav controls on desktop resize
        function refreshNavVisibility() {
            const navGroup = document.getElementById('related-prev')?.parentElement;
            if (!navGroup) return;
            if (window.innerWidth >= 768) {
                navGroup.style.display = 'none';
                container.classList.remove('overflow-x-auto');
            } else {
                // if container is in carousel mode (has overflow-x-auto), show controls
                const isCarousel = container.classList.contains('overflow-x-auto');
                navGroup.style.display = isCarousel ? 'flex' : 'none';
                if (isCarousel) container.classList.add('overflow-x-auto');
            }
        }

        // Initialize view toggle (grid/list) for related products on mobile
        function initRelatedViewToggle() {
            const toggles = Array.from(document.querySelectorAll('[data-view-toggle][data-target="related-products"]'));
            if (!toggles.length) return;

            function applyMode(mode) {
                const cards = Array.from(container.querySelectorAll('.product-card'));
                // reset container mobile layout classes
                container.classList.remove('grid','grid-cols-2','gap-4','flex','flex-col','overflow-x-auto','snap-x','snap-mandatory');

                if (window.innerWidth < 768) {
                    if (mode === 'grid') {
                        container.classList.add('grid','grid-cols-2','gap-4');
                        cards.forEach(c => { c.classList.remove('min-w-[72%]','flex-shrink-0','snap-start'); c.classList.add('w-full'); });
                    } else if (mode === 'list') {
                        container.classList.add('flex','flex-col','gap-4');
                        cards.forEach(c => { c.classList.remove('min-w-[72%]','flex-shrink-0','snap-start'); c.classList.add('w-full'); });
                    } else {
                        // carousel
                        container.classList.add('flex','gap-4','overflow-x-auto','snap-x','snap-mandatory');
                        cards.forEach(c => { c.classList.add('min-w-[72%]','flex-shrink-0','snap-start'); c.classList.remove('w-full'); });
                    }
                } else {
                    // desktop: let md:grid classes control layout
                    container.classList.remove('flex','flex-col');
                    cards.forEach(c => { c.classList.remove('min-w-[72%]','flex-shrink-0','snap-start'); c.classList.remove('w-full'); });
                }

                // update nav visibility
                refreshNavVisibility();
            }

            toggles.forEach(btn => {
                btn.addEventListener('click', function() {
                    toggles.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const mode = this.getAttribute('data-view-toggle') || 'grid';
                    applyMode(mode);
                });
            });

            // apply initial active
            const active = toggles.find(b => b.classList.contains('active')) || toggles[0];
            if (active) applyMode(active.getAttribute('data-view-toggle') || 'grid');

            // Re-apply on resize to switch between mobile/desktop behaviors
            window.addEventListener('resize', function() {
                const active2 = toggles.find(b => b.classList.contains('active')) || toggles[0];
                if (active2) applyMode(active2.getAttribute('data-view-toggle') || 'grid');
            }, { passive: true });
        }

        initRelatedViewToggle();

        window.addEventListener('resize', refreshNavVisibility, { passive: true });
        refreshNavVisibility();
    }

    // Initialize when DOM loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRelatedNav);
    } else {
        initRelatedNav();
    }
})();
</script>

<style>
/* Ensure square aspect ratio for thumbnails */
.size-thumbnail {
    transition: all 0.2s ease;
}

.size-thumbnail:hover {
    transform: scale(1.02);
}

.size-thumbnail img {
    width: 100%;
    aspect-ratio: 1/1;
    object-fit: cover;
}

/* Ensure zoomed image is anchored inside result box and not constrained */
#zoom-result img { top: 0; left: 0; position: absolute; max-width: none; max-height: none; image-rendering: auto; }

/* Hide zoom on small screens as a fallback */
@media (max-width: 767px) {
    #zoom-result, #zoom-lens { display: none !important; }
}

#zoom-result {
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    background: white;
    border-radius: 8px;
    pointer-events: none;
}

#zoom-lens {
    box-shadow: 0 0 0 1px rgba(255,255,255,0.5), 0 0 0 2px rgba(0,0,0,0.12);
    background: rgba(255,255,255,0.25);
    pointer-events: none;
    position: absolute;
}

/* Related products - mobile horizontal scroll */
.related-products { scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
.related-products::-webkit-scrollbar { display: none; }
.related-nav-btn { display: inline-flex; align-items: center; justify-content: center; min-width: 40px; }

/* Hide zoom on mobile */
@media (max-width: 1023px) {
    #zoom-lens, #zoom-result {
        display: none !important;
    }
}

#zoom-result {
    position: sticky;
    top: var(--zoom-top, 20px);
    width: var(--zoom-size, 420px);
    height: var(--zoom-size, 420px);
    margin: 0 auto;
    z-index: 50;
}
</style>

@endsection
@extends('frontend.layouts.app')
@section('title', $product->meta_title ?? $product->name)
@section('content')
<div class="col-span-full">
  <section class="space-y-6">
    <div class="space-y-8">
      {{-- Product Main Section --}}
      <div class="grid gap-6 rounded-lg border border-gray-200 bg-white p-5 md:grid-cols-[1fr_620px_240px] md:p-7">
        {{-- Product Images with Size Thumbnails --}}
        <div id="image-wrapper" class="relative md:sticky md:top-16 md:self-start">
          {{-- Breadcrumb style category/brand --}}
          <p class="mb-1 text-sm text-gray-500">
            @foreach($categories as $category)
            @if($category->children->count())
            <a href="{{ url($category->slug) }}" class="text-[#565959] hover:text-[#565959] hover:underline text-[12px]">{{ $category->name }}</a>
            @endif
            @foreach($category->children as $child)
            @if($child->children->count())
            <span>›</span>
            <a href="{{ url($child->slug) }}" class="text-[#565959] hover:text-[#565959] hover:underline text-[12px]">{{ $child->name }}</a>
            @endif
            @endforeach
            @endforeach
            <span>›</span>
            <a class="text-[#565959] hover:text-[#565959] hover:underline text-[12px]">{{ $product->brand->name }}</a>
            @if($product->brand)
            <span>›</span>
            <a href="{{ url($product->category->slug) }}" class="text-[#565959] hover:text-[#565959] hover:underline text-[12px]">{{ $product->category->name }}</a>
            <span>›</span>
            @endif
            <a class="text-[#565959] hover:text-[#565959] hover:underline text-[12px]">{{ $product->name }}</a>
          </p>
          {{-- Image Gallery with thumbnail strip --}}
          @php 
            $primaryImage = $product->images->where('position', 1)->first();
            $allProductImages = $product->images->sortBy('position');
          @endphp
          {{-- Mobile: horizontal thumbnail row --}}
          <div class="flex gap-1 mb-2 overflow-x-auto md:hidden" id="image-thumb-strip-mobile">
            @php $imageCount = $allProductImages->count(); @endphp
            @if($imageCount > 0)
            <div class="thumb-item-mobile shrink-0 cursor-pointer rounded border-2 border-primary overflow-hidden w-[30px] h-[30px]"
              onclick="changeGalleryImageMobile(this, '{{ Storage::url($primaryImage->image ?? $product->image) }}')">
              <img src="{{ Storage::url($primaryImage->image ?? $product->image) }}" alt="" class="w-full h-full object-cover">
            </div>
            @endif
            @foreach($allProductImages as $img)
            @if($img->position > 1)
            <div class="thumb-item-mobile shrink-0 cursor-pointer rounded border-2 border-gray-200 overflow-hidden w-[30px] h-[30px]"
              onclick="changeGalleryImageMobile(this, '{{ Storage::url($img->image) }}')">
              <img src="{{ Storage::url($img->image) }}" alt="" class="w-full h-full object-cover">
            </div>
            @endif
            @endforeach
          </div>
          <div class="md:flex md:gap-2">
            {{-- Vertical thumbnail strip (desktop) -- hover to change main image --}}
            <div class="hidden md:flex md:flex-col md:gap-1 md:w-[30px] md:shrink-0" id="image-thumb-strip">
              @php $imageCount = $allProductImages->count(); @endphp
              @if($imageCount > 0)
              <div class="thumb-item cursor-pointer rounded border border-primary overflow-hidden hover:border-primary transition-colors"
                onmouseenter="changeGalleryImage(this, '{{ Storage::url($primaryImage->image ?? $product->image) }}', '{{ Storage::url($primaryImage->image ?? $product->image) }}')">
                <img src="{{ Storage::url($primaryImage->image ?? $product->image) }}" alt="" class="w-full aspect-square object-cover">
              </div>
              @endif
              @foreach($allProductImages as $img)
              @if($img->position > 1)
              <div class="thumb-item cursor-pointer rounded border border-gray-200 overflow-hidden hover:border-primary transition-colors"
                onmouseenter="changeGalleryImage(this, '{{ Storage::url($img->image) }}', '{{ Storage::url($img->image) }}')">
                <img src="{{ Storage::url($img->image) }}" alt="" class="w-full aspect-square object-cover">
              </div>
              @endif
              @endforeach
            </div>
            {{-- Main Image --}}
            <div class="relative overflow-hidden rounded-lg bg-gray-100 mb-3 md:mb-0 md:flex-1">
              <img id="main-image" 
                src="{{ Storage::url($primaryImage->image ?? $product->image) }}" 
                data-zoom-src="{{ Storage::url($primaryImage->image ?? $product->image) }}"
                alt="{{ $product->name }}"
                class="w-full aspect-square object-cover cursor-crosshair">
              {{-- Zoom Lens --}}
              <div id="zoom-lens" class="absolute border-2 border-primary bg-black/10 pointer-events-none" style="display: none; width: 120px; height: 120px;"></div>
            </div>
          </div>
          {{-- Zoom Result column will be displayed in the middle column on desktop --}}
          {{-- Square Size Thumbnails --}}
          @if($product->variants->count() > 0)
          <div class="mt-4 md:hidden">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 md:text-3xl">{{ $product->name }}</h1>
            {{-- Rating placeholder (Amazon-style) --}}
            <div class="flex items-center gap-2 mt-1">
              <div class="flex items-center text-yellow-400 text-sm">
                ★★★★★
              </div>
              <span class="text-sm text-blue-600 hover:text-blue-800 hover:underline cursor-pointer">113 ratings</span>
              <span class="text-gray-300">|</span>
              <span class="text-sm text-blue-600 hover:text-blue-800 hover:underline cursor-pointer">{{ $product->variants->count() }} answered questions</span>
            </div>
            <h3 class="text-sm font-semibold mb-2">Size: <span id="h3-mobile-selected-size" class="ml-1 font-medium text-gray-700">{{ $product->variants->first()->size ?? 'N/A' }}</span></h3>
            <div class="flex flex-wrap gap-2">
              @foreach($product->variants as $index => $variant)
              @php $variantImage = $product->images->where('position', $index + 1)->first(); @endphp
              <button type="button" 
                class="size-pill cursor-pointer rounded-md border px-3 py-1.5 text-sm font-medium {{ $loop->first ? 'border-primary bg-blue-50 text-blue-700' : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400' }} transition-all"
                data-size="{{ $variant->size }}"
                data-price="{{ $variant->price }}"
                data-sku="{{ $variant->sku }}"
                data-stock="{{ $variant->stock }}"
                data-image="{{ $variantImage ? Storage::url($variantImage->image) : Storage::url($product->image) }}"
                data-zoom="{{ $variantImage ? Storage::url($variantImage->image) : Storage::url($product->image) }}"
                onclick="selectSizePill(this, '{{ Storage::url($variantImage ? $variantImage->image : $product->image) }}', '{{ $variant->price }}', '{{ $variant->size }}')">
                {{ $variant->size }}
              </button>
              @endforeach
            </div>
          </div>
          @endif
          {{-- Color Variations --}}
          @if($colorVariations->count() > 0 || $product->color)
          <div class="mt-4 md:hidden">
            <h3 class="text-sm font-semibold mb-2">Available colors: <span id="h3-mobile-available-colors" class="ml-1 text-gray-700 font-medium">{{ $product->color ?? ($colorVariations->first()->color ?? '') }}</span></h3>
            <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-4 gap-2">
              @php
              $allColors = collect();
              if ($product->color) {
              $currentImageModel = $product->images->where('position', 1)->first();
              $currentImagePath = $currentImageModel ? $currentImageModel->image : $product->image;
              $allColors->push(['color' => $product->color, 'slug' => $product->slug, 'image' => $currentImagePath]);
              }
              foreach ($colorVariations as $cv) {
              $imgModel = $cv->images->where('position', 1)->first();
              $imgPath = $imgModel ? $imgModel->image : $cv->image;
              $allColors->push(['color' => $cv->color, 'slug' => $cv->slug, 'image' => $imgPath]);
              }
              // dedupe by color and sort to provide stable ordering across pages
              $allColors = $allColors->unique('color')->sortBy(function($c) { return strtolower($c['color'] ?? ''); })->values();
              @endphp
              @foreach($allColors as $cv)
              @php $imgUrl = $cv['image'] ? Storage::url($cv['image']) : 'https://placehold.co/400x400/e5e7eb/9ca3af?text='.urlencode($cv['color']); @endphp
              <a href="{{ url('product/'.$cv['slug']) }}" 
                class="color-variant block rounded-lg overflow-hidden border-2 {{ $cv['slug'] == $product->slug ? 'border-primary' : 'border-gray-200' }} hover:border-primary transition-all text-center"
                data-color="{{ $cv['color'] }}" data-image="{{ $imgUrl }}" data-zoom="{{ $imgUrl }}">
                <img src="{{ $imgUrl }}" alt="{{ $cv['color'] }}" class="w-full aspect-square object-cover">
                <div class="p-1 text-center text-xs font-medium bg-white">
                  {{ $cv['color'] }}
                </div>
              </a>
              @endforeach
            </div>
          </div>
          @endif
          {{-- Product Specs (mobile) --}}
          <div class="mt-4 md:hidden">
            <div class="rounded-lg bg-white text-sm">
                {{-- Service Provider Row (Amazon-style inline twister) --}}
                <div class="py-1">
                  <div class="flex items-center justify-between">
                    <div class="flex items-baseline gap-1">
                      <span class="text-gray-600">Service provider:</span>
                      <span class="font-bold text-gray-900">Tracfone</span>
                    </div>
                  </div>
                  <div class="mt-2">
                    <div class="flex flex-wrap gap-2">
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-gray-300 text-sm text-gray-700 bg-white hover:border-blue-500 hover:text-blue-600 cursor-pointer transition">AT&T</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-gray-300 text-sm text-gray-700 bg-white hover:border-blue-500 hover:text-blue-600 cursor-pointer transition">Boost Mobile</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-gray-300 text-sm text-gray-700 bg-white hover:border-blue-500 hover:text-blue-600 cursor-pointer transition">Cricket</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-dashed border-gray-300 text-sm text-gray-400 bg-gray-50 cursor-not-allowed">GSM Carriers</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-dashed border-gray-300 text-sm text-gray-400 bg-gray-50 cursor-not-allowed">T-Mobile</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-primary bg-blue-50 text-sm text-blue-700 font-medium cursor-pointer transition">Tracfone</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-dashed border-gray-300 text-sm text-gray-400 bg-gray-50 cursor-not-allowed">Unlocked</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-dashed border-gray-300 text-sm text-gray-400 bg-gray-50 cursor-not-allowed">Verizon</span>
                    </div>
                  </div>
                </div>
                {{-- Product Grade Row --}}
                <div class="py-1">
                  <div class="flex items-center justify-between">
                    <div class="flex items-baseline gap-1">
                      <span class="text-gray-600">Product grade:</span>
                      <span class="font-bold text-gray-900">Renewed</span>
                    </div>
                  </div>
                  <div class="mt-2">
                    <div class="flex flex-wrap gap-2">
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-primary bg-blue-50 text-sm text-blue-700 font-medium cursor-pointer transition">Renewed</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-gray-300 text-sm text-gray-700 bg-white hover:border-blue-500 hover:text-blue-600 cursor-pointer transition">Renewed Premium</span>
                    </div>
                  </div>
                </div>
                {{-- Specifications Table (Amazon-style) with collapsible rows --}}
                <div class="py-1 border-b border-gray-200">
                  <table class="w-full specs-table">
                    <tbody>
                      <tr>
                        <td class="pr-4 w-2/5 align-top py-1"><span class="font-bold text-gray-900">Brand</span></td>
                        <td class="align-top py-1"><span class="text-gray-700">Apple</span></td>
                      </tr>
                      <tr>
                        <td class="pr-4 w-2/5 align-top py-1"><span class="font-bold text-gray-900">Operating System</span></td>
                        <td class="align-top py-1"><span class="text-gray-700">iOS 16</span></td>
                      </tr>
                      <tr>
                        <td class="pr-4 w-2/5 align-top py-1"><span class="font-bold text-gray-900">Memory Storage Capacity</span></td>
                        <td class="align-top py-1"><span class="text-gray-700">128 GB</span></td>
                      </tr>
                      <tr>
                        <td class="pr-4 w-2/5 align-top py-1"><span class="font-bold text-gray-900">Model Name</span></td>
                        <td class="align-top py-1"><span class="text-gray-700">iPhone 17</span></td>
                      </tr>
                      <tr>
                        <td class="pr-4 w-2/5 align-top py-1"><span class="font-bold text-gray-900">Wireless Carrier</span></td>
                        <td class="align-top py-1"><span class="text-gray-700">Tracfone</span></td>
                      </tr>
                      <tr>
                        <td class="pr-4 w-2/5 align-top py-1"><span class="font-bold text-gray-900">Cellular Technology</span></td>
                        <td class="align-top py-1"><span class="text-gray-700">5G</span></td>
                      </tr>
                    </tbody>
                  </table>
                  {{-- Collapsible extra rows --}}
                  <div id="specs-mobile-extra" class="hidden">
                    <table class="w-full specs-table">
                      <tbody>
                        <tr>
                          <td class="pr-4 w-2/5 align-top py-1.5"><span class="font-bold text-gray-900">Color</span></td>
                          <td class="align-top py-1.5"><span class="text-gray-700">(PRODUCT) RED</span></td>
                        </tr>
                        <tr>
                          <td class="pr-4 w-2/5 align-top py-1.5"><span class="font-bold text-gray-900">Wireless network technology</span></td>
                          <td class="align-top py-1.5"><span class="text-gray-700">Wi-Fi</span></td>
                        </tr>
                        <tr>
                          <td class="pr-4 w-2/5 align-top py-1.5"><span class="font-bold text-gray-900">SIM card slot count</span></td>
                          <td class="align-top py-1.5"><span class="text-gray-700">Dual SIM</span></td>
                        </tr>
                        <tr>
                          <td class="pr-4 w-2/5 align-top py-1.5"><span class="font-bold text-gray-900">Connector Type</span></td>
                          <td class="align-top py-1.5"><span class="text-gray-700">Lightning</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <button type="button" class="specs-toggle mt-2 text-sm text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1" data-target="specs-mobile-extra">
                    <svg class="inline-block w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    <span class="toggle-text">See more</span>
                  </button>
                </div>
                {{-- About this item --}}
                <div class="py-3">
                  <h3 class="font-bold text-gray-900 mb-2">About this item</h3>
                  <ul class="list-disc ml-4 text-sm text-gray-700 space-y-1.5">
                    <li>This device is locked to TracFone only and not compatible with any other carrier.</li>
                    <li>Please check with your carrier to verify compatibility.</li>
                    <li>When you receive the phone, insert a SIM card from a compatible carrier. Then, turn it on, connect to Wi-Fi, and follow the on screen prompts to activate service.</li>
                    <li>The device does not come with headphones or a SIM card. It does include a generic (Mfi certified) charger and charging cable.</li>
                    <li>Tested for battery health and guaranteed to have a minimum battery capacity of 80%.</li>
                  </ul>
                  <div class="mt-3 space-y-1">
                    <a href="https://www.amazon.com/Apple-iPhone-13-128GB-Blue/dp/B0DK81H7QX/ref=sr_1_1?crid=37XA8CF7N12JI&dib=eyJ2IjoiMSJ9.b8TgvfBA0_M8weoe9Ko-sqy3GaGWotxgPetwwIdgCOUsQHqrLAduhNTpILr_7AnVLcvzoPmWR6BA3bxFwrKkBNOVvR871cAwQcWg2G76EaCIhWj3TlA2msAepS3ibrBpOrpUMgzsuYl-f_Ci0jD3lGodSYCrFKuBiOQMf8Yd1qI3MDv2I1NQ7VOQOUchKBOCMJ8V-eYazWhKJ3z2Yi8bK8kCl3xeyXKnZNAwZyU_8JU.cuoWSfSMcjq_AxEeKiBkIM_5NO0tY6C7kpITVI9tX9Q&dib_tag=se&keywords=iphone&qid=1780744606&sprefix=iphone%2Caps%2C382&sr=8-1&th=1#productDetails" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">› See more product details</a>
                    <br>
                    <a href="https://www.amazon.com/Apple-iPhone-13-128GB-Blue/dp/B0DK81H7QX/ref=sr_1_1?crid=37XA8CF7N12JI&dib=eyJ2IjoiMSJ9.b8TgvfBA0_M8weoe9Ko-sqy3GaGWotxgPetwwIdgCOUsQHqrLAduhNTpILr_7AnVLcvzoPmWR6BA3bxFwrKkBNOVvR871cAwQcWg2G76EaCIhWj3TlA2msAepS3ibrBpOrpUMgzsuYl-f_Ci0jD3lGodSYCrFKuBiOQMf8Yd1qI3MDv2I1NQ7VOQOUchKBOCMJ8V-eYazWhKJ3z2Yi8bK8kCl3xeyXKnZNAwZyU_8JU.cuoWSfSMcjq_AxEeKiBkIM_5NO0tY6C7kpITVI9tX9Q&dib_tag=se&keywords=iphone&qid=1780744606&sprefix=iphone%2Caps%2C382&sr=8-1&th=1#" target="_blank" rel="noopener noreferrer" class="text-xs text-gray-500 hover:text-gray-700 hover:underline">Report an issue with this product or seller</a>
                  </div>
                </div>
              </div>
          </div>
        </div>
        {{-- Zoom Column (in-flow) --}}
        <div id="zoom-column" class="hidden md:flex md:items-start md:justify-center">
          <div id="zoom-result" class="hidden z-50 overflow-hidden rounded-lg shadow-xl bg-white border border-gray-200 pointer-events-none" style="width:calc(var(--zoom-size, 620px) + 77px); height:calc(var(--zoom-size,620px) + 200px);">
            <img id="zoomed-image" src="{{ Storage::url($primaryImage->image ?? $product->image) }}" alt="{{ $product->name }}" class="absolute top-0 left-0">
          </div>
          <div class="w-full max-w-full space-y-3">
            <div class="mt-2 hidden md:block">
              <h1 class="text-2xl font-bold tracking-tight text-gray-900 md:text-3xl">{{ $product->name }}</h1>
              {{-- Rating placeholder (Amazon-style) --}}
              <div class="flex items-center gap-2 mt-1">
                <div class="flex items-center text-yellow-400 text-sm">
                  ★★★★★
                </div>
                <span class="text-sm text-blue-600 hover:text-blue-800 hover:underline cursor-pointer">113 ratings</span>
                <span class="text-gray-300">|</span>
                <span class="text-sm text-blue-600 hover:text-blue-800 hover:underline cursor-pointer">{{ $product->variants->count() }} answered questions</span>
              </div>
              {{-- Price --}}
              <div class="mt-3 border-t border-gray-200 pt-3">
                @if($product->sale_price)
                <div class="flex items-baseline gap-2">
                  <span class="text-xs text-gray-500">List Price:</span>
                  <span class="text-sm text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                </div>
                @endif
                <div class="flex items-baseline gap-2">
                  <span class="text-xs text-gray-500">{{ $product->sale_price ? 'Sale Price:' : 'Price:' }}</span>
                  <span id="product-price" class="text-2xl font-bold text-gray-900">${{ number_format($product->sale_price ?? $product->price, 2) }}</span>
                </div>
                <div class="flex items-center gap-1 mt-1">
                  <span class="text-sm text-gray-600">FREE delivery</span>
                  <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::now()->addDays(rand(3, 7))->format('l, F d') }}</span>
                </div>
                <p class="text-sm text-gray-600 mt-1">Or fastest delivery <span class="font-medium text-gray-900">{{ \Carbon\Carbon::now()->addDays(rand(1, 2))->format('l, F d') }}</span></p>
              </div>
              {{-- Description snippet --}}
              <p class="mt-3 text-sm leading-6 text-gray-700">{{ $product->short_description ?? $product->description }}</p>
            </div>
            @if($product->variants->count() > 0)
            <div id="zoom-size-thumbnails" class="mt-2 hidden md:block">
              <h3 class="text-sm font-semibold mb-2">Size: <span id="h3-zoom-selected-size" class="ml-1 font-medium text-gray-700">{{ $product->variants->first()->size ?? 'N/A' }}</span></h3>
              <div class="flex flex-wrap gap-2">
                @foreach($product->variants as $index => $variant)
                @php $variantImage = $product->images->where('position', $index + 1)->first(); @endphp
                <button type="button" 
                  class="size-pill cursor-pointer rounded-md border px-3 py-1.5 text-sm font-medium {{ $loop->first ? 'border-primary bg-blue-50 text-blue-700' : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400' }} transition-all"
                  data-size="{{ $variant->size }}"
                  data-price="{{ $variant->price }}"
                  data-sku="{{ $variant->sku }}"
                  data-stock="{{ $variant->stock }}"
                  data-image="{{ $variantImage ? Storage::url($variantImage->image) : Storage::url($product->image) }}"
                  data-zoom="{{ $variantImage ? Storage::url($variantImage->image) : Storage::url($product->image) }}"
                  onclick="selectSizePill(this, '{{ Storage::url($variantImage ? $variantImage->image : $product->image) }}', '{{ $variant->price }}', '{{ $variant->size }}')">
                  {{ $variant->size }}
                </button>
                @endforeach
              </div>
            </div>
            @endif
            @if($colorVariations->count() > 0 || $product->color)
            <div id="zoom-color-variants" class="mt-2 hidden md:block">
              <h3 class="text-sm font-semibold mb-2">Available colors: <span id="h3-zoom-available-colors" class="ml-1 text-gray-700 font-medium">{{ $product->color ?? ($colorVariations->first()->color ?? '') }}</span></h3>
              <div class="grid grid-cols-8 gap-1">
                  @php
                  $allColors = collect();
                  if ($product->color) {
                      $currentImageModel = $product->images->where('position', 1)->first();
                      $currentImagePath = $currentImageModel ? $currentImageModel->image : $product->image;
                      $allColors->push(['color' => $product->color, 'slug' => $product->slug, 'image' => $currentImagePath]);
                  }
                  foreach ($colorVariations as $cv) {
                      $imgModel = $cv->images->where('position', 1)->first();
                      $imgPath = $imgModel ? $imgModel->image : $cv->image;
                      $allColors->push(['color' => $cv->color, 'slug' => $cv->slug, 'image' => $imgPath]);
                  }
                  $allColors = $allColors->unique('color')->sortBy(function($c) { return strtolower($c['color'] ?? ''); })->values();
                  @endphp
                  @foreach($allColors as $cv)
                  @php $imgUrl = $cv['image'] ? Storage::url($cv['image']) : 'https://placehold.co/400x400/e5e7eb/9ca3af?text='.urlencode($cv['color']); @endphp
                  <a href="{{ url('product/'.$cv['slug']) }}" 
                    class="color-variant block rounded-md overflow-hidden border {{ $cv['slug'] == $product->slug ? 'border-primary' : 'border-gray-200' }} hover:border-primary transition-all text-center"
                    data-color="{{ $cv['color'] }}" data-image="{{ $imgUrl }}" data-zoom="{{ $imgUrl }}">
                    <img src="{{ $imgUrl }}" alt="{{ $cv['color'] }}" class="w-full aspect-square object-cover">
                    <div class="py-0.5 text-center text-[9px] font-medium bg-white truncate">
                        {{ Str::limit($cv['color'], 6) }}
                    </div>
                  </a>
                  @endforeach
              </div>
            </div>
            @endif
            {{-- Product Specs (desktop, under variation selection) --}}
            <div id="product-specs" class="mt-4 hidden md:block">
              <div class="rounded-lg bg-white text-sm">
                {{-- Service Provider Row (Amazon-style inline twister) --}}
                <div class="py-1">
                  <div class="flex items-center justify-between">
                    <div class="flex items-baseline gap-1">
                      <span class="text-gray-600">Service provider:</span>
                      <span class="font-bold text-gray-900">Tracfone</span>
                    </div>
                  </div>
                  <div class="mt-2">
                    <div class="flex flex-wrap gap-2">
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-gray-300 text-sm text-gray-700 bg-white hover:border-blue-500 hover:text-blue-600 cursor-pointer transition">AT&T</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-gray-300 text-sm text-gray-700 bg-white hover:border-blue-500 hover:text-blue-600 cursor-pointer transition">Boost Mobile</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-gray-300 text-sm text-gray-700 bg-white hover:border-blue-500 hover:text-blue-600 cursor-pointer transition">Cricket</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-dashed border-gray-300 text-sm text-gray-400 bg-gray-50 cursor-not-allowed">GSM Carriers</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-dashed border-gray-300 text-sm text-gray-400 bg-gray-50 cursor-not-allowed">T-Mobile</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-primary bg-blue-50 text-sm text-blue-700 font-medium cursor-pointer transition">Tracfone</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-dashed border-gray-300 text-sm text-gray-400 bg-gray-50 cursor-not-allowed">Unlocked</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-dashed border-gray-300 text-sm text-gray-400 bg-gray-50 cursor-not-allowed">Verizon</span>
                    </div>
                  </div>
                </div>
                {{-- Product Grade Row --}}
                <div class="py-1">
                  <div class="flex items-center justify-between">
                    <div class="flex items-baseline gap-1">
                      <span class="text-gray-600">Product grade:</span>
                      <span class="font-bold text-gray-900">Renewed</span>
                    </div>
                  </div>
                  <div class="mt-2">
                    <div class="flex flex-wrap gap-2">
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-primary bg-blue-50 text-sm text-blue-700 font-medium cursor-pointer transition">Renewed</span>
                      <span class="inline-flex items-center px-1 py-1.5 rounded-md border border-gray-300 text-sm text-gray-700 bg-white hover:border-blue-500 hover:text-blue-600 cursor-pointer transition">Renewed Premium</span>
                    </div>
                  </div>
                </div>
                {{-- Specifications Table (Amazon-style) with collapsible rows --}}
                <div class="py-1 border-b border-gray-200">
                  <table class="w-full specs-table">
                    <tbody>
                      <tr>
                        <td class="pr-4 w-2/5 align-top py-1"><span class="font-bold text-gray-900">Brand</span></td>
                        <td class="align-top py-1"><span class="text-gray-700">{{ $product->brand->name }}</span></td>
                      </tr>
                      <tr>
                        <td class="pr-4 w-2/5 align-top py-1"><span class="font-bold text-gray-900">Operating System</span></td>
                        <td class="align-top py-1"><span class="text-gray-700">iOS 16</span></td>
                      </tr>
                      <tr>
                        @if($product->variants->count() > 0)
                        <td class="pr-4 w-2/5 align-top py-1"><span class="font-bold text-gray-900">Memory Storage Capacity</span></td>
                        <td class="align-top py-1"><span class="text-gray-700">{{ $product->variants->first()->size ?? 'N/A' }}</span></td>
                        @endif
                      </tr>
                      <tr>
                        <td class="pr-4 w-2/5 align-top py-1"><span class="font-bold text-gray-900">Model Name</span></td>
                        <td class="align-top py-1"><span class="text-gray-700">iPhone 17</span></td>
                      </tr>
                      <tr>
                        <td class="pr-4 w-2/5 align-top py-1"><span class="font-bold text-gray-900">Wireless Carrier</span></td>
                        <td class="align-top py-1"><span class="text-gray-700">Tracfone</span></td>
                      </tr>
                      <tr>
                        <td class="pr-4 w-2/5 align-top py-1"><span class="font-bold text-gray-900">Cellular Technology</span></td>
                        <td class="align-top py-1"><span class="text-gray-700">5G</span></td>
                      </tr>
                    </tbody>
                  </table>
                  {{-- Collapsible extra rows --}}
                  <div id="specs-desktop-extra" class="hidden">
                    <table class="w-full specs-table">
                      <tbody>
                        <tr>
                          <td class="pr-4 w-2/5 align-top py-1.5"><span class="font-bold text-gray-900">Color</span></td>
                          @if($colorVariations->count() > 0 || $product->color)
                          <td class="align-top py-1.5"><span class="text-gray-700">{{ $product->color ?? ($colorVariations->first()->color ?? '') }}</span></td>
                          @endif
                        </tr>
                        <tr>
                          <td class="pr-4 w-2/5 align-top py-1.5"><span class="font-bold text-gray-900">Wireless network technology</span></td>
                          <td class="align-top py-1.5"><span class="text-gray-700">Wi-Fi</span></td>
                        </tr>
                        <tr>
                          <td class="pr-4 w-2/5 align-top py-1.5"><span class="font-bold text-gray-900">SIM card slot count</span></td>
                          <td class="align-top py-1.5"><span class="text-gray-700">Dual SIM</span></td>
                        </tr>
                        <tr>
                          <td class="pr-4 w-2/5 align-top py-1.5"><span class="font-bold text-gray-900">Connector Type</span></td>
                          <td class="align-top py-1.5"><span class="text-gray-700">Lightning</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <button type="button" class="specs-toggle mt-2 text-sm text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1" data-target="specs-desktop-extra">
                    <svg class="inline-block w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    <span class="toggle-text">See more</span>
                  </button>
                </div>
                {{-- About this item --}}
                <div class="py-3">
                  <h3 class="font-bold text-gray-900 mb-2">About this item</h3>
                  <ul class="list-disc ml-4 text-sm text-gray-700 space-y-1.5">
                    <li>This device is locked to TracFone only and not compatible with any other carrier.</li>
                    <li>Please check with your carrier to verify compatibility.</li>
                    <li>When you receive the phone, insert a SIM card from a compatible carrier. Then, turn it on, connect to Wi-Fi, and follow the on screen prompts to activate service.</li>
                    <li>The device does not come with headphones or a SIM card. It does include a generic (Mfi certified) charger and charging cable.</li>
                    <li>Tested for battery health and guaranteed to have a minimum battery capacity of 80%.</li>
                  </ul>
                  <div class="mt-3 space-y-1">
                    <a href="https://www.amazon.com/Apple-iPhone-13-128GB-Blue/dp/B0DK81H7QX/ref=sr_1_1?crid=37XA8CF7N12JI&dib=eyJ2IjoiMSJ9.b8TgvfBA0_M8weoe9Ko-sqy3GaGWotxgPetwwIdgCOUsQHqrLAduhNTpILr_7AnVLcvzoPmWR6BA3bxFwrKkBNOVvR871cAwQcWg2G76EaCIhWj3TlA2msAepS3ibrBpOrpUMgzsuYl-f_Ci0jD3lGodSYCrFKuBiOQMf8Yd1qI3MDv2I1NQ7VOQOUchKBOCMJ8V-eYazWhKJ3z2Yi8bK8kCl3xeyXKnZNAwZyU_8JU.cuoWSfSMcjq_AxEeKiBkIM_5NO0tY6C7kpITVI9tX9Q&dib_tag=se&keywords=iphone&qid=1780744606&sprefix=iphone%2Caps%2C382&sr=8-1&th=1#productDetails" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">› See more product details</a>
                    <br>
                    <a href="https://www.amazon.com/Apple-iPhone-13-128GB-Blue/dp/B0DK81H7QX/ref=sr_1_1?crid=37XA8CF7N12JI&dib=eyJ2IjoiMSJ9.b8TgvfBA0_M8weoe9Ko-sqy3GaGWotxgPetwwIdgCOUsQHqrLAduhNTpILr_7AnVLcvzoPmWR6BA3bxFwrKkBNOVvR871cAwQcWg2G76EaCIhWj3TlA2msAepS3ibrBpOrpUMgzsuYl-f_Ci0jD3lGodSYCrFKuBiOQMf8Yd1qI3MDv2I1NQ7VOQOUchKBOCMJ8V-eYazWhKJ3z2Yi8bK8kCl3xeyXKnZNAwZyU_8JU.cuoWSfSMcjq_AxEeKiBkIM_5NO0tY6C7kpITVI9tX9Q&dib_tag=se&keywords=iphone&qid=1780744606&sprefix=iphone%2Caps%2C382&sr=8-1&th=1#" target="_blank" rel="noopener noreferrer" class="text-xs text-gray-500 hover:text-gray-700 hover:underline">Report an issue with this product or seller</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- Product Info --}}
        <div class="border border-gray-200 rounded-xl p-4 bg-white shadow-sm md:self-start">
          {{-- Price --}}
          <div class="mt-1 border-b border-gray-200 py-3">
            @if($product->sale_price)
            <div class="flex items-baseline gap-2">
              <span class="text-xs text-gray-500">List Price:</span>
              <span class="text-sm text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
            </div>
            @endif
            <div class="flex items-baseline gap-2">
              <span class="text-xs text-gray-500">{{ $product->sale_price ? 'Sale Price:' : 'Price:' }}</span>
              <span id="product-price" class="text-2xl font-bold text-gray-900">${{ number_format($product->sale_price ?? $product->price, 2) }}</span>
            </div>
            <div class="flex items-center gap-1 mt-1">
              <span class="text-sm text-gray-600">FREE delivery</span>
              <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::now()->addDays(rand(3, 7))->format('l, F d') }}</span>
            </div>
            <p class="text-sm text-gray-600 mt-1">Or fastest delivery <span class="font-medium text-gray-900">{{ \Carbon\Carbon::now()->addDays(rand(1, 2))->format('l, F d') }}</span></p>
          </div>
          {{-- Description snippet --}}
          <p class="mt-3 text-sm leading-6 text-gray-700">{{ $product->short_description ?? $product->description }}</p>
          {{-- Size & Stock info --}}
          <div class="mt-3 flex items-center gap-4 text-sm">
            <div>
              <span class="text-gray-500">Size:</span>
              <span id="selected-size" class="font-semibold text-gray-900 ml-1">{{ $product->variants->first()->size ?? 'N/A' }}</span>
            </div>
            <div>
              <span class="text-gray-500">Stock:</span>
              <span id="selected-stock" class="font-semibold {{ ($product->variants->first()->stock ?? $product->stock) > 0 ? 'text-green-600' : 'text-red-600' }} ml-1">{{ $product->variants->first()->stock ?? $product->stock }}</span>
            </div>
          </div>
          {{-- Quantity with +/- buttons (Amazon-style) --}}
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity:</label>
            <div class="inline-flex items-center border border-gray-300 rounded-md overflow-hidden">
              <button type="button" onclick="decrementQty()" class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition border-r border-gray-300 text-lg font-medium select-none">−</button>
              <input type="number" id="quantity" value="1" min="1" max="{{ $product->variants->first()->stock ?? $product->stock }}" 
                class="w-14 h-9 text-center text-sm font-medium text-gray-900 border-0 focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
              <button type="button" onclick="incrementQty()" class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition border-l border-gray-300 text-lg font-medium select-none">+</button>
            </div>
          </div>
          {{-- Action Buttons --}}
          <div class="mt-5 flex flex-col gap-2">
            <div id="action-buttons-wrapper" class="flex flex-col gap-2" data-initial-stock="{{ $product->variants->first()->stock ?? $product->stock }}">
              <button id="btn-add-to-cart" data-cart-add type="button" class="w-full rounded-full bg-yellow-400 hover:bg-yellow-500 px-6 py-3 font-bold text-sm text-gray-900 transition shadow-sm">Add to Cart</button>
              <button id="btn-buy-now" type="button" class="w-full rounded-full bg-orange-500 hover:bg-orange-600 px-6 py-3 font-bold text-sm text-white transition shadow-sm">Buy Now</button>
            </div>
            <div id="stock-message" class="text-sm text-gray-600" style="display:none;">Currently unavailable — we don’t know when or if this item will be back in stock.</div>
          </div>
          {{-- Extra info --}}
          <div class="mt-4 text-xs text-gray-500 space-y-1">
            <p><span class="font-medium text-gray-700">Ships from:</span> AI Ecom Store</p>
            <p><span class="font-medium text-gray-700">Sold by:</span> AI Ecom Store</p>
            @if($product->sku)
            <p><span class="font-medium text-gray-700">SKU:</span> {{ $product->sku }}</p>
            @endif
          </div>
        </div>
      </div>
      {{-- From the manufacturer (current Amazon style) --}}
      <div id="product-description" class="border-b border-gray-200 pb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Product Description</h2>
        <div class="text-sm leading-6 text-gray-700 space-y-3">
          <div id="desc-text" class="line-clamp-4 overflow-hidden transition-all duration-300">
            <p>{{ $product->description }}</p>
            @if($product->short_description)
            <div class="mt-3 bg-gray-50 rounded-lg p-4">
              <h3 class="font-bold text-gray-900 text-sm mb-1.5">Key Features</h3>
              <ul class="space-y-1">
                @foreach(explode("\n", $product->short_description) as $feature)
                @if(trim($feature))
                <li class="flex items-start gap-2">
                  <svg class="w-4 h-4 text-green-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                  <span>{{ trim($feature) }}</span>
                </li>
                @endif
                @endforeach
              </ul>
            </div>
            @endif
          </div>
          <button type="button" id="desc-toggle" class="text-sm text-blue-600 hover:text-blue-800 hover:underline font-medium inline-flex items-center gap-1" onclick="toggleDescription()">
            <span id="desc-toggle-text">See more</span>
            <svg class="w-3 h-3 transition-transform duration-200" id="desc-toggle-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
        </div>
      </div>

      {{-- Product Information (Amazon-style collapsible dropdowns - compact) --}}
      <div id="product-details" class="border-b border-gray-200 pb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Product information</h2>
        
        <div class="product-info-grid">
            <!-- LEFT COLUMN -->
            <div class="rounded-lg border border-gray-200 overflow-hidden divide-y divide-gray-200">
                <!-- Display & Hardware section -->
                <div>
                    <button type="button" class="product-info-toggle w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left" onclick="toggleProductInfo(this)">
                        <span class="text-sm font-bold text-gray-900">Display & Hardware</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="product-info-content">
                        <div class="divide-y divide-gray-100">
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Screen Size</span><span class="w-3/5 text-sm text-gray-700">6.3 inches</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">Resolution</span><span class="w-3/5 text-sm text-gray-700">1206 x 2622</span></div>
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Refresh Rate</span><span class="w-3/5 text-sm text-gray-700">120 Hz</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">Display Type</span><span class="w-3/5 text-sm text-gray-700">OLED</span></div>
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Pixel Density</span><span class="w-3/5 text-sm text-gray-700">460 PPI</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">Operating System</span><span class="w-3/5 text-sm text-gray-700">iOS 17</span></div>
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Processor</span><span class="w-3/5 text-sm text-gray-700">Apple A18 Pro</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">Processor Speed</span><span class="w-3/5 text-sm text-gray-700">6 GHz</span></div>
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">RAM</span><span class="w-3/5 text-sm text-gray-700">256 GB</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">Storage</span><span class="w-3/5 text-sm text-gray-700">256 GB</span></div>
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Color</span><span class="w-3/5 text-sm text-gray-700">Desert Titanium</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">Connector</span><span class="w-3/5 text-sm text-gray-700">USB Type C</span></div>
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Form Factor</span><span class="w-3/5 text-sm text-gray-700">Bar</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">SIM</span><span class="w-3/5 text-sm text-gray-700">eSIM</span></div>
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Water Resistance</span><span class="w-3/5 text-sm text-gray-700">Not Water Resistant</span></div>
                        </div>
                    </div>
                </div>
                
                <!-- Battery & Dimensions -->
                <div>
                    <button type="button" class="product-info-toggle w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left" onclick="toggleProductInfo(this)">
                        <span class="text-sm font-bold text-gray-900">Battery & Dimensions</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="product-info-content">
                        <div class="divide-y divide-gray-100">
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Battery</span><span class="w-3/5 text-sm text-gray-700">3582 mAh</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">Dimensions</span><span class="w-3/5 text-sm text-gray-700">5.89 x 2.81 x 0.32 inches</span></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- RIGHT COLUMN -->
            <div class="rounded-lg border border-gray-200 overflow-hidden divide-y divide-gray-200">
                <!-- Connectivity -->
                <div>
                    <button type="button" class="product-info-toggle w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left" onclick="toggleProductInfo(this)">
                        <span class="text-sm font-bold text-gray-900">Connectivity</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="product-info-content">
                        <div class="divide-y divide-gray-100">
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Wireless Provider</span><span class="w-3/5 text-sm text-gray-700">Unlocked for All Carriers</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">Cellular Technology</span><span class="w-3/5 text-sm text-gray-700">5G</span></div>
                        </div>
                    </div>
                </div>
                
                <!-- Item Details -->
                <div>
                    <button type="button" class="product-info-toggle w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left" onclick="toggleProductInfo(this)">
                        <span class="text-sm font-bold text-gray-900">Item Details</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="product-info-content">
                        <div class="divide-y divide-gray-100">
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Brand</span><span class="w-3/5 text-sm text-gray-700">Apple</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">Model Year</span><span class="w-3/5 text-sm text-gray-700">2024</span></div>
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Built-In Media</span><span class="w-3/5 text-sm text-gray-700">Apple iPhone 16 Pro, USB Cable</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">Warranty</span><span class="w-3/5 text-sm text-gray-700">1 Year Amazon Renewed Guarantee</span></div>
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Manufacturer</span><span class="w-3/5 text-sm text-gray-700">Apple</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">UPC</span><span class="w-3/5 text-sm text-gray-700">724129131017</span></div>
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">ASIN</span><span class="w-3/5 text-sm text-gray-700">B0DNTC3HXX</span></div>
                        </div>
                    </div>
                </div>
                
                <!-- Customer Feedback -->
                <div>
                    <button type="button" class="product-info-toggle w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left" onclick="toggleProductInfo(this)">
                        <span class="text-sm font-bold text-gray-900">Customer Feedback</span>
                        <svg class="w-3 h-3 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="product-info-content">
                        <div class="divide-y divide-gray-100">
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Customer Reviews</span><span class="w-3/5 text-sm text-gray-700">4.3 out of 5 stars (811 reviews)</span></div>
                            <div class="flex px-4 py-2.5 bg-gray-50"><span class="w-2/5 text-sm font-bold text-gray-900">Lower Price Feedback</span><span class="w-3/5 text-sm text-gray-700">Yes ✔</span></div>
                            <div class="flex px-4 py-2.5"><span class="w-2/5 text-sm font-bold text-gray-900">Best Sellers Rank</span><span class="w-3/5 text-sm text-gray-700">#680 in Cell Phones & Accessories, #9 in Renewed Smartphones, #13 in Cell Phones</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>

      {{-- Customer Reviews (current Amazon style 2025+) --}}
      <div id="product-reviews" class="pb-8">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-bold text-gray-900">Customer Reviews</h2>
          <a href="#" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">How customer reviews and ratings work</a>
        </div>
        <div class="grid md:grid-cols-[280px_1fr] gap-8">
          {{-- Rating summary sidebar --}}
          <div class="rounded-lg border border-gray-200 p-5">
            <div class="flex items-center gap-3 mb-3">
              <div class="text-4xl font-bold text-gray-900 leading-none">0</div>
              <div>
                <div class="flex items-center text-yellow-400 text-sm">
                  ★☆☆☆☆
                </div>
                <p class="text-xs text-gray-500 mt-0.5">0 global ratings</p>
              </div>
            </div>
            {{-- Rating bars --}}
            <div class="space-y-1.5">
              <div class="flex items-center gap-2 text-sm">
                <span class="text-yellow-400 w-3 shrink-0 text-xs">★</span>
                <span class="text-gray-600 w-12 text-xs">5 star</span>
                <div class="flex-1 h-2.5 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-yellow-400 rounded-full" style="width: 0%"></div>
                </div>
                <span class="text-gray-500 w-5 text-xs text-right">0</span>
                <span class="text-gray-400 text-xs">%</span>
              </div>
              <div class="flex items-center gap-2 text-sm">
                <span class="text-yellow-400 w-3 shrink-0 text-xs">★</span>
                <span class="text-gray-600 w-12 text-xs">4 star</span>
                <div class="flex-1 h-2.5 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-yellow-400 rounded-full" style="width: 0%"></div>
                </div>
                <span class="text-gray-500 w-5 text-xs text-right">0</span>
                <span class="text-gray-400 text-xs">%</span>
              </div>
              <div class="flex items-center gap-2 text-sm">
                <span class="text-yellow-400 w-3 shrink-0 text-xs">★</span>
                <span class="text-gray-600 w-12 text-xs">3 star</span>
                <div class="flex-1 h-2.5 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-yellow-400 rounded-full" style="width: 0%"></div>
                </div>
                <span class="text-gray-500 w-5 text-xs text-right">0</span>
                <span class="text-gray-400 text-xs">%</span>
              </div>
              <div class="flex items-center gap-2 text-sm">
                <span class="text-yellow-400 w-3 shrink-0 text-xs">★</span>
                <span class="text-gray-600 w-12 text-xs">2 star</span>
                <div class="flex-1 h-2.5 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-yellow-400 rounded-full" style="width: 0%"></div>
                </div>
                <span class="text-gray-500 w-5 text-xs text-right">0</span>
                <span class="text-gray-400 text-xs">%</span>
              </div>
              <div class="flex items-center gap-2 text-sm">
                <span class="text-yellow-400 w-3 shrink-0 text-xs">★</span>
                <span class="text-gray-600 w-12 text-xs">1 star</span>
                <div class="flex-1 h-2.5 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-yellow-400 rounded-full" style="width: 0%"></div>
                </div>
                <span class="text-gray-500 w-5 text-xs text-right">0</span>
                <span class="text-gray-400 text-xs">%</span>
              </div>
            </div>
            <hr class="my-4 border-gray-200">
            <a href="#" class="block text-center text-sm text-blue-600 hover:text-blue-800 hover:underline font-medium">Write a review</a>
          </div>
          {{-- Review CTA section (current Amazon style) --}}
          <div>
            <div class="rounded-lg border border-gray-200 p-6 text-center">
              <svg class="mx-auto h-8 w-8 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
              </svg>
              <p class="text-sm font-medium text-gray-900 mb-1">Share your thoughts with other customers</p>
              <p class="text-xs text-gray-500 mb-4">Be the first to review this product</p>
              <button class="rounded-full border border-gray-300 bg-white px-6 py-2 text-sm font-bold text-gray-800 hover:bg-gray-50 transition" type="button">Write a customer review</button>
            </div>
          </div>
        </div>
      </div>
      {{-- Related Products Section (current Amazon style 2025+) --}}
      @if($relatedProducts->count() > 0)
      <section class="border-t border-gray-200 pt-8">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-bold text-gray-900">Customers who viewed this also viewed</h2>
          <a class="text-sm text-blue-600 hover:text-blue-800 hover:underline font-medium" href="{{ url($product->category->slug) }}">See more ›</a>
        </div>
        <div class="relative">
          {{-- Carousel container --}}
          <div id="related-products" class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide snap-x snap-mandatory scroll-smooth">
            @foreach($relatedProducts as $relatedProduct)
            @php $imageUrl = $relatedProduct->image ? Storage::url($relatedProduct->image) : 'https://placehold.co/400x400/e5e7eb/9ca3af?text=No+Image'; @endphp
            <a href="{{ url('product/'.$relatedProduct->slug) }}" class="flex-shrink-0 w-[160px] sm:w-[180px] snap-start group">
              <div class="relative w-full aspect-square bg-gray-100 rounded-lg overflow-hidden mb-2">
                <img src="{{ $imageUrl }}" 
                  alt="{{ $relatedProduct->name }}"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  loading="lazy">
                @if($relatedProduct->sale_price)
                <div class="absolute top-1.5 left-1.5">
                  <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">SALE</span>
                </div>
                @endif
              </div>
              <h3 class="text-sm text-gray-800 font-medium line-clamp-2 mb-1 leading-snug group-hover:text-blue-600 transition-colors">{{ $relatedProduct->name }}</h3>
              <div class="flex items-center gap-1 mb-0.5">
                <div class="flex text-yellow-400 text-[11px]">★★★★★</div>
                <span class="text-[11px] text-gray-500">({{ rand(10, 500) }})</span>
              </div>
              <div class="flex items-baseline gap-1 flex-wrap">
                @if($relatedProduct->sale_price)
                <span class="font-bold text-sm text-gray-900">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                <span class="text-xs text-gray-400 line-through">${{ number_format($relatedProduct->price, 2) }}</span>
                @else
                <span class="font-bold text-sm text-gray-900">${{ number_format($relatedProduct->price, 2) }}</span>
                @endif
              </div>
            </a>
            @endforeach
          </div>
          {{-- Scroll arrows (desktop only) --}}
          <button id="related-scroll-left" class="hidden md:flex absolute left-0 top-[35%] -translate-x-3 w-9 h-9 rounded-full bg-white border border-gray-200 shadow-sm items-center justify-center text-gray-600 hover:text-gray-900 hover:shadow-md hover:border-gray-300 transition-all z-10" type="button" aria-label="Scroll left">‹</button>
          <button id="related-scroll-right" class="hidden md:flex absolute right-0 top-[35%] translate-x-3 w-9 h-9 rounded-full bg-white border border-gray-200 shadow-sm items-center justify-center text-gray-600 hover:text-gray-900 hover:shadow-md hover:border-gray-300 transition-all z-10" type="button" aria-label="Scroll right">›</button>
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
          // Convert display coordinates (within the <img> element) to natural image coordinates,
          // accounting for CSS object-fit/object-position (cover, contain, fill).
          function parseObjectPosition(pos) {
              const parts = (pos || '50% 50%').split(/\s+/);
              const xToken = parts[0] || '50%';
              const yToken = parts[1] || '50%';
              function tokenToFraction(tok) {
                  tok = tok.trim();
                  if (tok === 'left' || tok === 'top') return 0;
                  if (tok === 'center') return 0.5;
                  if (tok === 'right' || tok === 'bottom') return 1;
                  if (tok.endsWith('%')) return parseFloat(tok) / 100;
                  return 0.5;
              }
              return { x: tokenToFraction(xToken), y: tokenToFraction(yToken) };
          }
  
          function displayToNatural(displayX, displayY, rect, Wn, Hn) {
              const cs = getComputedStyle(mainImage);
              const objectFit = (cs.objectFit || cs.getPropertyValue('object-fit') || '').toLowerCase();
              const objectPosition = cs.objectPosition || cs.getPropertyValue('object-position') || '50% 50%';
              // Fallback to simple mapping if natural sizes are not available
              const fallbackW = (Wn && Wn > 0) ? Wn : rect.width;
              const fallbackH = (Hn && Hn > 0) ? Hn : rect.height;
              if (!Wn || !Hn || Wn <= 0 || Hn <= 0) {
                  return { x: (displayX / Math.max(rect.width, 1)) * fallbackW, y: (displayY / Math.max(rect.height, 1)) * fallbackH };
              }
  
              let scale;
              if (objectFit === 'contain') {
                  scale = Math.min(rect.width / Wn, rect.height / Hn);
              } else if (objectFit === 'fill') {
                  // stretched independently on each axis
                  const scaleX = rect.width / Wn;
                  const scaleY = rect.height / Hn;
                  const parsed = parseObjectPosition(objectPosition);
                  const renderedW = Wn * scaleX;
                  const renderedH = Hn * scaleY;
                  const overflowX = Math.max(0, renderedW - rect.width);
                  const overflowY = Math.max(0, renderedH - rect.height);
                  const offsetX = overflowX * parsed.x;
                  const offsetY = overflowY * parsed.y;
                  return { x: (displayX + offsetX) / scaleX, y: (displayY + offsetY) / scaleY };
              } else {
                  // default to 'cover' behavior (also handles '' / unknown)
                  scale = Math.max(rect.width / Wn, rect.height / Hn);
              }
  
              const renderedW = Wn * scale;
              const renderedH = Hn * scale;
              const parsed = parseObjectPosition(objectPosition);
              const overflowX = Math.max(0, renderedW - rect.width);
              const overflowY = Math.max(0, renderedH - rect.height);
              const offsetX = overflowX * parsed.x;
              const offsetY = overflowY * parsed.y;
  
              const naturalX = (displayX + offsetX) / scale;
              const naturalY = (displayY + offsetY) / scale;
              return { x: naturalX, y: naturalY };
          }
  
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
              let zoomW = 560;
              if (winW < 1100) zoomW = Math.round(Math.min(560, winW * 0.32));
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
              const lensSize = Math.max(120, Math.min(180, Math.round(rect.width * 0.14)));
              zoomLens.style.width = lensSize + 'px';
              zoomLens.style.height = lensSize + 'px';
              const lensX = Math.max(0, Math.round((rect.width - lensSize) / 2));
              const lensY = Math.max(0, Math.round((rect.height - lensSize) / 2));
              zoomLens.style.left = lensX + 'px';
              zoomLens.style.top = lensY + 'px';
  
              const Zw = zoomResult.offsetWidth || parseInt(getComputedStyle(zoomResult).width) || 620;
              const Zh = zoomResult.offsetHeight || Zw;
  
              const Wn = (zoomedImage.naturalWidth && zoomedImage.naturalWidth > 0) ? zoomedImage.naturalWidth : (preloadedImg.naturalWidth || mainImage.naturalWidth || rect.width);
              const Hn = (zoomedImage.naturalHeight && zoomedImage.naturalHeight > 0) ? zoomedImage.naturalHeight : (preloadedImg.naturalHeight || mainImage.naturalHeight || rect.height);
  
              // Prefer the lens-based zoom (Zw/lens) but ensure the zoomed image covers the
              // zoom box. If the natural image is too small, increase factor but cap it to avoid excessive zoom.
              const safeWn = Math.max(1, Wn);
              const safeHn = Math.max(1, Hn);
              const cx = Zw / Math.max(1, lensSize);
              const cy = Zh / Math.max(1, lensSize);
              let zoomFactor = Math.max(cx, cy);
              if (safeWn * zoomFactor < Zw || safeHn * zoomFactor < Zh) {
                  zoomFactor = Math.max(zoomFactor, Zw / safeWn, Zh / safeHn);
              }
              const MAX_ZOOM = 1;
              zoomFactor = Math.min(zoomFactor, MAX_ZOOM);
  
              zoomedImage.style.width = Math.round(safeWn * zoomFactor) + 'px';
              zoomedImage.style.height = Math.round(safeHn * zoomFactor) + 'px';
  
              // Center the lens area in the zoom box and clamp to avoid empty space.
              const centerX = lensX + lensSize / 2;
              const centerY = lensY + lensSize / 2;
              const centerNatural = displayToNatural(centerX, centerY, rect, Wn, Hn);
              const centerX_n = Math.max(0, Math.min(centerNatural.x, safeWn));
              const centerY_n = Math.max(0, Math.min(centerNatural.y, safeHn));
  
              const zoomedImageWidth = Math.round(safeWn * zoomFactor);
              const zoomedImageHeight = Math.round(safeHn * zoomFactor);
  
              let left = Math.round((Zw / 2) - (centerX_n * zoomFactor));
              let top = Math.round((Zh / 2) - (centerY_n * zoomFactor));
  
              const leftMin = Math.min(0, Zw - zoomedImageWidth);
              const topMin = Math.min(0, Zh - zoomedImageHeight);
              left = Math.max(leftMin, Math.min(0, left));
              top = Math.max(topMin, Math.min(0, top));
  
              zoomedImage.style.left = left + 'px';
              zoomedImage.style.top = top + 'px';
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
          const lensSize = Math.max(120, Math.min(180, Math.round(rect.width * 0.14)));
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
  
          const Zw = zoomResult.offsetWidth || 800;
          const Zh = zoomResult.offsetHeight || 800;
  
          // Prefer the zoom image natural size so the zoom isn't stretched.
          const Wn = (zoomedImage.naturalWidth && zoomedImage.naturalWidth > 0) ? zoomedImage.naturalWidth : (preloadedImg.naturalWidth || mainImage.naturalWidth || rect.width);
          const Hn = (zoomedImage.naturalHeight && zoomedImage.naturalHeight > 0) ? zoomedImage.naturalHeight : (preloadedImg.naturalHeight || mainImage.naturalHeight || rect.height);
  
          const safeWn = Math.max(1, Wn);
          const safeHn = Math.max(1, Hn);
          const cx = Zw / Math.max(1, lensWidth);
          const cy = Zh / Math.max(1, lensHeight);
          let zoomFactor = Math.max(cx, cy);
          if (safeWn * zoomFactor < Zw || safeHn * zoomFactor < Zh) {
              zoomFactor = Math.max(zoomFactor, Zw / safeWn, Zh / safeHn);
          }
          const MAX_ZOOM = 1;
          zoomFactor = Math.min(zoomFactor, MAX_ZOOM);
  
          // Size the zoomed image based on its natural size and the uniform zoom factor
          const zoomedImageWidth = Math.round(safeWn * zoomFactor);
          const zoomedImageHeight = Math.round(safeHn * zoomFactor);
          zoomedImage.style.width = zoomedImageWidth + 'px';
          zoomedImage.style.height = zoomedImageHeight + 'px';
  
          // Map the lens center (display coords) to natural coords and center it in the zoom box.
          const centerX = lensX + (lensWidth / 2);
          const centerY = lensY + (lensHeight / 2);
          const centerNatural = displayToNatural(centerX, centerY, rect, Wn, Hn);
          const centerX_n = Math.max(0, Math.min(centerNatural.x, safeWn));
          const centerY_n = Math.max(0, Math.min(centerNatural.y, safeHn));
  
          let left = Math.round((Zw / 2) - (centerX_n * zoomFactor));
          let top = Math.round((Zh / 2) - (centerY_n * zoomFactor));
  
          const leftMin = Math.min(0, Zw - zoomedImageWidth);
          const topMin = Math.min(0, Zh - zoomedImageHeight);
          left = Math.max(leftMin, Math.min(0, left));
          top = Math.max(topMin, Math.min(0, top));
  
          zoomedImage.style.left = left + 'px';
          zoomedImage.style.top = top + 'px';
      });
  
      
  });
  
  // Quantity +/- functions
  function decrementQty() {
      const qty = document.getElementById('quantity');
      const val = parseInt(qty.value);
      if (val > parseInt(qty.min || 1)) {
          qty.value = val - 1;
      }
  }
  
  function incrementQty() {
      const qty = document.getElementById('quantity');
      const val = parseInt(qty.value);
      const max = parseInt(qty.max || 999);
      if (val < max) {
          qty.value = val + 1;
      }
  }
  
  // Mobile image gallery: change main image from horizontal thumbnail strip (no zoom)
  function changeGalleryImageMobile(el, imgUrl) {
      var mainImage = document.getElementById('main-image');
      if (mainImage) mainImage.src = imgUrl;
      // Update active thumbnail border
      document.querySelectorAll('.thumb-item-mobile').forEach(function(t) {
          t.classList.remove('border-primary');
          t.classList.add('border-gray-200');
      });
      el.classList.remove('border-gray-200');
      el.classList.add('border-primary');
  }
  
  // Desktop image gallery: change main image from thumbnail strip
  function changeGalleryImage(el, imgUrl, zoomUrl) {
      var mainImage = document.getElementById('main-image');
      var zoomedImage = document.getElementById('zoomed-image');
      if (mainImage) mainImage.src = imgUrl;
      if (zoomedImage) {
          zoomedImage.src = zoomUrl;
          var preload = new Image();
          preload.src = zoomUrl;
          preload.onload = function() { zoomedImage.src = zoomUrl; };
      }
      // Update active thumbnail border
      document.querySelectorAll('.thumb-item').forEach(function(t) {
          t.classList.remove('border-primary');
          t.classList.add('border-gray-200');
      });
      el.classList.remove('border-gray-200');
      el.classList.add('border-primary');
  }
  
  // Size selection via pill buttons (Amazon-style) - only updates price/size/stock, NOT the image
  function selectSizePill(element, imageUrl, price, size) {
      // Update price
      var priceElement = document.getElementById('product-price');
      if (priceElement) priceElement.innerHTML = '$' + parseFloat(price).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
      
      // Update selected size labels
      var sizeLabel = document.getElementById('h3-selected-size-label');
      if (sizeLabel) sizeLabel.textContent = size;
      var selectedSizeSpan = document.getElementById('selected-size');
      if (selectedSizeSpan) selectedSizeSpan.innerHTML = size;
      var h3ZoomSelected = document.getElementById('h3-zoom-selected-size');
      if (h3ZoomSelected) h3ZoomSelected.textContent = size;
      var h3Mobile = document.getElementById('h3-mobile-selected-size');
      if (h3Mobile) h3Mobile.textContent = size;
      
      // Update stock
      var stock = element.getAttribute('data-stock');
      var stockSpan = document.getElementById('selected-stock');
      var quantityInput = document.getElementById('quantity');
      if (stockSpan) stockSpan.innerHTML = stock;
      if (quantityInput) quantityInput.max = stock;
      if (typeof updateActionButtons === 'function') updateActionButtons(stock);
      
      // Update active pill styling
      document.querySelectorAll('.size-pill').forEach(function(p) {
          p.classList.remove('border-primary', 'bg-blue-50', 'text-blue-700');
          p.classList.add('border-gray-300', 'bg-white', 'text-gray-700');
      });
      element.classList.remove('border-gray-300', 'bg-white', 'text-gray-700');
      element.classList.add('border-primary', 'bg-blue-50', 'text-blue-700');
  }
  
  // Size selection function (legacy)
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
      
      // Update selected size display (product info + H3s)
      const selectedSizeSpan = document.getElementById('selected-size');
      if (selectedSizeSpan) selectedSizeSpan.innerHTML = size;
      const h3ZoomSelected = document.getElementById('h3-zoom-selected-size');
      if (h3ZoomSelected) h3ZoomSelected.textContent = size;
      const h3MobileSelected = document.getElementById('h3-mobile-selected-size');
      if (h3MobileSelected) h3MobileSelected.textContent = size;
      
      // Update stock
      const stock = element.getAttribute('data-stock');
      const stockSpan = document.getElementById('selected-stock');
      const quantityInput = document.getElementById('quantity');
      if (stockSpan) stockSpan.innerHTML = stock;
      if (quantityInput) quantityInput.max = stock;
      // Update action buttons based on stock
      if (typeof updateActionButtons === 'function') updateActionButtons(stock);
      
      // Update active thumbnail border
      document.querySelectorAll('.size-thumbnail').forEach(thumb => {
          thumb.classList.remove('border-primary');
          thumb.classList.add('border-gray-200');
      });
      element.classList.remove('border-gray-200');
      element.classList.add('border-primary');
  }
  // Toggle Add/Buy buttons and unavailable state based on stock
  function updateActionButtons(stock) {
      const addBtn = document.getElementById('btn-add-to-cart');
      const buyBtn = document.getElementById('btn-buy-now');
      const unBtn = document.getElementById('btn-unavailable');
      const qty = document.getElementById('quantity');
      const stockMsg = document.getElementById('stock-message');
      let s = parseInt(stock, 10);
      if (isNaN(s)) s = 0;
  
      if (s <= 0) {
          if (addBtn) { addBtn.style.display = 'none'; addBtn.disabled = true; addBtn.setAttribute('aria-disabled', 'true'); }
          if (buyBtn) { buyBtn.style.display = 'none'; buyBtn.disabled = true; buyBtn.setAttribute('aria-disabled', 'true'); }
          if (unBtn) { unBtn.style.display = 'block'; }
          if (stockMsg) { stockMsg.style.display = 'block'; }
          if (qty) { qty.disabled = true; qty.value = 0; }
      } else {
          if (addBtn) { addBtn.style.display = ''; addBtn.disabled = false; addBtn.setAttribute('aria-disabled', 'false'); }
          if (buyBtn) { buyBtn.style.display = ''; buyBtn.disabled = false; buyBtn.setAttribute('aria-disabled', 'false'); }
          if (unBtn) { unBtn.style.display = 'none'; }
          if (stockMsg) { stockMsg.style.display = 'none'; }
          if (qty) { qty.disabled = false; if (!qty.value || parseInt(qty.value,10) === 0) qty.value = 1; qty.max = s; if (parseInt(qty.value,10) > s) qty.value = s; }
      }
  }
  
  // Initialize action buttons from initial data attribute
  (function() {
      const wrapper = document.getElementById('action-buttons-wrapper');
      if (wrapper) {
          const initialStock = wrapper.getAttribute('data-initial-stock');
          if (typeof updateActionButtons === 'function') updateActionButtons(initialStock);
      }
  })();
  // Handle color tile clicks: prevent navigation, mark active, update main/zoom image and H3s
  (function() {
      function onColorSelect(el) {
          if (!el) return;
          // get color label
          const lbl = el.querySelector('.p-1') || el.lastElementChild;
          const color = lbl ? lbl.textContent.trim() : (el.getAttribute('data-color') || '').trim();
  
          // update H3 displays
          const h3ZoomColors = document.getElementById('h3-zoom-available-colors');
          const h3MobileColors = document.getElementById('h3-mobile-available-colors');
          if (h3ZoomColors) h3ZoomColors.textContent = color;
          if (h3MobileColors) h3MobileColors.textContent = color;
  
          // toggle active class without moving DOM nodes
          document.querySelectorAll('.color-variant').forEach(n => n.classList.remove('border-primary'));
          el.classList.add('border-primary');
  
          // Update main + zoom images from the clicked tile
          const img = el.querySelector('img');
          const mainImage = document.getElementById('main-image');
          const zoomedImage = document.getElementById('zoomed-image');
          const src = img ? img.src : (el.getAttribute('data-image') || '');
          const zoomSrc = el.getAttribute('data-zoom') || src;
          if (mainImage && src) mainImage.src = src;
          if (zoomedImage && zoomSrc) {
              const preload = new Image();
              preload.src = zoomSrc;
              preload.onload = function() { zoomedImage.src = zoomSrc; };
              zoomedImage.src = zoomSrc;
          }
      }
  
      const nodes = Array.from(document.querySelectorAll('.color-variant'));
      nodes.forEach(n => {
          n.addEventListener('click', function(e) {
              // If this node is an anchor (or inside an anchor), allow normal navigation.
              const anchor = (n.tagName && n.tagName.toLowerCase() === 'a') ? n : n.closest && n.closest('a');
              if (anchor && anchor.href) {
                  // update visual state but do not prevent navigation
                  onColorSelect(n);
                  return;
              }
              // non-anchor tiles: prevent default and handle in-place
              if (e && typeof e.preventDefault === 'function') e.preventDefault();
              onColorSelect(n);
          });
          n.addEventListener('keydown', function(e) {
              if (e.key === 'Enter' || e.key === ' ') {
                  const anchor = (n.tagName && n.tagName.toLowerCase() === 'a') ? n : n.closest && n.closest('a');
                  if (anchor && anchor.href) {
                      // allow keyboard-activated navigation on anchors
                      onColorSelect(n);
                      return;
                  }
                  if (e && typeof e.preventDefault === 'function') e.preventDefault();
                  onColorSelect(n);
              }
          });
      });
  })();
  // Initialize H3 selected labels on load
  (function() {
      // Initialize H3 selected labels on load
      const sel = document.getElementById('selected-size');
      const h3Zoom = document.getElementById('h3-zoom-selected-size');
      if (h3Zoom && sel && sel.textContent) h3Zoom.textContent = sel.textContent.trim();
      const h3Mobile = document.getElementById('h3-mobile-selected-size');
      if (h3Mobile && sel && sel.textContent) h3Mobile.textContent = sel.textContent.trim();
  
      // Populate available colors preferring thumbnails in the zoom column (avoids duplicates)
      let colorNodes = Array.from(document.querySelectorAll('#zoom-color-variants .color-variant'));
      if (!colorNodes || colorNodes.length === 0) {
          // fallback to visible color-variant elements (mobile)
          colorNodes = Array.from(document.querySelectorAll('.color-variant')).filter(n => n.offsetParent !== null);
      }
  
      // Prefer the currently active color tile (server marks it with border-primary). If present, show only that color.
      const activeColorNode = document.querySelector('#zoom-color-variants .color-variant.border-primary') || document.querySelector('.color-variant.border-primary');
      let colorOutput = '';
      if (activeColorNode) {
          const lbl = activeColorNode.querySelector('.p-1') || activeColorNode.lastElementChild;
          colorOutput = lbl ? lbl.textContent.trim() : (activeColorNode.getAttribute('data-color') || '').trim();
      } else {
          const colors = Array.from(new Set(colorNodes.map(n => {
              const label = n.querySelector('.p-1') || n.lastElementChild;
              return label ? label.textContent.trim() : (n.getAttribute('data-color') || '').trim();
          }).filter(Boolean)));
          colorOutput = colors.join(', ');
      }
  
      const h3ZoomColors = document.getElementById('h3-zoom-available-colors');
      if (h3ZoomColors) h3ZoomColors.textContent = colorOutput;
      const h3MobileColors = document.getElementById('h3-mobile-available-colors');
      if (h3MobileColors) h3MobileColors.textContent = colorOutput;
  })();
  // Related Products Carousel: scroll left/right arrows
  document.addEventListener('DOMContentLoaded', function() {
      const container = document.getElementById('related-products');
      const scrollLeft = document.getElementById('related-scroll-left');
      const scrollRight = document.getElementById('related-scroll-right');
      if (!container) return;
  
      function doScroll(amount) {
          container.scrollBy({ left: amount, behavior: 'smooth' });
      }
  
      if (scrollLeft) {
          scrollLeft.addEventListener('click', function() {
              doScroll(-Math.round(container.clientWidth * 0.7));
          });
      }
      if (scrollRight) {
          scrollRight.addEventListener('click', function() {
              doScroll(Math.round(container.clientWidth * 0.7));
          });
      }
  
      // Show/hide arrows based on scroll position on desktop
      function updateArrowVisibility() {
          if (!scrollLeft || !scrollRight) return;
          if (window.innerWidth < 768) {
              scrollLeft.classList.add('hidden');
              scrollRight.classList.add('hidden');
              return;
          }
          scrollLeft.classList.remove('hidden');
          scrollRight.classList.remove('hidden');
      }
  
      container.addEventListener('scroll', updateArrowVisibility, { passive: true });
      window.addEventListener('resize', updateArrowVisibility, { passive: true });
      updateArrowVisibility();
  });
  
  // Toggle product description expand/collapse
  function toggleDescription() {
      var descText = document.getElementById('desc-text');
      var toggleText = document.getElementById('desc-toggle-text');
      var chevron = document.getElementById('desc-toggle-chevron');
      if (!descText) return;
      var isExpanded = !descText.classList.contains('line-clamp-4');
      descText.classList.toggle('line-clamp-4');
      toggleText.textContent = isExpanded ? 'See more' : 'See less';
      if (chevron) {
          chevron.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(180deg)';
      }
  }
  
  // Product Information section toggle: expand/collapse
  function toggleProductInfo(btn) {
      var content = btn.nextElementSibling;
      var svg = btn.querySelector('svg');
      if (!content) return;
      var isHidden = (getComputedStyle(content).display === 'none' || content.style.display === 'none');
      content.style.display = isHidden ? 'block' : 'none';
      if (svg) {
          svg.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
          svg.style.transition = 'transform 0.2s ease';
      }
  }
  
  // Specs toggle: expand/collapse mobile/desktop spec sections
  document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.specs-toggle').forEach(function(btn) {
          btn.addEventListener('click', function(e) {
              e.preventDefault();
              var targetId = this.getAttribute('data-target');
              var target = document.getElementById(targetId);
              if (!target) return;
              
              var isHidden = (getComputedStyle(target).display === 'none' || target.style.display === 'none');
              target.style.display = isHidden ? 'block' : 'none';
              
              // Find the text span inside the button
              var textSpan = this.querySelector('.toggle-text');
              if (textSpan) {
                  textSpan.textContent = isHidden ? 'See less' : 'See more';
              }
              
              // Rotate the SVG chevron
              var svg = this.querySelector('svg');
              if (svg) {
                  svg.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
                  svg.style.transition = 'transform 0.2s ease';
              }
          });
      });
  });
  
</script>
<style>
  /* Ensure square aspect ratio for thumbnails */
  .size-thumbnail {
  transition: all 0.2s ease;
  }
  .size-thumbnail:hover {
  transform: scale(1.02);
  }
  .color-variant {
  transition: all 0.2s ease;
  }
  .color-variant img {
  width: 100%;
  aspect-ratio: 1/1;
  object-fit: cover;
  }
  /* Active state for size thumbnails and color variants */
  .size-thumbnail.border-primary,
  .color-variant.border-primary {
  box-shadow: 0 0 0 2px var(--primary-hover);
  border-color: var(--primary-hover) !important;
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
  position: absolute;
  pointer-events: none;
  border: transparent;
  background-color: transparent;
  background-image: radial-gradient(
  rgba(255, 140, 0, 0.35) 0.5px,
  transparent 1px
  );
  background-size: 3px 3px;
  box-shadow: none;
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
  width: calc(var(--zoom-size, 620px) + 77px);
  height: calc(var(--zoom-size, 620px) + 200px);
  margin: 0 auto;
  z-index: 50;
  }
  /* Desktop: overlay the zoom result so it doesn't push thumbnails aside */
  @media (min-width: 768px) {
  #zoom-column { position: relative; }
  #zoom-column .space-y-3 { position: relative; }
  #zoom-column #zoom-result {
  position: absolute;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  margin: 0;
  z-index: 80;
  pointer-events: none;
  }
  }
  .product-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    align-items: start;  /* THIS FIXES THE ISSUE - prevents columns from stretching to match height */
}

@media (max-width: 768px) {
    .product-info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
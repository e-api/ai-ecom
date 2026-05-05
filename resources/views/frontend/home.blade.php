@extends('frontend.layouts.app')

@section('title','Home')

@section('content')
  <!-- Carousel (copied from public index) -->
  <div class="carousel col-span-full w-full mx-auto" aria-roledescription="carousel">
    <div class="carousel-track" aria-live="polite">
      <div class="carousel-slide" role="group" aria-roledescription="slide" aria-label="1 of 3" style="background-image: url('https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1600&q=80&auto=format&fit=crop');">
        <div class="overlay"></div>
        <div class="slide-content px-6 py-12 lg:py-20 max-w-4xl mx-auto text-center">
          <p class="text-sm font-bold uppercase tracking-wider text-white/90">New season essentials</p>
          <h2 class="mt-3 text-3xl md:text-5xl font-black text-white">Everyday essentials for every day</h2>
          <p class="mt-4 text-white/90">Comfortable, sustainable pieces for your everyday wardrobe.</p>
          <div class="mt-6">
            <a class="btn-go rounded-md px-5 py-3 font-bold" href="{{ url('listing.html') }}">Shop collection</a>
          </div>
        </div>
      </div>

      <div class="carousel-slide" role="group" aria-roledescription="slide" aria-label="2 of 3" style="background-image: url('https://images.unsplash.com/photo-1512436991641-6745cdb1723f?w=1600&q=80&auto=format&fit=crop');">
        <div class="overlay"></div>
        <div class="slide-content px-6 py-12 lg:py-20 max-w-4xl mx-auto text-center">
          <p class="text-sm font-bold uppercase tracking-wider text-white/90">Trending now</p>
          <h2 class="mt-3 text-3xl md:text-5xl font-black text-white">Lightweight layers for spring</h2>
          <p class="mt-4 text-white/90">Shop light jackets and breathable tees</p>
          <div class="mt-6">
            <a class="btn-go rounded-md px-5 py-3 font-bold" href="{{ url('listing.html') }}">Browse</a>
          </div>
        </div>
      </div>

      <div class="carousel-slide" role="group" aria-roledescription="slide" aria-label="3 of 3" style="background-image: url('https://images.unsplash.com/photo-1520975911874-8e7a3ca2e0d8?w=1600&q=80&auto=format&fit=crop');">
        <div class="overlay"></div>
        <div class="slide-content px-6 py-12 lg:py-20 max-w-4xl mx-auto text-center">
          <p class="text-sm font-bold uppercase tracking-wider text-white/90">Limited Offer</p>
          <h2 class="mt-3 text-3xl md:text-5xl font-black text-white">Up to 30% off select styles</h2>
          <p class="mt-4 text-white/90">Grab your favorites while stocks last.</p>
          <div class="mt-6">
            <a class="btn-go rounded-md px-5 py-3 font-bold" href="{{ url('listing.html') }}">Shop sale</a>
          </div>
        </div>
      </div>
    </div>

    <button class="carousel-arrow left" aria-label="Previous slide">‹</button>
    <button class="carousel-arrow right" aria-label="Next slide">›</button>

    <div class="carousel-dots" role="tablist" aria-label="Carousel dots">
      <button class="carousel-dot" role="tab" aria-selected="true"></button>
      <button class="carousel-dot" role="tab" aria-selected="false"></button>
      <button class="carousel-dot" role="tab" aria-selected="false"></button>
    </div>
  </div>

  <section class="space-y-6 lg:sticky lg:top-28 lg:self-start">
    <div class="sidebar-cart-card px-4 py-3 text-sm font-semibold text-gray-700">Cart icon: 3 items in your cart</div>
    <aside class="store-sidebar p-4">
      <section class="mb-6">
        <h2 class="sidebar-title mb-2">MEN</h2>
        <a class="category-link" href="{{ url('listing.html') }}">T-Shirts <span>24</span></a>
        <a class="category-link" href="{{ url('listing.html') }}">Casual T-Shirts <span>18</span></a>
        <a class="category-link" href="{{ url('listing.html') }}">Formal T-Shirts <span>9</span></a>
        <a class="category-link" href="{{ url('listing.html') }}">Shirts <span>31</span></a>
        <a class="category-link" href="{{ url('listing.html') }}">Casual Shirts <span>14</span></a>
        <a class="category-link" href="{{ url('listing.html') }}">Formal Shirts <span>12</span></a>
      </section>
      <section class="mb-6">
        <h2 class="sidebar-title mb-2">WOMEN</h2>
        <a class="category-link" href="{{ url('listing.html') }}">Tops <span>28</span></a>
        <a class="category-link" href="{{ url('listing.html') }}">Casual Tops <span>19</span></a>
        <a class="category-link" href="{{ url('listing.html') }}">Formal Tops <span>7</span></a>
        <a class="category-link" href="{{ url('listing.html') }}">Dresses <span>35</span></a>
        <a class="category-link" href="{{ url('listing.html') }}">Casual Dresses <span>16</span></a>
        <a class="category-link" href="{{ url('listing.html') }}">Formal Dresses <span>10</span></a>
      </section>
      <section>
        <h2 class="sidebar-title mb-2">KIDS</h2>
        <a class="category-link" href="{{ url('listing.html') }}">T-Shirts <span>15</span></a>
        <a class="category-link" href="{{ url('listing.html') }}">Shirts <span>11</span></a>
        <a class="category-link" href="{{ url('listing.html') }}">Dresses <span>13</span></a>
      </section>
    </aside>
  </section>

  <section class="space-y-8">
    <div class="rounded-lg border border-gray-200 bg-white p-6 md:p-8">
      <p class="mb-2 text-sm font-bold uppercase tracking-wider text-blue-700">New season essentials</p>
      <h1 class="max-w-3xl text-3xl font-black tracking-tight text-gray-950 md:text-5xl">Clean everyday apparel for men, women, and kids.</h1>
      <p class="mt-4 max-w-2xl text-gray-600">Browse structured categories, quick product cards, and simple checkout pages built as a responsive Tailwind storefront.</p>
      <div class="mt-6 flex flex-wrap gap-3">
        <a class="btn-go rounded-md px-5 py-3 font-bold" href="{{ url('listing.html') }}">Shop collection</a>
        <a class="rounded-md border border-gray-300 bg-white px-5 py-3 font-bold text-gray-800" href="{{ url('about.html') }}">About us</a>
      </div>
    </div>

    <div>
      <div class="mb-4 flex items-end justify-between gap-4">
        <div>
          <h2 class="text-2xl font-black">Featured Products</h2>
          <p class="text-sm text-gray-600">Flat product cards with modern spacing and responsive grids.</p>
        </div>
        <div class="flex items-center gap-3">
          <div class="inline-flex items-center gap-2">
            <button data-view-toggle="grid" class="view-toggle active" aria-pressed="true">Grid</button>
            <button data-view-toggle="list" class="view-toggle" aria-pressed="false">List</button>
          </div>
          <a class="text-sm font-bold text-blue-700" href="{{ url('listing.html') }}">View all</a>
        </div>
      </div>
      <div data-related-products class="related-products grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <article class="product-card overflow-hidden">
          <div class="product-swatch h-52 bg-gray-200"></div>
          <div class="p-4"><h3 class="font-black">Classic T-Shirt</h3><p class="mt-1 text-sm text-gray-600">Soft cotton basic for daily wear.</p><div class="mt-4 flex items-center justify-between"><span class="font-black">$24.00</span><a class="btn-go rounded-md px-4 py-2 text-sm font-bold" href="{{ url('detail.html') }}">View</a></div></div>
        </article>
        <article class="product-card overflow-hidden">
          <div class="product-swatch h-52 bg-slate-300"></div>
          <div class="p-4"><h3 class="font-black">Formal Shirt</h3><p class="mt-1 text-sm text-gray-600">A polished shirt for office days.</p><div class="mt-4 flex items-center justify-between"><span class="font-black">$39.00</span><a class="btn-go rounded-md px-4 py-2 text-sm font-bold" href="{{ url('detail.html') }}">View</a></div></div>
        </article>
        <article class="product-card overflow-hidden">
          <div class="product-swatch h-52 bg-zinc-300"></div>
          <div class="p-4"><h3 class="font-black">Casual Dress</h3><p class="mt-1 text-sm text-gray-600">Easy fit with clean lines.</p><div class="mt-4 flex items-center justify-between"><span class="font-black">$59.00</span><a class="btn-go rounded-md px-4 py-2 text-sm font-bold" href="{{ url('detail.html') }}">View</a></div></div>
        </article>
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

@endsection

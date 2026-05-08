<header class="store-header sticky top-0 z-40">
  <div class="mx-auto max-w-7xl px-4">
    <div class="flex items-center justify-between gap-4 py-3">
      <div class="flex items-center gap-3">
        <button data-mobile-toggle class="mobile-toggle lg:hidden p-2 rounded-md" aria-controls="mobile-navigation" aria-expanded="false" aria-label="Toggle menu" type="button">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <a href="{{ url('/') }}" class="text-2xl font-black tracking-tight text-white">Storefront</a>
      </div>

      <div class="flex-1 hidden md:flex justify-center px-4">
        <form class="header-search w-full max-w-2xl flex items-center gap-2" role="search" aria-label="Site search" onsubmit="return false;">
          <label for="site-search" class="sr-only">Search products</label>
          <input id="site-search" class="form-control text-gray-900" type="search" placeholder="Search shirts, dresses, tops">
          <button class="btn-go shrink-0 rounded-md px-5 py-2 font-bold" type="submit">Search</button>
        </form>
      </div>

      <div class="flex items-center gap-3">
        <a class="cart-button rounded-md bg-white/10 px-3 py-2 text-sm font-bold flex items-center gap-2" href="{{ url('cart.html') }}" aria-label="View cart">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4"></path><circle cx="9" cy="20" r="1"></circle><circle cx="20" cy="20" r="1"></circle></svg>
          <span class="sr-only">Cart</span>
          <span data-cart-count>3</span>
        </a>
        <a class="btn-login rounded-md px-4 py-2 font-bold hidden sm:inline-flex" href="{{ url('login.html') }}">Login</a>
      </div>
    </div>

    {{-- Desktop Navigation with Dropdowns --}}
    <nav id="primary-navigation" class="hidden lg:flex lg:items-center lg:justify-between mt-2" aria-label="Primary navigation">
      <div class="flex flex-wrap items-center gap-2 text-sm">
        <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('/') }}">Home</a>
        
        {{-- Dynamic Categories Dropdown --}}
        @foreach($categories as $category)
          <div class="relative inline-block group">
            <a data-nav class="store-link rounded-md px-3 py-2 inline-flex items-center gap-1" href="{{ url('category/'.$category->slug) }}">
              {{ $category->name }}
              @if($category->children->count())
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              @endif
            </a>
            
            @if($category->children->count())
              <div class="dropdown-menu absolute left-0 mt-1 w-64 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <div class="rounded-md bg-white ring-1 ring-black ring-opacity-5">
                  <div class="py-1">
                    @foreach($category->children as $child)
                      <a href="{{ url('category/'.$child->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <strong>{{ $child->name }}</strong>
                      </a>
                      @foreach($child->children as $sub)
                        <a href="{{ url('category/'.$sub->slug) }}" class="block px-8 py-1 text-sm text-gray-500 hover:bg-gray-100">
                          - {{ $sub->name }}
                        </a>
                      @endforeach
                      @if(!$loop->last)
                        <div class="border-t border-gray-100 my-1"></div>
                      @endif
                    @endforeach
                  </div>
                </div>
              </div>
            @endif
          </div>
        @endforeach
        
        <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('listing.html') }}">Shop</a>
        <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('about.html') }}">About</a>
        <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('contact.html') }}">Contact</a>
      </div>
      <div class="hidden lg:block">
        <a class="rounded-md border border-gray-300 bg-white px-4 py-2 font-bold text-gray-800" href="{{ url('register.html') }}">Create account</a>
      </div>
    </nav>

    {{-- Mobile Navigation --}}
    <nav id="mobile-navigation" class="mobile-drawer lg:hidden" aria-label="Mobile navigation" aria-hidden="true">
      <div class="p-4">
        <form class="header-search mb-4 flex w-full gap-2" role="search" aria-label="Mobile search" onsubmit="return false;">
          <label for="mobile-site-search" class="sr-only">Search products</label>
          <input id="mobile-site-search" class="form-control text-gray-900" type="search" placeholder="Search shirts, dresses, tops">
          <button class="btn-go shrink-0 rounded-md px-4 py-2 font-bold" type="submit">Search</button>
        </form>
        <div class="flex flex-col gap-2">
          <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('/') }}">Home</a>
          
          {{-- Mobile Categories with Collapsible Submenu --}}
          @foreach($categories as $category)
            <div class="mobile-category">
              <div class="flex items-center justify-between">
                <a data-nav class="store-link rounded-md px-3 py-2 flex-1" href="{{ url('category/'.$category->slug) }}">
                  {{ $category->name }}
                </a>
                @if($category->children->count())
                  <button type="button" class="mobile-submenu-toggle p-2" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                @endif
              </div>
              @if($category->children->count())
                <div class="mobile-submenu hidden ml-4 mt-1 flex flex-col gap-1">
                  @foreach($category->children as $child)
                    <a href="{{ url('category/'.$child->slug) }}" class="store-link rounded-md px-3 py-1 text-sm">
                      <strong>{{ $child->name }}</strong>
                    </a>
                    @foreach($child->children as $sub)
                      <a href="{{ url('category/'.$sub->slug) }}" class="store-link rounded-md px-6 py-1 text-xs text-gray-400">
                        - {{ $sub->name }}
                      </a>
                    @endforeach
                  @endforeach
                </div>
              @endif
            </div>
          @endforeach
          
          <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('listing.html') }}">Shop</a>
          <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('about.html') }}">About</a>
          <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('faq.html') }}">FAQ</a>
          <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('contact.html') }}">Contact</a>
        </div>
        <div class="mt-4">
          <a class="rounded-md border border-gray-300 bg-white px-4 py-2 font-bold text-gray-800 block text-center" href="{{ url('register.html') }}">Create account</a>
        </div>
      </div>
    </nav>

  </div>
</header>
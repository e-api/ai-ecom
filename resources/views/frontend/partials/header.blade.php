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
        <!-- Currency Swapper Desktop -->
        <div class="hidden sm:flex items-center gap-2">
          <select class="currency-select bg-white/10 text-white text-sm rounded-md px-2 py-1.5 border border-white/20 cursor-pointer" aria-label="Select currency">
            <option value="USD" class="text-gray-900 flex items-center gap-2">
              <svg class="flag-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 14"><rect width="20" height="14" fill="#fff"/><rect y="1" width="20" height="2" fill="#B22234"/><rect y="5" width="20" height="2" fill="#B22234"/><rect y="9" width="20" height="2" fill="#B22234"/><rect width="8" height="7" fill="#3C3B6E"/></svg>
              USD
            </option>
            <option value="EUR" class="text-gray-900 flex items-center gap-2">
              <svg class="flag-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 14"><rect width="20" height="14" fill="#003399"/><rect y="4.6" width="20" height="4.8" fill="#FFCC00"/></svg>
              EUR
            </option>
            <option value="GBP" class="text-gray-900 flex items-center gap-2">
              <svg class="flag-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 14"><rect width="20" height="14" fill="#012169"/><path d="M0 0L20 14M20 0L0 14" stroke="#fff" stroke-width="3"/><path d="M0 0L20 14M20 0L0 14" stroke="#C8102E" stroke-width="1.5"/></svg>
              GBP
            </option>
            <option value="JPY" class="text-gray-900 flex items-center gap-2">
              <svg class="flag-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 14"><rect width="20" height="14" fill="#fff"/><circle cx="10" cy="7" r="4" fill="#BC002D"/></svg>
              JPY
            </option>
          </select>
        </div>

        <a class="cart-button rounded-md bg-white/10 px-3 py-2 text-sm font-bold flex items-center gap-2" href="{{ url('cart.html') }}" aria-label="View cart">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4"></path><circle cx="9" cy="20" r="1"></circle><circle cx="20" cy="20" r="1"></circle></svg>
          <span class="sr-only">Cart</span>
          <span data-cart-count>3</span>
        </a>

        <!-- Desktop: Login Button (logged out) -->
        <a class="btn-login rounded-md px-4 py-2 font-bold hidden sm:inline-flex items-center gap-2" href="{{ url('login.html') }}">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
          </svg>
          Login
        </a>

        <!-- Desktop: User Avatar (logged in) - Hidden by default -->
        <div class="hidden sm:flex items-center gap-2 cursor-pointer" id="desktop-user-menu">
          <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm">
            JD
          </div>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </div>
    </div>

    <nav id="primary-navigation" class="hidden lg:flex lg:items-center lg:justify-between mt-2" aria-label="Primary navigation">
      <div class="flex flex-wrap items-center gap-2 text-sm">
        <a data-nav class="store-link px-2 rounded-lg font-medium transition-all duration-200 text-white hover:bg-white/10" href="{{ url('/') }}">Home</a>
        
        @foreach($categories as $category)
          <div class="relative group">
            <a data-nav class="store-link rounded-md px-2 font-medium text-white hover:bg-white/10 inline-flex items-center gap-1" href="{{ url('category/'.$category->slug) }}">
              {{ $category->name }}
              @if($category->children->count())
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              @endif
            </a>
            
            @if($category->children->count())
              <div class="dropdown-menu absolute left-0 mt-1 w-64 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <div class="rounded-md bg-white ring-1 ring-black ring-opacity-5 py-1">
                  @foreach($category->children as $child)
                    @if($child->children->count())
                      <div class="relative">
                        <div class="submenu-trigger flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                          <strong>{{ $child->name }}</strong>
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                          </svg>
                        </div>
                        
                        <div class="dropdown-submenu absolute left-0 top-full mt-0 w-64 rounded-md shadow-lg opacity-0 invisible transition-all duration-200 z-50">
                          <div class="rounded-md bg-white ring-1 ring-black ring-opacity-5 py-1">
                            @foreach($child->children as $sub)
                              @if($sub->children->count())
                                <div class="relative">
                                  <div class="submenu-trigger flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                                    <span>{{ $sub->name }}</span>
                                    <svg class="h-3 w-3 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                  </div>
                                  
                                  <div class="dropdown-submenu absolute left-0 top-full mt-0 w-64 rounded-md shadow-lg opacity-0 invisible transition-all duration-200 z-50">
                                    <div class="rounded-md bg-white ring-1 ring-black ring-opacity-5 py-1">
                                      @foreach($sub->children as $subsub)
                                        <a href="{{ url('category/'.$subsub->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                          {{ $subsub->name }}
                                        </a>
                                      @endforeach
                                    </div>
                                  </div>
                                </div>
                              @else
                                <a href="{{ url('category/'.$sub->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                  {{ $sub->name }}
                                </a>
                              @endif
                            @endforeach
                          </div>
                        </div>
                      </div>
                    @else
                      <a href="{{ url('category/'.$child->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <strong>{{ $child->name }}</strong>
                      </a>
                    @endif
                    
                    @if(!$loop->last)
                      <div class="border-t border-gray-100 my-1"></div>
                    @endif
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        @endforeach
        
        <a data-nav class="store-link px-2 rounded-lg font-medium transition-all duration-200 text-white hover:bg-white/10" href="{{ url('shop.html') }}">Shop</a>
        <a data-nav class="store-link px-2 rounded-lg font-medium transition-all duration-200 text-white hover:bg-white/10" href="{{ url('about.html') }}">About</a>
        <a data-nav class="store-link px-2 rounded-lg font-medium transition-all duration-200 text-white hover:bg-white/10" href="{{ url('contact.html') }}">Contact</a>
      </div>
      <div class="hidden lg:flex items-center gap-3">
        <a class="rounded-md border border-gray-300 bg-white px-4 py-2 font-bold text-gray-800" href="{{ url('register.html') }}">Create account</a>
      </div>
    </nav>

    {{-- Mobile Navigation --}}
    <nav id="mobile-navigation" class="mobile-drawer lg:hidden" aria-label="Mobile navigation" aria-hidden="true">
      <div class="p-4">
        {{-- Mobile Search --}}
        <form class="header-search mb-4 flex w-full gap-2" role="search" aria-label="Mobile search" onsubmit="return false;">
          <label for="mobile-site-search" class="sr-only">Search products</label>
          <input id="mobile-site-search" class="form-control text-gray-900" type="search" placeholder="Search shirts, dresses, tops">
          <button class="btn-go shrink-0 rounded-md px-4 py-2 font-bold" type="submit">Search</button>
        </form>

        {{-- Mobile User Section --}}
        <div class="mb-4 p-3 rounded-lg bg-white/5 border border-white/10">
          {{-- Logged Out State --}}
          <div class="mobile-logged-out">
            <a href="{{ url('login.html') }}" class="flex items-center gap-2 text-white hover:bg-white/10 rounded-md p-2 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
              </svg>
              <span class="font-bold">Login / Register</span>
            </a>
          </div>

          {{-- Logged In State (Hidden by default) --}}
          <div class="mobile-logged-in hidden">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                JD
              </div>
              <div class="flex-1">
                <p class="text-white font-bold text-sm">John Doe</p>
                <p class="text-white/60 text-xs">john@example.com</p>
              </div>
            </div>
            <div class="mt-3 pt-3 border-t border-white/10 grid grid-cols-2 gap-2">
              <a href="{{ url('account.html') }}" class="text-white text-sm hover:bg-white/10 rounded-md p-2 transition text-center">My Account</a>
              <a href="{{ url('orders.html') }}" class="text-white text-sm hover:bg-white/10 rounded-md p-2 transition text-center">Orders</a>
              <a href="{{ url('wishlist.html') }}" class="text-white text-sm hover:bg-white/10 rounded-md p-2 transition text-center">Wishlist</a>
              <a href="{{ url('logout.html') }}" class="text-red-400 text-sm hover:bg-white/10 rounded-md p-2 transition text-center">Logout</a>
            </div>
          </div>
        </div>

        {{-- Mobile Currency Swapper --}}
        <div class="mb-4">
          <label class="text-white/60 text-xs block mb-1">Currency</label>
          <select class="w-full bg-white/5 border border-white/10 text-white text-sm rounded-md px-3 py-2 cursor-pointer" aria-label="Select currency">
            <option value="USD" class="text-gray-900">🇺🇸 USD - US Dollar</option>
            <option value="EUR" class="text-gray-900">🇪🇺 EUR - Euro</option>
            <option value="GBP" class="text-gray-900">🇬🇧 GBP - British Pound</option>
            <option value="JPY" class="text-gray-900">🇯🇵 JPY - Japanese Yen</option>
          </select>
        </div>

        {{-- Mobile Navigation Links --}}
        <div class="flex flex-col gap-2">
          <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('/') }}">Home</a>
          
          @foreach($categories as $category)
            @if($category->children->count())
              <details class="mobile-category-details">
                <summary class="store-link rounded-md px-3 py-2 cursor-pointer list-none w-full text-left">
                  <span class="flex items-center justify-between">
                    <span>{{ $category->name }}</span>
                    <span class="summary-arrow ml-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                      </svg>
                    </span>
                  </span>
                </summary>
                <div class="mt-1 flex flex-col">
                  @foreach($category->children as $child)
                    {{-- Level 2 Category --}}
                    @if($child->children->count())
                      {{-- Has Level 3 children - Make it collapsible --}}
                      <details class="mobile-sub-category-details ml-2">
                        <summary class="store-link rounded-md py-1 text-sm cursor-pointer list-none w-full text-left font-semibold">
                          <span class="flex items-center justify-between">
                            <span>{{ $child->name }}</span>
                            <span class="summary-arrow ml-2">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                              </svg>
                            </span>
                          </span>
                        </summary>
                        <div class="mt-1 ml-2 flex flex-col">
                          @foreach($child->children as $sub)
                            {{-- Level 3 Category --}}
                            @if($sub->children->count())
                              {{-- Has Level 4 children --}}
                              <details class="mobile-level3-category-details ml-2">
                                <summary class="store-link rounded-md py-1 text-xs cursor-pointer list-none w-full text-left text-gray-600">
                                  <span class="flex items-center justify-between">
                                    <span>{{ $sub->name }}</span>
                                    <span class="summary-arrow ml-2">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                      </svg>
                                    </span>
                                  </span>
                                </summary>
                                <div class="mt-1 ml-2 flex flex-col">
                                  @foreach($sub->children as $level4)
                                    {{-- Level 4 Category --}}
                                    <a data-nav class="store-link rounded-md py-1 text-xs text-gray-500 mobile-level-4" href="{{ url('category/'.$level4->slug) }}">
                                      {{ $level4->name }}
                                    </a>
                                  @endforeach
                                </div>
                              </details>
                            @else
                              {{-- No Level 4 children - Regular link --}}
                              <a data-nav class="store-link rounded-md py-1 text-xs mobile-level-3 ml-2" href="{{ url('category/'.$sub->slug) }}">
                                {{ $sub->name }}
                              </a>
                            @endif
                          @endforeach
                        </div>
                      </details>
                    @else
                      {{-- No Level 3 children - Regular link --}}
                      <a data-nav class="store-link rounded-md py-1 text-sm mobile-level-2 ml-2" href="{{ url('category/'.$child->slug) }}">
                        {{ $child->name }}
                      </a>
                    @endif
                    
                    @if(!$loop->last)
                      <div class="mobile-divider"></div>
                    @endif
                  @endforeach
                </div>
              </details>
            @else
              <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('category/'.$category->slug) }}">
                {{ $category->name }}
              </a>
            @endif
          @endforeach
          
          <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('listing.html') }}">Shop</a>
          <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('about.html') }}">About</a>
          <a data-nav class="store-link rounded-md px-3 py-2" href="{{ url('contact.html') }}">Contact</a>
        </div>
      </div>
    </nav>

  </div>
</header>
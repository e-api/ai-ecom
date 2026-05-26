<section class="space-y-6 lg:sticky lg:top-28 lg:self-start">
  <div class="store-sidebar p-4">
    <div class="flex items-center justify-between mb-2">
      <h5 class="font-bold text-lg">Filters</h5>
      <button id="desktop-filter-reset" class="text-xs text-red-500 hover:text-red-700 transition">Reset All</button>
    </div>
    <hr class="my-3 border-gray-200">
    {{-- Category Filter --}}
    <div class="filter-group">
      <div class="flex items-stretch">
        <h6 class="font-semibold text-md flex-1 px-1 py-0 rounded-l-md text-gray-800 flex items-center">Categories</h6>
        <button class="sidebar-toggle-btn px-3 py-2 rounded-r-md hover:bg-transparent transition-all duration-200 flex items-center justify-center" data-target="category-filter-dropdown">
          <svg class="sidebar-icon h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
      </div>
      <div id="category-filter-dropdown" class="sidebar-dropdown hidden mt-1 ml-3 pl-2 border-l-2 border-gray-100 space-y-1">
        @foreach($filterCategories as $filterCategory)
        <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
        <input type="checkbox"
        class="filter-checkbox category-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2"
        value="{{ $filterCategory->id }}"
        {{ request()->categories && in_array($filterCategory->id, explode(',', request()->categories)) ? 'checked' : '' }}>
        <span class="text-gray-700 text-[12px]">{{ $filterCategory->name }}</span>
        </label>
        @endforeach
      </div>
    </div>
    {{-- Price Filter --}}
    <div class="filter-group">
      <div class="flex items-stretch">
        <h6 class="font-semibold text-md flex-1 px-1 py-0 rounded-l-md text-gray-800 flex items-center">Price</h6>
        <button class="sidebar-toggle-btn px-3 py-2 rounded-r-md hover:bg-transparent transition-all duration-200 flex items-center justify-center" data-target="price-filter-dropdown">
          <svg class="sidebar-icon h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
      </div>
      <div id="price-filter-dropdown" class="sidebar-dropdown hidden mt-1 ml-3 pl-2 border-l-2 border-gray-100 space-y-1">
        <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
        <input type="checkbox" class="filter-checkbox price-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2" value="0-50" {{ request()->price && in_array('0-50', explode(',', request()->price)) ? 'checked' : '' }}>
        <span class="text-gray-700 text-[12px]">$0 - $50</span>
        </label>
        <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
        <input type="checkbox" class="filter-checkbox price-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2" value="50-100" {{ request()->price && in_array('50-100', explode(',', request()->price)) ? 'checked' : '' }}>
        <span class="text-gray-700 text-[12px]">$50 - $100</span>
        </label>
        <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
        <input type="checkbox" class="filter-checkbox price-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2" value="100-200" {{ request()->price && in_array('100-200', explode(',', request()->price)) ? 'checked' : '' }}>
        <span class="text-gray-700 text-[12px]">$100 - $200</span>
        </label>
        <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
        <input type="checkbox" class="filter-checkbox price-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2" value="200-500" {{ request()->price && in_array('200-500', explode(',', request()->price)) ? 'checked' : '' }}>
        <span class="text-gray-700 text-[12px]">$200 - $500</span>
        </label>
        <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
        <input type="checkbox" class="filter-checkbox price-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2" value="500-1000" {{ request()->price && in_array('500-1000', explode(',', request()->price)) ? 'checked' : '' }}>
        <span class="text-gray-700 text-[12px]">$500 - $1000</span>
        </label>
        <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
        <input type="checkbox" class="filter-checkbox price-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2" value="1000-999999" {{ request()->price && in_array('1000-999999', explode(',', request()->price)) ? 'checked' : '' }}>
        <span class="text-gray-700 text-[12px]">$1000+</span>
        </label>
      </div>
    </div>
    {{-- Brand Filter --}}
    @if($brands->count() > 0)
    <div class="filter-group">
      <div class="flex items-stretch">
        <h6 class="font-semibold text-md flex-1 px-1 py-0 rounded-l-md text-gray-800 flex items-center">Brands</h6>
        <button class="sidebar-toggle-btn px-3 py-2 rounded-r-md hover:bg-transparent transition-all duration-200 flex items-center justify-center" data-target="brand-filter-dropdown">
          <svg class="sidebar-icon h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
      </div>
      <div id="brand-filter-dropdown" class="sidebar-dropdown hidden mt-1 ml-3 pl-2 border-l-2 border-gray-100 space-y-1">
        @foreach($brands as $brand)
        <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
        <input type="checkbox"
        class="filter-checkbox brand-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2"
        value="{{ $brand->id }}"
        {{ request()->brands && in_array($brand->id, explode(',', request()->brands)) ? 'checked' : '' }}>
        <span class="text-gray-700 text-[12px]">{{ $brand->name }}</span>
        </label>
        @endforeach
      </div>
    </div>
    @endif
    {{-- Size Filter --}}
    @if($sizes->count() > 0)
    <div class="filter-group mb-4">
      <div class="flex items-stretch">
        <h6 class="font-semibold text-md flex-1 px-1 py-0 rounded-l-md text-gray-800 flex items-center">Sizes</h6>
        <button class="sidebar-toggle-btn px-3 py-2 rounded-r-md hover:bg-transparent transition-all duration-200 flex items-center justify-center" data-target="size-filter-dropdown">
          <svg class="sidebar-icon h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
      </div>
      <div id="size-filter-dropdown" class="sidebar-dropdown hidden mt-1 ml-3 pl-2 border-l-2 border-gray-100 space-y-1">
        @foreach($sizes as $size)
        <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
        <input type="checkbox"
        class="filter-checkbox size-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2"
        value="{{ $size->size }}"
        {{ request()->sizes && in_array($size->size, explode(',', request()->sizes)) ? 'checked' : '' }}>
        <span class="text-gray-700 text-[12px]">{{ $size->size }}</span>
        </label>
        @endforeach
      </div>
    </div>
    @endif
  </div>
  {{-- Mobile Filter Button (Only visible on mobile) --}}
  <div class="lg:hidden fixed bottom-4 right-4 z-50">
    <button id="mobile-filter-toggle" class="bg-primary text-white rounded-full p-4 shadow-lg flex items-center justify-center">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
      </svg>
    </button>
  </div>
  {{-- Mobile Filter Drawer --}}
  <div id="mobile-filter-drawer" class="fixed inset-y-0 left-0 transform -translate-x-full transition-transform duration-300 ease-in-out z-40 w-80 bg-white shadow-xl lg:hidden" style="top: 48px; height: calc(100% - 48px);">
    <div class="flex flex-col h-full">
      {{-- Header --}}
      <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h3 class="text-lg font-bold">Filters</h3>
        <button id="mobile-filter-close" class="text-gray-500 hover:text-gray-700">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      {{-- Filter Content --}}
      <div class="flex-1 overflow-y-auto p-4">
        {{-- Category Filter --}}
        <div class="filter-group">
          <div class="flex items-stretch">
            <h6 class="font-semibold text-md flex-1 px-1 py-0 rounded-l-md text-gray-800 flex items-center">Categories</h6>
            <button class="sidebar-toggle-btn px-3 py-2 rounded-r-md hover:bg-transparent transition-all duration-200 flex items-center justify-center" data-target="category-filter-dropdown-mobile">
              <svg class="sidebar-icon h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
          </div>
          <div id="category-filter-dropdown-mobile" class="sidebar-dropdown hidden mt-1 ml-3 pl-2 border-l-2 border-gray-100 space-y-1">
            @foreach($filterCategories as $filterCategory)
            <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
            <input type="checkbox"
            class="filter-checkbox category-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2"
            value="{{ $filterCategory->id }}"
            {{ request()->categories && in_array($filterCategory->id, explode(',', request()->categories)) ? 'checked' : '' }}>
            <span class="text-gray-700 text-sm">{{ $filterCategory->name }}</span>
            </label>
            @endforeach
          </div>
        </div>
        {{-- Price Filter --}}
        <div class="filter-group">
          <div class="flex items-stretch">
            <h6 class="font-semibold text-md flex-1 px-1 py-0 rounded-l-md text-gray-800 flex items-center">Price</h6>
            <button class="sidebar-toggle-btn px-3 py-2 rounded-r-md hover:bg-transparent transition-all duration-200 flex items-center justify-center" data-target="price-filter-dropdown-mobile">
              <svg class="sidebar-icon h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
          </div>
          <div id="price-filter-dropdown-mobile" class="sidebar-dropdown hidden mt-1 ml-3 pl-2 border-l-2 border-gray-100 space-y-1">
            <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
            <input type="checkbox" class="filter-checkbox price-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2" value="0-50" {{ request()->price && in_array('0-50', explode(',', request()->price)) ? 'checked' : '' }}>
            <span class="text-gray-700 text-sm">$0 - $50</span>
            </label>
            <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
            <input type="checkbox" class="filter-checkbox price-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2" value="50-100" {{ request()->price && in_array('50-100', explode(',', request()->price)) ? 'checked' : '' }}>
            <span class="text-gray-700 text-sm">$50 - $100</span>
            </label>
            <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
            <input type="checkbox" class="filter-checkbox price-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2" value="100-200" {{ request()->price && in_array('100-200', explode(',', request()->price)) ? 'checked' : '' }}>
            <span class="text-gray-700 text-sm">$100 - $200</span>
            </label>
            <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
            <input type="checkbox" class="filter-checkbox price-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2" value="200-500" {{ request()->price && in_array('200-500', explode(',', request()->price)) ? 'checked' : '' }}>
            <span class="text-gray-700 text-sm">$200 - $500</span>
            </label>
            <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
            <input type="checkbox" class="filter-checkbox price-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2" value="500-1000" {{ request()->price && in_array('500-1000', explode(',', request()->price)) ? 'checked' : '' }}>
            <span class="text-gray-700 text-sm">$500 - $1000</span>
            </label>
            <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
            <input type="checkbox" class="filter-checkbox price-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2" value="1000-999999" {{ request()->price && in_array('1000-999999', explode(',', request()->price)) ? 'checked' : '' }}>
            <span class="text-gray-700 text-sm">$1000+</span>
            </label>
          </div>
        </div>
        {{-- Brand Filter --}}
        <div class="filter-group">
          <div class="flex items-stretch">
            <h6 class="font-semibold text-md flex-1 px-1 py-0 rounded-l-md text-gray-800 flex items-center">Brands</h6>
            <button class="sidebar-toggle-btn px-3 py-2 rounded-r-md hover:bg-transparent transition-all duration-200 flex items-center justify-center" data-target="brand-filter-dropdown-mobile">
              <svg class="sidebar-icon h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
          </div>
          <div id="brand-filter-dropdown-mobile" class="sidebar-dropdown hidden mt-1 ml-3 pl-2 border-l-2 border-gray-100 space-y-1">
            @foreach($brands as $brand)
            <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
              <input type="checkbox"
                class="filter-checkbox brand-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2"
                value="{{ $brand->id }}"
                {{ request()->brands && in_array($brand->id, explode(',', request()->brands)) ? 'checked' : '' }}>
                <span class="text-gray-700 text-sm">{{ $brand->name }}</span>
            </label>
            @endforeach
          </div>
        </div>
        {{-- Size Filter --}}
        <div class="filter-group">
          <div class="flex items-stretch">
            <h6 class="font-semibold text-md flex-1 px-1 py-0 rounded-l-md text-gray-800 flex items-center">Sizes</h6>
            <button class="sidebar-toggle-btn px-3 py-2 rounded-r-md hover:bg-transparent transition-all duration-200 flex items-center justify-center" data-target="size-filter-dropdown-mobile">
              <svg class="sidebar-icon h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
          </div>
          <div id="size-filter-dropdown-mobile" class="sidebar-dropdown hidden mt-1 ml-3 pl-2 border-l-2 border-gray-100 space-y-1">
            @foreach($sizes as $size)
            <label class="flex items-center cursor-pointer py-1 hover:bg-gray-50 px-2 rounded-md transition">
              <input type="checkbox"
                class="filter-checkbox size-filter w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary mr-2"
                value="{{ $size->size }}"
                {{ request()->sizes && in_array($size->size, explode(',', request()->sizes)) ? 'checked' : '' }}>
                <span class="text-gray-700 text-sm">{{ $size->size }}</span>
            </label>
            @endforeach
          </div>
        </div>
      </div>
      {{-- Footer with Apply Button --}}
      <div class="p-4 border-t border-gray-200 space-y-2">
        <button id="mobile-filter-reset" class="w-full bg-gray-100 text-gray-700 py-2 rounded-lg font-semibold hover:bg-gray-200 transition">Reset All Filters</button>
      </div>
    </div>
  </div>
  {{-- Overlay --}}
  <div id="mobile-filter-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-60 hidden lg:hidden"></div>
  {{-- Payment Methods --}}
    <div class="store-sidebar p-4">
      <h2 class="sidebar-title mb-3">We Accept</h2>
      <div class="grid grid-cols-4 max-w-xs mx-auto gap-2">
        <div class="relative group flex items-center justify-center bg-transparent cursor-pointer transition">
          <svg viewBox="0 -11 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="0.5" y="0.5" width="69" height="47" rx="5.5" fill="white" stroke="#D9D9D9"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M21.2505 32.5165H17.0099L13.8299 20.3847C13.679 19.8267 13.3585 19.3333 12.8871 19.1008C11.7106 18.5165 10.4142 18.0514 9 17.8169V17.3498H15.8313C16.7742 17.3498 17.4813 18.0514 17.5991 18.8663L19.2491 27.6173L23.4877 17.3498H27.6104L21.2505 32.5165ZM29.9675 32.5165H25.9626L29.2604 17.3498H33.2653L29.9675 32.5165ZM38.4467 21.5514C38.5646 20.7346 39.2717 20.2675 40.0967 20.2675C41.3931 20.1502 42.8052 20.3848 43.9838 20.9671L44.6909 17.7016C43.5123 17.2345 42.216 17 41.0395 17C37.1524 17 34.3239 19.1008 34.3239 22.0165C34.3239 24.2346 36.3274 25.3992 37.7417 26.1008C39.2717 26.8004 39.861 27.2675 39.7431 27.9671C39.7431 29.0165 38.5646 29.4836 37.3881 29.4836C35.9739 29.4836 34.5596 29.1338 33.2653 28.5494L32.5582 31.8169C33.9724 32.3992 35.5025 32.6338 36.9167 32.6338C41.2752 32.749 43.9838 30.6502 43.9838 27.5C43.9838 23.5329 38.4467 23.3004 38.4467 21.5514ZM58 32.5165L54.82 17.3498H51.4044C50.6972 17.3498 49.9901 17.8169 49.7544 18.5165L43.8659 32.5165H47.9887L48.8116 30.3004H53.8772L54.3486 32.5165H58ZM51.9936 21.4342L53.1701 27.1502H49.8723L51.9936 21.4342Z" fill="#172B85"/>
          </svg>
          <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
          Visa
          <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
          </div>
        </div>
        <div class="relative group flex items-center justify-center bg-transparent cursor-pointer transition">
          <svg viewBox="0 -9 58 58" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="0.5" y="0.5" width="57" height="39" rx="3.5" fill="white" stroke="#F3F3F3"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M21.2489 30.8906V32.3674V33.8443H20.6016V33.4857C20.3963 33.7517 20.0848 33.9186 19.6614 33.9186C18.8266 33.9186 18.1722 33.27 18.1722 32.3674C18.1722 31.4656 18.8266 30.8163 19.6614 30.8163C20.0848 30.8163 20.3963 30.9832 20.6016 31.2492V30.8906H21.2489ZM19.7419 31.4218C19.1816 31.4218 18.8387 31.8483 18.8387 32.3674C18.8387 32.8866 19.1816 33.3131 19.7419 33.3131C20.2773 33.3131 20.6387 32.905 20.6387 32.3674C20.6387 31.8299 20.2773 31.4218 19.7419 31.4218ZM43.1228 32.3674C43.1228 31.8483 43.4657 31.4218 44.026 31.4218C44.5621 31.4218 44.9228 31.8299 44.9228 32.3674C44.9228 32.905 44.5621 33.3131 44.026 33.3131C43.4657 33.3131 43.1228 32.8866 43.1228 32.3674ZM45.5338 29.7044V32.3674V33.8443H44.8858V33.4857C44.6804 33.7517 44.3689 33.9186 43.9455 33.9186C43.1107 33.9186 42.4563 33.27 42.4563 32.3674C42.4563 31.4656 43.1107 30.8163 43.9455 30.8163C44.3689 30.8163 44.6804 30.9832 44.8858 31.2492V29.7044H45.5338ZM29.2838 31.3914C29.7008 31.3914 29.9688 31.6509 30.0373 32.1079H28.4925C28.5616 31.6814 28.8225 31.3914 29.2838 31.3914ZM27.8138 32.3674C27.8138 31.4465 28.424 30.8163 29.2966 30.8163C30.1307 30.8163 30.7038 31.4465 30.7102 32.3674C30.7102 32.4537 30.7038 32.5344 30.6974 32.6143H28.4868C28.5802 33.1462 28.9601 33.3379 29.3771 33.3379C29.6758 33.3379 29.9938 33.2261 30.2433 33.0288L30.5605 33.5048C30.1991 33.8075 29.7885 33.9186 29.3401 33.9186C28.449 33.9186 27.8138 33.3068 27.8138 32.3674ZM37.1126 32.3674C37.1126 31.8483 37.4555 31.4218 38.0158 31.4218C38.5511 31.4218 38.9126 31.8299 38.9126 32.3674C38.9126 32.905 38.5511 33.3131 38.0158 33.3131C37.4555 33.3131 37.1126 32.8866 37.1126 32.3674ZM39.5228 30.8906V32.3674V33.8443H38.8755V33.4857C38.6695 33.7517 38.3587 33.9186 37.9352 33.9186C37.1004 33.9186 36.446 33.27 36.446 32.3674C36.446 31.4656 37.1004 30.8163 37.9352 30.8163C38.3587 30.8163 38.6695 30.9832 38.8755 31.2492V30.8906H39.5228ZM33.4569 32.3674C33.4569 33.2636 34.0857 33.9186 35.0452 33.9186C35.4936 33.9186 35.7923 33.8196 36.116 33.5663L35.8051 33.0472C35.5621 33.2205 35.3068 33.3131 35.026 33.3131C34.5091 33.3068 34.1292 32.9361 34.1292 32.3674C34.1292 31.7988 34.5091 31.4281 35.026 31.4218C35.3068 31.4218 35.5621 31.5144 35.8051 31.6877L36.116 31.1685C35.7923 30.9153 35.4936 30.8163 35.0452 30.8163C34.0857 30.8163 33.4569 31.4713 33.4569 32.3674ZM41.0177 31.2492C41.1859 30.9896 41.429 30.8163 41.8026 30.8163C41.9337 30.8163 42.1205 30.8411 42.2638 30.8969L42.0642 31.5024C41.9273 31.4465 41.7904 31.4281 41.6593 31.4281C41.2358 31.4281 41.0241 31.6997 41.0241 32.1885V33.8443H40.3761V30.8906H41.0177V31.2492ZM24.4505 31.1254C24.1389 30.9217 23.7098 30.8163 23.2364 30.8163C22.4822 30.8163 21.9967 31.1749 21.9967 31.762C21.9967 32.2437 22.3582 32.5407 23.024 32.6334L23.3298 32.6765C23.6848 32.7261 23.8524 32.8187 23.8524 32.9856C23.8524 33.2141 23.6157 33.3442 23.1737 33.3442C22.7253 33.3442 22.4017 33.2021 22.1835 33.0351L21.8784 33.5352C22.2334 33.7948 22.6818 33.9186 23.1673 33.9186C24.027 33.9186 24.5253 33.5168 24.5253 32.9545C24.5253 32.4353 24.1332 32.1637 23.4852 32.0711L23.1801 32.0272C22.9 31.9904 22.6754 31.9353 22.6754 31.7372C22.6754 31.5208 22.8871 31.3914 23.2421 31.3914C23.6221 31.3914 23.9899 31.5335 24.1703 31.6446L24.4505 31.1254ZM32.0184 31.2492C32.1859 30.9896 32.429 30.8163 32.8025 30.8163C32.9337 30.8163 33.1205 30.8411 33.2637 30.8969L33.0641 31.5024C32.9273 31.4465 32.7904 31.4281 32.6592 31.4281C32.2358 31.4281 32.0241 31.6997 32.0241 32.1885V33.8443H31.3768V30.8906H32.0184V31.2492ZM27.2784 30.8906H26.2198V29.9944H25.5654V30.8906H24.9616V31.4776H25.5654V32.8251C25.5654 33.5105 25.8334 33.9186 26.5991 33.9186C26.8799 33.9186 27.2036 33.8323 27.4089 33.6901L27.2221 33.1398C27.0289 33.2509 26.8172 33.3068 26.649 33.3068C26.3253 33.3068 26.2198 33.1087 26.2198 32.8123V31.4776H27.2784V30.8906ZM17.5997 31.9904V33.8443H16.9453V32.2005C16.9453 31.6997 16.7336 31.4218 16.2916 31.4218C15.8617 31.4218 15.563 31.6941 15.563 32.2069V33.8443H14.9086V32.2005C14.9086 31.6997 14.6912 31.4218 14.2613 31.4218C13.8186 31.4218 13.5321 31.6941 13.5321 32.2069V33.8443H12.8784V30.8906H13.5264V31.2548C13.7695 30.909 14.0803 30.8163 14.3982 30.8163C14.853 30.8163 15.1767 31.0144 15.382 31.3418C15.6564 30.9274 16.0485 30.8099 16.4285 30.8163C17.1513 30.8227 17.5997 31.2923 17.5997 31.9904Z" fill="#231F20"/>
            <path d="M34.0465 25.8715H24.2359V8.3783H34.0465V25.8715Z" fill="#FF5F00"/>
            <path d="M24.8583 17.1253C24.8583 13.5767 26.5328 10.4157 29.1405 8.37867C27.2336 6.88907 24.8269 5.99998 22.2114 5.99998C16.0194 5.99998 11 10.9809 11 17.1253C11 23.2697 16.0194 28.2506 22.2114 28.2506C24.8269 28.2506 27.2336 27.3615 29.1405 25.8719C26.5328 23.8349 24.8583 20.6739 24.8583 17.1253" fill="#EB001B"/>
            <path d="M47.2818 17.1253C47.2818 23.2697 42.2624 28.2506 36.0704 28.2506C33.4548 28.2506 31.0482 27.3615 29.1405 25.8719C31.7489 23.8349 33.4235 20.6739 33.4235 17.1253C33.4235 13.5767 31.7489 10.4157 29.1405 8.37867C31.0482 6.88907 33.4548 5.99998 36.0704 5.99998C42.2624 5.99998 47.2818 10.9809 47.2818 17.1253" fill="#F79E1B"/>
          </svg>
          <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
          MasterCards
          <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
          </div>
        </div>
        <div class="relative group flex items-center justify-center bg-transparent cursor-pointer transition">
          <svg viewBox="0 -9 58 58" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="0.5" y="0.5" width="57" height="39" rx="3.5" fill="white" stroke="#F3F3F3"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M26.4388 20.2562L26.6913 18.6477L26.1288 18.6346H23.4429L25.3095 6.76505C25.3153 6.72911 25.3341 6.69575 25.3616 6.67201C25.3892 6.64827 25.4243 6.63525 25.4611 6.63525H29.9901C31.4937 6.63525 32.5313 6.94897 33.073 7.56826C33.327 7.85879 33.4887 8.16246 33.567 8.49653C33.6491 8.84713 33.6505 9.26596 33.5704 9.77689L33.5646 9.81405V10.1415L33.8186 10.2858C34.0324 10.3996 34.2024 10.5298 34.3328 10.6788C34.55 10.9273 34.6905 11.2431 34.7499 11.6173C34.8113 12.0022 34.791 12.4604 34.6905 12.979C34.5746 13.5755 34.3873 14.0951 34.1343 14.5202C33.9016 14.9119 33.6052 15.2369 33.2531 15.4886C32.9171 15.7279 32.5178 15.9095 32.0664 16.0257C31.6288 16.1399 31.1301 16.1975 30.583 16.1975H30.2305C29.9786 16.1975 29.7338 16.2886 29.5416 16.4517C29.3489 16.6183 29.2215 16.8459 29.1824 17.0947L29.1558 17.2396L28.7096 20.0747L28.6894 20.1787C28.684 20.2117 28.6748 20.2281 28.6613 20.2392C28.6493 20.2494 28.632 20.2562 28.615 20.2562H26.4388" fill="#28356A"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M34.0589 9.85181C34.0455 9.93848 34.03 10.027 34.0126 10.1181C33.4154 13.1934 31.372 14.2558 28.7623 14.2558H27.4335C27.1143 14.2558 26.8453 14.4881 26.7957 14.8038L25.9227 20.3573C25.8904 20.5647 26.0497 20.7514 26.2582 20.7514H28.615C28.894 20.7514 29.1311 20.5481 29.1751 20.2721L29.1982 20.1521L29.6419 17.3281L29.6705 17.1732C29.7139 16.8962 29.9515 16.6928 30.2305 16.6928H30.583C32.8663 16.6928 34.6538 15.7632 35.1763 13.0728C35.3944 11.9489 35.2815 11.0105 34.704 10.3505C34.5293 10.1516 34.3125 9.98635 34.0589 9.85181" fill="#298FC2"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M33.4342 9.60206C33.3429 9.57534 33.2488 9.5512 33.1522 9.52936C33.0551 9.50807 32.9557 9.48922 32.8533 9.47267C32.4951 9.41462 32.1025 9.38708 31.682 9.38708H28.1322C28.0447 9.38708 27.9617 9.40689 27.8874 9.44269C27.7236 9.52163 27.602 9.67707 27.5726 9.86736L26.8174 14.6641L26.7957 14.8039C26.8454 14.4882 27.1144 14.2558 27.4335 14.2558H28.7623C31.372 14.2558 33.4154 13.1929 34.0127 10.1181C34.0305 10.0271 34.0455 9.93856 34.0589 9.85189C33.9078 9.77146 33.7442 9.7027 33.568 9.64411C33.5244 9.62959 33.4795 9.61562 33.4342 9.60206" fill="#22284F"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M27.5726 9.86737C27.6021 9.67708 27.7236 9.52165 27.8874 9.44325C27.9622 9.40731 28.0447 9.38751 28.1322 9.38751H31.682C32.1025 9.38751 32.4951 9.41518 32.8534 9.47323C32.9557 9.48964 33.0551 9.50863 33.1522 9.52992C33.2488 9.55162 33.3429 9.5759 33.4342 9.60248C33.4795 9.61605 33.5244 9.63015 33.5684 9.64412C33.7446 9.70272 33.9084 9.77202 34.0595 9.85191C34.2372 8.71545 34.058 7.94168 33.4453 7.241C32.7698 6.46953 31.5507 6.1394 29.9906 6.1394H25.4615C25.1429 6.1394 24.8711 6.37174 24.8218 6.68803L22.9354 18.6796C22.8982 18.9168 23.0807 19.1309 23.3193 19.1309H26.1153L27.5726 9.86737" fill="#28356A"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.0946 23.5209H9.79248C9.56648 23.5209 9.3743 23.6855 9.339 23.9093L8.00345 32.4009C7.97695 32.5686 8.10638 32.7195 8.27584 32.7195H9.85225C10.0782 32.7195 10.2704 32.555 10.3057 32.3308L10.6659 30.0404C10.7006 29.8162 10.8932 29.6516 11.1188 29.6516H12.1641C14.3393 29.6516 15.5946 28.5959 15.9226 26.5042C16.0703 25.589 15.9288 24.87 15.5014 24.3664C15.0321 23.8134 14.1997 23.5209 13.0946 23.5209ZM13.4755 26.6224C13.2949 27.8106 12.3896 27.8106 11.5143 27.8106H11.0159L11.3655 25.5914C11.3863 25.4573 11.5021 25.3585 11.6374 25.3585H11.8658C12.4621 25.3585 13.0246 25.3585 13.3152 25.6994C13.4886 25.9027 13.5416 26.2049 13.4755 26.6224Z" fill="#28356A"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M23.0496 26.5199H21.4683C21.3336 26.5199 21.2171 26.6187 21.1964 26.7528L21.1264 27.1963L21.0159 27.0356C20.6736 26.5373 19.9101 26.3707 19.1483 26.3707C17.4008 26.3707 15.9084 27.698 15.6177 29.5598C15.4666 30.4885 15.6814 31.3766 16.2068 31.9959C16.6887 32.5653 17.3782 32.8026 18.1985 32.8026C19.6065 32.8026 20.3871 31.8947 20.3871 31.8947L20.3167 32.3354C20.2902 32.5038 20.4196 32.6549 20.5881 32.6549H22.0124C22.2389 32.6549 22.4301 32.4903 22.4659 32.2661L23.3205 26.8385C23.3475 26.6714 23.2185 26.5199 23.0496 26.5199ZM20.8453 29.6064C20.6928 30.5122 19.9759 31.1204 19.0613 31.1204C18.6022 31.1204 18.2353 30.9727 17.9995 30.6929C17.7658 30.415 17.6771 30.0194 17.7513 29.5787C17.8939 28.6805 18.6229 28.0524 19.5235 28.0524C19.9725 28.0524 20.3375 28.2022 20.578 28.4843C20.8188 28.7695 20.9145 29.1676 20.8453 29.6064Z" fill="#28356A"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M31.3495 26.6556H29.7604C29.6088 26.6556 29.4664 26.7312 29.3805 26.8576L27.1888 30.095L26.2598 26.9839C26.2014 26.7892 26.0223 26.6556 25.8195 26.6556H24.2581C24.0682 26.6556 23.9365 26.8416 23.9968 27.0208L25.7471 32.1718L24.1016 34.5014C23.9722 34.6849 24.1025 34.9372 24.3261 34.9372H25.9132C26.0639 34.9372 26.2048 34.8635 26.2903 34.7397L31.5754 27.089C31.702 26.906 31.572 26.6556 31.3495 26.6556" fill="#28356A"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M36.6469 23.5209H33.3444C33.1189 23.5209 32.9267 23.6855 32.8914 23.9093L31.5559 32.4009C31.5294 32.5686 31.6588 32.7195 31.8273 32.7195H33.5221C33.6794 32.7195 33.8141 32.6044 33.8387 32.4475L34.2178 30.0404C34.2525 29.8162 34.4453 29.6516 34.6707 29.6516H35.7156C37.8912 29.6516 39.1461 28.5959 39.4745 26.5042C39.6227 25.589 39.4803 24.87 39.0529 24.3664C38.584 23.8134 37.7521 23.5209 36.6469 23.5209ZM37.0279 26.6224C36.8478 27.8106 35.9424 27.8106 35.0666 27.8106H34.5689L34.9189 25.5914C34.9396 25.4573 35.0545 25.3585 35.1902 25.3585H35.4186C36.0144 25.3585 36.5774 25.3585 36.868 25.6994C37.0414 25.9027 37.094 26.2049 37.0279 26.6224Z" fill="#298FC2"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M46.5999 26.5199H45.0195C44.8839 26.5199 44.7685 26.6187 44.7482 26.7528L44.6782 27.1963L44.5671 27.0356C44.2248 26.5373 43.4619 26.3707 42.6999 26.3707C40.9526 26.3707 39.4607 27.698 39.1701 29.5598C39.0194 30.4885 39.2332 31.3766 39.7585 31.9959C40.2415 32.5653 40.9299 32.8026 41.7503 32.8026C43.1582 32.8026 43.9389 31.8947 43.9389 31.8947L43.8685 32.3354C43.842 32.5038 43.9713 32.6549 44.1408 32.6549H45.5647C45.7902 32.6549 45.9823 32.4903 46.0176 32.2661L46.8727 26.8385C46.8988 26.6714 46.7693 26.5199 46.5999 26.5199ZM44.3958 29.6064C44.2442 30.5122 43.5262 31.1204 42.6116 31.1204C42.1534 31.1204 41.7856 30.9727 41.5498 30.6929C41.3163 30.415 41.2283 30.0194 41.3016 29.5787C41.4451 28.6805 42.1732 28.0524 43.0738 28.0524C43.5228 28.0524 43.8878 28.2022 44.1283 28.4843C44.3701 28.7695 44.4657 29.1676 44.3958 29.6064Z" fill="#298FC2"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M48.3324 23.7543L46.9771 32.4013C46.9506 32.569 47.0799 32.7199 47.2484 32.7199H48.611C48.8375 32.7199 49.0296 32.5554 49.0643 32.3312L50.4008 23.84C50.4275 23.6724 50.298 23.5209 50.1295 23.5209H48.6038C48.4691 23.5213 48.3532 23.6202 48.3324 23.7543" fill="#298FC2"/>
          </svg>
          <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
          PayPal
          <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
          </div>
        </div>
        <div class="relative group flex items-center justify-center bg-transparent cursor-pointer transition">
          <svg viewBox="0 -11 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="0.5" y="0.5" width="69" height="47" rx="5.5" fill="white" stroke="#D9D9D9"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M37.6109 16.2838L34.055 17.047V14.164L37.6109 13.415V16.2838ZM45.0057 17.8808C43.6173 17.8808 42.7248 18.5308 42.229 18.9831L42.0448 18.1069H38.9281V34.5849L42.4698 33.8359L42.484 29.8365C42.994 30.2039 43.7448 30.7268 44.9915 30.7268C47.5273 30.7268 49.8365 28.6918 49.8365 24.2119C49.8223 20.1136 47.4848 17.8808 45.0057 17.8808ZM44.1556 27.6177C43.3198 27.6177 42.8239 27.321 42.4839 26.9535L42.4698 21.7105C42.8381 21.3007 43.3481 21.0181 44.1556 21.0181C45.4448 21.0181 46.3373 22.4595 46.3373 24.3108C46.3373 26.2045 45.4589 27.6177 44.1556 27.6177ZM61 24.3532C61 20.7354 59.2433 17.8808 55.8858 17.8808C52.5142 17.8808 50.4742 20.7354 50.4742 24.325C50.4742 28.5787 52.8825 30.7268 56.3392 30.7268C58.025 30.7268 59.3 30.3452 60.2633 29.8082V26.9818C59.3 27.4623 58.195 27.7591 56.7925 27.7591C55.4183 27.7591 54.2 27.2786 54.0442 25.611H60.9717C60.9717 25.5332 60.9768 25.3565 60.9826 25.1528L60.9826 25.1526V25.1525V25.1524V25.1523V25.1523C60.9906 24.8753 61 24.5486 61 24.3532ZM54.0016 23.0107C54.0016 21.4138 54.9791 20.7496 55.8716 20.7496C56.7358 20.7496 57.6566 21.4138 57.6566 23.0107H54.0016ZM34.0548 18.121H37.6107V30.4866H34.0548V18.121ZM30.0176 18.121L30.2443 19.1668C31.0801 17.6405 32.7376 17.9514 33.1909 18.121V21.3714C32.7518 21.2159 31.3351 21.0181 30.4993 22.1063V30.4866H26.9576V18.121H30.0176ZM23.1607 15.0543L19.704 15.7892L19.6899 27.109C19.6899 29.2005 21.2624 30.7409 23.359 30.7409C24.5207 30.7409 25.3707 30.529 25.8382 30.2746V27.4058C25.3849 27.5895 23.1465 28.2396 23.1465 26.148V21.1311H25.8382V18.121H23.1465L23.1607 15.0543ZM14.7884 20.9475C14.0375 20.9475 13.5842 21.1594 13.5842 21.7106C13.5842 22.3124 14.3644 22.5771 15.3323 22.9055C16.9102 23.4409 18.9871 24.1455 18.9959 26.7557C18.9959 29.2854 16.97 30.741 14.0234 30.741C12.805 30.741 11.4733 30.5007 10.1558 29.9355V26.572C11.3458 27.2221 12.8475 27.7026 14.0234 27.7026C14.8167 27.7026 15.3834 27.4906 15.3834 26.8405C15.3834 26.174 14.5376 25.8693 13.5166 25.5015C11.9616 24.9413 10 24.2346 10 21.8802C10 19.3788 11.9125 17.8808 14.7884 17.8808C15.9642 17.8808 17.1259 18.0645 18.3017 18.5309V21.8519C17.225 21.2725 15.865 20.9475 14.7884 20.9475Z" fill="#6461FC"/>
          </svg>
          <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
          Stripes
          <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
          </div>
        </div>
      </div>
      <p class="text-xs text-gray-500 mt-3 text-center flex items-center justify-center gap-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        100% Secure Checkout
      </p>
    </div>
</section>
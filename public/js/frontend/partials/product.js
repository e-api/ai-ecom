/**
 * Product Module - Tabs, View Toggle, Carousel, Mobile Filter Drawer, Filter Checkboxes
 */

document.addEventListener("DOMContentLoaded", function() {
  // ==================== TAB PANELS ====================
  document.querySelectorAll("[data-tab-target]").forEach(function(button) {
    button.addEventListener("click", function() {
      var targetId = button.getAttribute("data-tab-target");
      var panel = document.getElementById(targetId);
      if (!panel) return;

      document.querySelectorAll("[data-tab-target]").forEach(function(tab) {
        tab.classList.remove("active");
        tab.setAttribute("aria-selected", "false");
      });
      document.querySelectorAll("[data-tab-panel]").forEach(function(item) {
        item.classList.add("hidden");
      });

      button.classList.add("active");
      button.setAttribute("aria-selected", "true");
      panel.classList.remove("hidden");
    });
  });

  // ==================== VIEW TOGGLE (grid / list) ====================
  document.querySelectorAll("[data-view-toggle]").forEach(function(button) {
    button.addEventListener("click", function() {
      var view = button.getAttribute("data-view-toggle");
      var container = button.closest('section') || document;
      var products = container.querySelector("[data-related-products]") || document.querySelector("[data-related-products]");
      if (!products) return;

      var toggles = container.querySelectorAll("[data-view-toggle]");
      if (!toggles || toggles.length === 0) toggles = document.querySelectorAll("[data-view-toggle]");

      toggles.forEach(function(toggle) {
        var active = toggle === button;
        toggle.classList.toggle("active", active);
        toggle.setAttribute("aria-pressed", active ? "true" : "false");
      });

      products.classList.toggle("list-view", view === "list");
    });
  });

  // ==================== CAROUSEL (product detail) ====================
  (function initCarousels() {
    var autoplayDelay = 8000;
    var carousels = document.querySelectorAll('.carousel');

    carousels.forEach(function(carousel) {
      var track = carousel.querySelector('.carousel-track');
      var slides = Array.from(carousel.querySelectorAll('.carousel-slide'));
      if (!track || slides.length === 0) return;

      var prevBtn = carousel.querySelector('.carousel-arrow.left');
      var nextBtn = carousel.querySelector('.carousel-arrow.right');
      var dots = Array.from(carousel.querySelectorAll('.carousel-dot'));
      var index = 0;
      var width = carousel.clientWidth || carousel.offsetWidth;
      var timer = null;
      var isPaused = false;
      var startX = 0;
      var currentTranslate = 0;

      function refreshSizes() {
        width = carousel.clientWidth || carousel.offsetWidth;
        slides.forEach(function(slide) {
          slide.style.width = width + 'px';
          slide.style.flex = '0 0 ' + width + 'px';
        });
        track.style.width = (slides.length * width) + 'px';
        track.style.display = 'flex';
      }

      function update() {
        track.style.transition = 'transform 640ms cubic-bezier(.2,.9,.2,1)';
        track.style.transform = 'translateX(' + (-index * width) + 'px)';
        if (dots && dots.length === slides.length) {
          dots.forEach(function(dot, i) {
            dot.classList.toggle('active', i === index);
            dot.setAttribute('aria-selected', String(i === index));
          });
        }
      }

      function goTo(i) { index = (i + slides.length) % slides.length; update(); }
      function next() { goTo(index + 1); }
      function prev() { goTo(index - 1); }

      function start() {
        if (timer) clearInterval(timer);
        timer = setInterval(function() { if (!isPaused) next(); }, autoplayDelay);
      }

      function stop() { if (timer) { clearInterval(timer); timer = null; } }
      function pause() { isPaused = true; }
      function resume() { isPaused = false; }

      window.addEventListener('resize', function() { refreshSizes(); update(); });

      if (nextBtn) {
        nextBtn.addEventListener('click', function() { next(); pause(); setTimeout(resume, autoplayDelay); });
      }
      if (prevBtn) {
        prevBtn.addEventListener('click', function() { prev(); pause(); setTimeout(resume, autoplayDelay); });
      }

      if (dots && dots.length === slides.length) {
        dots.forEach(function(dot, i) {
          dot.addEventListener('click', function() { goTo(i); pause(); setTimeout(resume, autoplayDelay); });
        });
      }

      // Hover buttons
      carousel.addEventListener('mouseenter', function() {
        if (prevBtn) prevBtn.style.opacity = '1';
        if (nextBtn) nextBtn.style.opacity = '1';
        pause();
      });
      carousel.addEventListener('mouseleave', function() {
        if (prevBtn) prevBtn.style.opacity = '0';
        if (nextBtn) nextBtn.style.opacity = '0';
        resume();
      });
      if (prevBtn) prevBtn.style.opacity = '0';
      if (nextBtn) nextBtn.style.opacity = '0';

      // Scroll wheel
      carousel.addEventListener('wheel', function(e) {
        e.preventDefault();
        if (e.deltaY > 0) { next(); } else if (e.deltaY < 0) { prev(); }
        pause(); setTimeout(resume, autoplayDelay);
      }, { passive: false });

      // Keyboard
      carousel.setAttribute('tabindex', '0');
      carousel.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') { prev(); pause(); setTimeout(resume, autoplayDelay); }
        if (e.key === 'ArrowRight') { next(); pause(); setTimeout(resume, autoplayDelay); }
      });

      // Touch
      track.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        currentTranslate = -index * width;
        track.style.transition = 'none';
        pause();
      }, { passive: true });

      track.addEventListener('touchmove', function(e) {
        var dx = e.touches[0].clientX - startX;
        track.style.transform = 'translateX(' + (currentTranslate + dx) + 'px)';
      }, { passive: true });

      track.addEventListener('touchend', function(e) {
        var dx = e.changedTouches[0].clientX - startX;
        track.style.transition = 'transform 320ms cubic-bezier(.2,.9,.2,1)';
        if (Math.abs(dx) > (width * 0.15) || Math.abs(dx) > 40) {
          if (dx < 0) next(); else prev();
        } else { update(); }
        setTimeout(resume, 250);
      });

      refreshSizes();
      update();
      start();
    });
  })();

  // ==================== MOBILE FILTER DRAWER ====================
  (function() {
    const filterDrawer = document.getElementById('mobile-filter-drawer');
    const filterToggle = document.getElementById('mobile-filter-toggle');
    const filterClose = document.getElementById('mobile-filter-close');
    const filterOverlay = document.getElementById('mobile-filter-overlay');
    const filterApply = document.getElementById('mobile-filter-apply');
    const filterReset = document.getElementById('mobile-filter-reset');
    const desktopReset = document.getElementById('desktop-filter-reset');

    let isDrawerOpen = false;

    function openDrawer() {
      filterDrawer.classList.remove('-translate-x-full');
      filterDrawer.classList.add('translate-x-0');
      filterOverlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      isDrawerOpen = true;
    }

    function closeDrawer() {
      filterDrawer.classList.remove('translate-x-0');
      filterDrawer.classList.add('-translate-x-full');
      filterOverlay.classList.add('hidden');
      document.body.style.overflow = '';
      isDrawerOpen = false;
    }

    if (filterToggle) {
      filterToggle.addEventListener('click', function() {
        if (isDrawerOpen) { closeDrawer(); } else { openDrawer(); }
      });
    }
    if (filterClose) filterClose.addEventListener('click', closeDrawer);
    if (filterOverlay) filterOverlay.addEventListener('click', closeDrawer);

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && isDrawerOpen) closeDrawer();
    });

    function resetAllFilters() {
      document.querySelectorAll('.category-filter').forEach(function(cb) { cb.checked = false; });
      document.querySelectorAll('.price-filter').forEach(function(cb) { cb.checked = false; });
      document.querySelectorAll('.brand-filter').forEach(function(cb) { cb.checked = false; });
      document.querySelectorAll('.size-filter').forEach(function(cb) { cb.checked = false; });
      document.querySelectorAll('.attribute-filter').forEach(function(cb) { cb.checked = false; });
      var newUrl = window.location.pathname;
      window.history.pushState({}, "", newUrl);
      if (typeof loadProducts === 'function') loadProducts();
    }

    if (filterReset) filterReset.addEventListener('click', resetAllFilters);
    if (desktopReset) desktopReset.addEventListener('click', resetAllFilters);
  })();
});

// ==================== JQUERY FILTER CHECKBOXES & PRODUCT LOADING ====================
$(document).ready(function() {
    function getUrlParams() {
        let params = new URLSearchParams(window.location.search);
        let prices = params.get('price');
        let categories = params.get('categories');
        let brands = params.get('brands');
        let sizes = params.get('sizes');
        let attribute_values = params.get('attribute_values');

        return {
            prices: prices ? prices.split(',') : [],
            categories: categories ? categories.split(',') : [],
            brands: brands ? brands.split(',') : [],
            sizes: sizes ? sizes.split(',') : [],
            attribute_values: attribute_values ? attribute_values.split(',') : []
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

        $('.brand-filter').each(function() {
            $(this).prop('checked', selected.brands.includes($(this).val()));
        });
        
        $('.size-filter').each(function() {
            $(this).prop('checked', selected.sizes.includes($(this).val()));
        });
        
        $('.attribute-filter').each(function() {
            $(this).prop('checked', selected.attribute_values.includes($(this).val()));
        });
    }

    syncCheckboxesWithUrl();

    $('.price-filter').on('change', function() { loadProducts(); });
    $('.category-filter').on('change', function() { loadProducts(); });
    $('.brand-filter').on('change', function() { loadProducts(); });
    $('.size-filter').on('change', function() { loadProducts(); });
    $('.attribute-filter').on('change', function() { loadProducts(); });

    function loadProducts() {
        let prices = [];
        let categories = [];
        let brands = [];
        let sizes = [];
        let attribute_values = [];

        $('.price-filter:checked').each(function() { prices.push($(this).val()); });
        $('.category-filter:checked').each(function() { categories.push($(this).val()); });
        $('.brand-filter:checked').each(function() { brands.push($(this).val()); });
        $('.size-filter:checked').each(function() { sizes.push($(this).val()); });
        $('.attribute-filter:checked').each(function() { attribute_values.push($(this).val()); });
        
        let params = new URLSearchParams();
        if (prices.length > 0) params.set('price', prices.join(','));
        if (categories.length > 0) params.set('categories', categories.join(','));
        if (brands.length > 0) params.set('brands', brands.join(','));
        if (sizes.length > 0) params.set('sizes', sizes.join(','));
        if (attribute_values.length > 0) params.set('attribute_values', attribute_values.join(','));

        let newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({}, "", newUrl);

        $.ajax({
            url: newUrl,
            type: "GET",
            beforeSend: function() {
                $('#products-grid-container').html('<div class="flex justify-center items-center min-h-[200px]"><div class="text-center"><div class="animate-spin rounded-full h-8 w-8 border-4 border-gray-200 border-t-primary mx-auto"></div><p class="mt-2 text-sm text-gray-600">Loading Products...</p></div></div>');
            },
            success: function(response) {
                let html = $(response).find('#products-grid-container').html();
                if (html) {
                    $('#products-grid-container').html(html);
                } else {
                    $('#products-grid-container').html(response);
                }
            }
        });
    }

    $(window).on('popstate', function() {
        syncCheckboxesWithUrl();
        loadProducts();
    });
});
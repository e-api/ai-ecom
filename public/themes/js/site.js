document.addEventListener("DOMContentLoaded", function() {
  // set CSS variable for header height so the mobile drawer and sidebar
  // can be positioned correctly under the sticky header regardless of
  // device or font-size. Re-compute on resize.
  function setHeaderHeight() {
    try {
      var header = document.querySelector('.store-header');
      var h = header ? Math.ceil(header.getBoundingClientRect().height) : 64;
      document.documentElement.style.setProperty('--header-height', h + 'px');
    } catch (e) {
      document.documentElement.style.setProperty('--header-height', '64px');
    }
  }
  setHeaderHeight();
  window.addEventListener('resize', function() {
    clearTimeout(window._headerHeightTimeout);
    window._headerHeightTimeout = setTimeout(setHeaderHeight, 120);
  });
  var current = window.location.pathname.split("/").pop() || "index.html";
  document.querySelectorAll("[data-nav]").forEach(function(link) {
    if (link.getAttribute("href") === current) {
      link.classList.add("active");
      link.setAttribute("aria-current", "page");
    } else {
      link.removeAttribute("aria-current");
    }
  });

  // Cart add handled by jQuery #addToCartBtn click handler below (in $(document).ready)
  // Removed legacy vanilla [data-cart-add] handler that only incremented the counter
  // without making an AJAX call to the server.

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

  // Mobile menu toggle (sliding drawer)
  // helper to close the mobile menu and hide the overlay/backdrop
  function closeMobileMenu() {
    var openButton = document.querySelector("[data-mobile-toggle][aria-expanded='true']");
    var nav = null;
    if (openButton) {
      openButton.setAttribute("aria-expanded", "false");
      var navId = openButton.getAttribute("aria-controls");
      nav = navId ? document.getElementById(navId) : null;
      if (nav) {
        nav.classList.remove("open");
        nav.setAttribute('aria-hidden', 'true');
      }
      try {
        openButton.focus();
      } catch (e) {
        /* ignore focus errors */
      }
    } else {
      // fallback: close known mobile nav if present
      nav = document.getElementById('mobile-navigation');
      if (nav) nav.classList.remove('open');
      if (nav) nav.setAttribute('aria-hidden', 'true');
    }

    document.body.classList.remove("mobile-menu-open");
    document.body.classList.remove("sidebar-open");

    var bd = document.querySelector('.sidebar-backdrop');
    if (bd) {
      bd.style.display = 'none';
      bd.onclick = null;
    }

    var main = document.querySelector('main');
    if (main) main.removeAttribute('aria-hidden');
  }

  document.querySelectorAll("[data-mobile-toggle]").forEach(function(button) {
    button.addEventListener("click", function() {
      var navId = button.getAttribute("aria-controls");
      var nav = navId ? document.getElementById(navId) : null;
      if (!nav) return;
      var expanded = button.getAttribute("aria-expanded") === "true";
      button.setAttribute("aria-expanded", String(!expanded));
      if (!expanded) {
        document.body.classList.add("mobile-menu-open");
        nav.classList.add("open");
        var bd = ensureBackdrop();
        bd.style.display = 'block';
        bd.onclick = closeMobileMenu;
        // accessibility: hide main content from assistive tech and focus first item
        try {
          nav.setAttribute('aria-hidden', 'false');
          var main = document.querySelector('main');
          if (main) main.setAttribute('aria-hidden', 'true');
          setTimeout(function() {
            var first = nav.querySelector('a,button,input,select');
            if (first && typeof first.focus === 'function') first.focus();
          }, 200);
        } catch (e) {
          /* no-op */
        }
      } else {
        closeMobileMenu();
      }
    });
  });

  // allow close buttons inside mobile navs to close the drawer
  document.querySelectorAll("[data-mobile-close]").forEach(function(btn) {
    btn.addEventListener('click', function() {
      closeMobileMenu();
    });
  });

  // Close mobile menu when a nav link is clicked (mobile)
  document.querySelectorAll("[data-nav]").forEach(function(link) {
    link.addEventListener("click", function() {
      closeMobileMenu();
    });
  });

  // Sidebar toggle (mobile): look for any button with data-sidebar-toggle
  function ensureBackdrop() {
    var bd = document.querySelector('.sidebar-backdrop');
    if (!bd) {
      bd = document.createElement('div');
      bd.className = 'sidebar-backdrop';
      bd.tabIndex = -1;
      bd.setAttribute('role', 'presentation');
      document.body.appendChild(bd);
    }
    return bd;
  }

  function closeSidebar() {
    document.body.classList.remove('sidebar-open');
    var toggles = document.querySelectorAll('[data-sidebar-toggle][aria-expanded="true"]');
    toggles.forEach(function(t) {
      t.setAttribute('aria-expanded', 'false');
    });
    var bd = document.querySelector('.sidebar-backdrop');
    if (bd) {
      bd.style.display = 'none';
      bd.onclick = null;
    }
    var main = document.querySelector('main');
    if (main) main.removeAttribute('aria-hidden');
  }

  document.querySelectorAll('[data-sidebar-toggle]').forEach(function(button) {
    button.addEventListener('click', function() {
      var expanded = button.getAttribute('aria-expanded') === 'true';
      button.setAttribute('aria-expanded', String(!expanded));
      document.body.classList.toggle('sidebar-open', !expanded);
      var bd = ensureBackdrop();
      bd.style.display = !expanded ? 'block' : 'none';
      bd.onclick = function() {
        closeSidebar();
      };
    });
  });

  // Close mobile menu on Escape
  document.addEventListener("keydown", function(e) {
    if (e.key === "Escape" || e.key === "Esc") {
      // close mobile menu if open
      closeMobileMenu();
      // also close sidebar if open
      if (document.body.classList.contains('sidebar-open')) {
        closeSidebar();
      }
    }
  });

  // Carousel initialization (auto-advance, manual controls, touch, hover buttons, scroll wheel)
  (function initCarousels() {
    var autoplayDelay = 8000;
    var carousels = document.querySelectorAll('.carousel');

    carousels.forEach(function(carousel) {
      var track = carousel.querySelector('.carousel-track');
      var slides = Array.from(carousel.querySelectorAll('.carousel-slide'));

      // Force slides to be visible and track to have proper width
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
        // Set width for each slide
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

        // Update dots
        if (dots && dots.length === slides.length) {
          dots.forEach(function(dot, i) {
            dot.classList.toggle('active', i === index);
            dot.setAttribute('aria-selected', String(i === index));
          });
        }
      }

      function goTo(i) {
        index = (i + slides.length) % slides.length;
        update();
      }

      function next() {
        goTo(index + 1);
      }

      function prev() {
        goTo(index - 1);
      }

      function start() {
        if (timer) clearInterval(timer);
        timer = setInterval(function() {
          if (!isPaused) next();
        }, autoplayDelay);
      }

      function stop() {
        if (timer) {
          clearInterval(timer);
          timer = null;
        }
      }

      function pause() {
        isPaused = true;
      }

      function resume() {
        isPaused = false;
      }

      window.addEventListener('resize', function() {
        refreshSizes();
        update();
      });

      if (nextBtn) {
        nextBtn.addEventListener('click', function() {
          next();
          pause();
          setTimeout(resume, autoplayDelay);
        });
      }
      if (prevBtn) {
        prevBtn.addEventListener('click', function() {
          prev();
          pause();
          setTimeout(resume, autoplayDelay);
        });
      }

      if (dots && dots.length === slides.length) {
        dots.forEach(function(dot, i) {
          dot.addEventListener('click', function() {
            goTo(i);
            pause();
            setTimeout(resume, autoplayDelay);
          });
        });
      }

      // ========== NEW: HOVER BUTTONS - Show arrows on hover ==========
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

      // Initially hide buttons
      if (prevBtn) prevBtn.style.opacity = '0';
      if (nextBtn) nextBtn.style.opacity = '0';

      // ========== NEW: MOUSE SCROLL WHEEL SUPPORT ==========
      carousel.addEventListener('wheel', function(e) {
        e.preventDefault();
        if (e.deltaY > 0) {
          next();
        } else if (e.deltaY < 0) {
          prev();
        }
        pause();
        setTimeout(resume, autoplayDelay);
      }, {
        passive: false
      });

      // ========== KEYBOARD NAVIGATION (already works with tabindex) ==========
      carousel.setAttribute('tabindex', '0');

      carousel.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
          prev();
          pause();
          setTimeout(resume, autoplayDelay);
        }
        if (e.key === 'ArrowRight') {
          next();
          pause();
          setTimeout(resume, autoplayDelay);
        }
      });

      // Touch support (already working)
      track.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        currentTranslate = -index * width;
        track.style.transition = 'none';
        pause();
      }, {
        passive: true
      });

      track.addEventListener('touchmove', function(e) {
        var dx = e.touches[0].clientX - startX;
        track.style.transform = 'translateX(' + (currentTranslate + dx) + 'px)';
      }, {
        passive: true
      });

      track.addEventListener('touchend', function(e) {
        var dx = e.changedTouches[0].clientX - startX;
        track.style.transition = 'transform 320ms cubic-bezier(.2,.9,.2,1)';
        if (Math.abs(dx) > (width * 0.15) || Math.abs(dx) > 40) {
          if (dx < 0) next();
          else prev();
        } else {
          update();
        }
        setTimeout(resume, 250);
      });

      // Initialize
      refreshSizes();
      update();
      start();
    });
  })();

  // Toggle sidebar submenu (used by sidebar categories)
  // Use windows - make it globally available
  window.toggleSidebarSubmenu = function(element) {
    const submenu = element.nextElementSibling;
    const icon = element.querySelector('.sidebar-toggle-icon');

    if (submenu) {
      if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'block';
        if (icon) icon.style.transform = 'rotate(90deg)';
      } else {
        submenu.style.display = 'none';
        if (icon) icon.style.transform = 'rotate(0deg)';
      }
    }
  };
});

// Toggle dropdown when clicking on the arrow area only
document.addEventListener('DOMContentLoaded', function() {
  // Get all toggle buttons
  const toggleButtons = document.querySelectorAll('.sidebar-toggle-btn');

  toggleButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();

      // Get target dropdown ID from data-target attribute
      const targetId = this.getAttribute('data-target');
      const targetDropdown = document.getElementById(targetId);
      const icon = this.querySelector('.sidebar-icon');

      if (targetDropdown) {
        // Toggle visibility
        if (targetDropdown.classList.contains('hidden')) {
          targetDropdown.classList.remove('hidden');
          if (icon) {
            icon.style.transform = 'rotate(180deg)';
          }
        } else {
          targetDropdown.classList.add('hidden');
          if (icon) {
            icon.style.transform = 'rotate(0deg)';
          }
        }
      }
    });
  });
});

// Banner carousel with auto-slide
(function initBannerCarousel() {
  var carousels = document.querySelectorAll('.banner-carousel');

  carousels.forEach(function(carousel) {
    var track = carousel.querySelector('.banner-track');
    var slides = carousel.querySelectorAll('.banner-slide');
    var dots = carousel.querySelectorAll('.banner-dot');

    if (!track || slides.length === 0) return;

    var currentIndex = 0;
    var autoplayDelay = 5000; // 5 seconds
    var timer = null;
    var isPaused = false;

    function goToSlide(index) {
      if (index >= slides.length) index = 0;
      if (index < 0) index = slides.length - 1;
      currentIndex = index;

      // Scroll to slide
      var targetSlide = slides[currentIndex];
      var scrollPosition = targetSlide.offsetLeft - (track.offsetWidth - targetSlide.offsetWidth) / 2;

      track.scrollTo({
        left: scrollPosition,
        behavior: 'smooth'
      });

      // Update dots
      updateDots();
    }

    function nextSlide() {
      goToSlide(currentIndex + 1);
    }

    function updateDots() {
      dots.forEach(function(dot, i) {
        if (i === currentIndex) {
          dot.classList.remove('bg-gray-300');
          dot.classList.add('bg-gray-800');
        } else {
          dot.classList.remove('bg-gray-800');
          dot.classList.add('bg-gray-300');
        }
      });
    }

    function startAutoplay() {
      stopAutoplay();
      timer = setInterval(function() {
        if (!isPaused) {
          nextSlide();
        }
      }, autoplayDelay);
    }

    function stopAutoplay() {
      if (timer) {
        clearInterval(timer);
        timer = null;
      }
    }

    // Dot click handlers
    dots.forEach(function(dot) {
      dot.addEventListener('click', function() {
        var index = parseInt(this.getAttribute('data-index'));
        goToSlide(index);
        stopAutoplay();
        startAutoplay();
      });
    });

    // Update current index on manual scroll
    track.addEventListener('scroll', function() {
      var scrollCenter = this.scrollLeft + (this.offsetWidth / 2);

      slides.forEach(function(slide, index) {
        var slideLeft = slide.offsetLeft;
        var slideRight = slideLeft + slide.offsetWidth;

        if (scrollCenter >= slideLeft && scrollCenter < slideRight) {
          if (currentIndex !== index) {
            currentIndex = index;
            updateDots();
          }
        }
      });
    });

    // Pause autoplay on hover
    carousel.addEventListener('mouseenter', function() {
      isPaused = true;
    });

    carousel.addEventListener('mouseleave', function() {
      isPaused = false;
    });

    // Pause on touch
    track.addEventListener('touchstart', function() {
      isPaused = true;
    }, {
      passive: true
    });

    track.addEventListener('touchend', function() {
      setTimeout(function() {
        isPaused = false;
      }, 2000);
    });

    // Start autoplay
    startAutoplay();
  });
})();

// Update dots on scroll
document.querySelectorAll('.flex.overflow-x-auto.snap-x').forEach(function(container) {
  container.addEventListener('scroll', function() {
    var dots = this.parentElement.querySelectorAll('.banner-dot');
    if (!dots.length) return;

    var scrollPosition = this.scrollLeft + (this.offsetWidth / 2);
    var items = this.querySelectorAll('.flex-shrink-0');

    items.forEach(function(item, index) {
      if (scrollPosition >= item.offsetLeft && scrollPosition < item.offsetLeft + item.offsetWidth) {
        dots.forEach(function(d) {
          d.classList.remove('bg-gray-800');
          d.classList.add('bg-gray-300');
        });
        if (dots[index]) {
          dots[index].classList.remove('bg-gray-300');
          dots[index].classList.add('bg-gray-800');
        }
      }
    });
  });
});

// Mobile Navigation Product Filter 
document.addEventListener('DOMContentLoaded', function() {
  const filterDrawer = document.getElementById('mobile-filter-drawer');
  const filterToggle = document.getElementById('mobile-filter-toggle');
  const filterClose = document.getElementById('mobile-filter-close');
  const filterOverlay = document.getElementById('mobile-filter-overlay');
  const filterApply = document.getElementById('mobile-filter-apply');
  const filterReset = document.getElementById('mobile-filter-reset');
  const desktopReset = document.getElementById('desktop-filter-reset');

  let isDrawerOpen = false;

  // Toggle function
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

  // Toggle drawer on button click (open/close)
  if (filterToggle) {
    filterToggle.addEventListener('click', function() {
      if (isDrawerOpen) {
        closeDrawer();
      } else {
        openDrawer();
      }
    });
  }

  // Close on close button
  if (filterClose) {
    filterClose.addEventListener('click', closeDrawer);
  }

  // Close on overlay click
  if (filterOverlay) {
    filterOverlay.addEventListener('click', closeDrawer);
  }

  // Close on escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && isDrawerOpen) {
      closeDrawer();
    }
  });

  // Reset all filters function
  function resetAllFilters() {
    // Uncheck all category filters
    document.querySelectorAll('.category-filter').forEach(function(checkbox) {
      checkbox.checked = false;
    });

    // Uncheck all price filters
    document.querySelectorAll('.price-filter').forEach(function(checkbox) {
      checkbox.checked = false;
    });

    // Uncheck all brand filters
    document.querySelectorAll('.brand-filter').forEach(function(checkbox) {
        checkbox.checked = false;
    });
    
    // Uncheck all size filters
    document.querySelectorAll('.size-filter').forEach(function(checkbox) {
        checkbox.checked = false;
    });
    
    // Uncheck all attribute filters
    document.querySelectorAll('.attribute-filter').forEach(function(checkbox) {
        checkbox.checked = false;
    });

    // Clear URL parameters and reload products
    let newUrl = window.location.pathname;
    window.history.pushState({}, "", newUrl);

    // Reload products
    if (typeof loadProducts === 'function') {
      loadProducts();
    }
  }

  // Mobile reset button - does NOT close drawer
  if (filterReset) {
    filterReset.addEventListener('click', function() {
      resetAllFilters();
      // REMOVED: drawer close code
    });
  }

  // Desktop reset button
  if (desktopReset) {
    desktopReset.addEventListener('click', function() {
      resetAllFilters();
    });
  }
});

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

    $('.price-filter').on('change', function() {
        loadProducts();
    });

    $('.category-filter').on('change', function() {
        loadProducts();
    });

    $('.brand-filter').on('change', function() {
        loadProducts();
    });

    $('.size-filter').on('change', function() {
        loadProducts();
    });
    
    $('.attribute-filter').on('change', function() {
        loadProducts();
    });

    function loadProducts() {
        let prices = [];
        let categories = [];
        let brands = [];
        let sizes = [];
        let attribute_values = [];

        $('.price-filter:checked').each(function() {
            prices.push($(this).val());
        });

        $('.category-filter:checked').each(function() {
            categories.push($(this).val());
        });

        $('.brand-filter:checked').each(function() {
            brands.push($(this).val());
        });
        
        $('.size-filter:checked').each(function() {
            sizes.push($(this).val());
        });
        
        $('.attribute-filter:checked').each(function() {
            attribute_values.push($(this).val());
        });
        
        let params = new URLSearchParams();

        if (prices.length > 0) {
            params.set('price', prices.join(','));
        }

        if (categories.length > 0) {
            params.set('categories', categories.join(','));
        }

        if (brands.length > 0) {
            params.set('brands', brands.join(','));
        }

        if (sizes.length > 0) {
            params.set('sizes', sizes.join(','));
        }
        
        if (attribute_values.length > 0) {
            params.set('attribute_values', attribute_values.join(','));
        }

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

    /*
    | Add To Cart
    */
    $(document).on('click', '#addToCartBtn', function() {
        let button = $(this);
        let product_id = $('#product_id').val();
        let variantInput = $('#variant_id');
        let variant_id = variantInput.length ? variantInput.val() : null;
        let quantity = parseInt($('#quantity').val(), 10);
        let config = window.siteConfig || {};
        let originalText = button.html();

        if (!product_id) {
            alert('Product is missing. Please refresh the page and try again.');
            return false;
        }

        if (variantInput.length && variant_id === '') {
            alert('Please select size.');
            return false;
        }

        if (!quantity || quantity < 1) {
            alert('Please enter a valid quantity.');
            return false;
        }

        $.ajax({
            url: config.routes ? config.routes.cartAdd : '/cart/add',
            type: "POST",
            data: {
                _token: config.csrfToken || '',
                product_id: product_id,
                variant_id: variant_id,
                quantity: quantity
            },
            beforeSend: function() {
                button
                    .prop('disabled', true)
                    .text('Adding...');
            },
            success: function(response) {
                if (response.status) {
                    $('[data-cart-count]').text(response.cartCount ?? 0);

                    if (window.toastr) {
                        toastr.success(response.message || 'Product added to cart successfully.');
                    } else {
                        alert(response.message || 'Product added to cart successfully.');
                    }
                } else {
                    alert(response.message || 'Something went wrong.');
                    updateCartCount();
                }
            },
            error: function(xhr) {
                let message = xhr.responseJSON?.message || 'Something went wrong. Please try again.';
                alert(message);
            },
            complete: function() {
                button
                    .prop('disabled', false)
                    .html(originalText || 'Add to Cart');
            }
        });
    });

    function updateCartCount() {
        let config = window.siteConfig || {};
        $.ajax({
            url: config.routes ? config.routes.cartCount : '/cart/count',
            type: "GET",
            success: function(response) {
                $('[data-cart-count]').text(response.count ?? 0);
            }
        });
    }

    $(window).on('popstate', function() {
        syncCheckboxesWithUrl();
        loadProducts();
    });
});
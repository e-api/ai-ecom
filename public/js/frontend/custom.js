/**
 * Custom JS - Layout, Navigation, Header, Mobile Menu, Sidebar, Banner Carousel
 */

document.addEventListener("DOMContentLoaded", function() {
  // ==================== HEADER HEIGHT ====================
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

  // ==================== ACTIVE NAV LINK ====================
  var current = window.location.pathname.split("/").pop() || "index.html";
  document.querySelectorAll("[data-nav]").forEach(function(link) {
    if (link.getAttribute("href") === current) {
      link.classList.add("active");
      link.setAttribute("aria-current", "page");
    } else {
      link.removeAttribute("aria-current");
    }
  });

  // ==================== MOBILE MENU (sliding drawer) ====================
  var backdropEl = null;

  function ensureBackdrop() {
    if (!backdropEl) {
      backdropEl = document.querySelector('.sidebar-backdrop');
    }
    if (!backdropEl) {
      backdropEl = document.createElement('div');
      backdropEl.className = 'sidebar-backdrop';
      backdropEl.tabIndex = -1;
      backdropEl.setAttribute('role', 'presentation');
      document.body.appendChild(backdropEl);
    }
    return backdropEl;
  }

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
      } catch (e) {}
    } else {
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
        try {
          nav.setAttribute('aria-hidden', 'false');
          var main = document.querySelector('main');
          if (main) main.setAttribute('aria-hidden', 'true');
          setTimeout(function() {
            var first = nav.querySelector('a,button,input,select');
            if (first && typeof first.focus === 'function') first.focus();
          }, 200);
        } catch (e) {}
      } else {
        closeMobileMenu();
      }
    });
  });

  document.querySelectorAll("[data-mobile-close]").forEach(function(btn) {
    btn.addEventListener('click', function() {
      closeMobileMenu();
    });
  });

  document.querySelectorAll("[data-nav]").forEach(function(link) {
    link.addEventListener("click", function() {
      closeMobileMenu();
    });
  });

  // ==================== SIDEBAR TOGGLE ====================
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

  // ==================== ESCAPE KEY ====================
  document.addEventListener("keydown", function(e) {
    if (e.key === "Escape" || e.key === "Esc") {
      closeMobileMenu();
      if (document.body.classList.contains('sidebar-open')) {
        closeSidebar();
      }
    }
  });

  // ==================== SIDEBAR SUBMENU TOGGLE (global) ====================
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

  // ==================== SIDEBAR DROPDOWN TOGGLE ====================
  const toggleButtons = document.querySelectorAll('.sidebar-toggle-btn');
  toggleButtons.forEach(function(button) {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      var targetId = this.getAttribute('data-target');
      var targetDropdown = document.getElementById(targetId);
      var icon = this.querySelector('.sidebar-icon');
      if (targetDropdown) {
        if (targetDropdown.classList.contains('hidden')) {
          targetDropdown.classList.remove('hidden');
          if (icon) icon.style.transform = 'rotate(180deg)';
        } else {
          targetDropdown.classList.add('hidden');
          if (icon) icon.style.transform = 'rotate(0deg)';
        }
      }
    });
  });

  // ==================== BANNER CAROUSEL ====================
  (function initBannerCarousel() {
    var carousels = document.querySelectorAll('.banner-carousel');
    carousels.forEach(function(carousel) {
      var track = carousel.querySelector('.banner-track');
      var slides = carousel.querySelectorAll('.banner-slide');
      var dots = carousel.querySelectorAll('.banner-dot');
      if (!track || slides.length === 0) return;

      var currentIndex = 0;
      var autoplayDelay = 5000;
      var timer = null;
      var isPaused = false;

      function goToSlide(index) {
        if (index >= slides.length) index = 0;
        if (index < 0) index = slides.length - 1;
        currentIndex = index;
        var targetSlide = slides[currentIndex];
        var scrollPosition = targetSlide.offsetLeft - (track.offsetWidth - targetSlide.offsetWidth) / 2;
        track.scrollTo({ left: scrollPosition, behavior: 'smooth' });
        updateDots();
      }

      function nextSlide() { goToSlide(currentIndex + 1); }

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
        timer = setInterval(function() { if (!isPaused) nextSlide(); }, autoplayDelay);
      }

      function stopAutoplay() {
        if (timer) { clearInterval(timer); timer = null; }
      }

      dots.forEach(function(dot) {
        dot.addEventListener('click', function() {
          var index = parseInt(this.getAttribute('data-index'));
          goToSlide(index);
          stopAutoplay();
          startAutoplay();
        });
      });

      track.addEventListener('scroll', function() {
        var scrollCenter = this.scrollLeft + (this.offsetWidth / 2);
        slides.forEach(function(slide, index) {
          var slideLeft = slide.offsetLeft;
          var slideRight = slideLeft + slide.offsetWidth;
          if (scrollCenter >= slideLeft && scrollCenter < slideRight) {
            if (currentIndex !== index) { currentIndex = index; updateDots(); }
          }
        });
      });

      carousel.addEventListener('mouseenter', function() { isPaused = true; });
      carousel.addEventListener('mouseleave', function() { isPaused = false; });
      track.addEventListener('touchstart', function() { isPaused = true; }, { passive: true });
      track.addEventListener('touchend', function() {
        setTimeout(function() { isPaused = false; }, 2000);
      });

      startAutoplay();
    });
  })();

  // ==================== SCROLL DOTS ====================
  document.querySelectorAll('.flex.overflow-x-auto.snap-x').forEach(function(container) {
    container.addEventListener('scroll', function() {
      var dots = this.parentElement.querySelectorAll('.banner-dot');
      if (!dots.length) return;
      var scrollPosition = this.scrollLeft + (this.offsetWidth / 2);
      var items = this.querySelectorAll('.flex-shrink-0');
      items.forEach(function(item, index) {
        if (scrollPosition >= item.offsetLeft && scrollPosition < item.offsetLeft + item.offsetWidth) {
          dots.forEach(function(d) { d.classList.remove('bg-gray-800'); d.classList.add('bg-gray-300'); });
          if (dots[index]) { dots[index].classList.remove('bg-gray-300'); dots[index].classList.add('bg-gray-800'); }
        }
      });
    });
  });
});
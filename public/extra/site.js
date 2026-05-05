document.addEventListener("DOMContentLoaded", function () {
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
  window.addEventListener('resize', function () {
    clearTimeout(window._headerHeightTimeout);
    window._headerHeightTimeout = setTimeout(setHeaderHeight, 120);
  });
  var current = window.location.pathname.split("/").pop() || "index.html";
  document.querySelectorAll("[data-nav]").forEach(function (link) {
    if (link.getAttribute("href") === current) {
      link.classList.add("active");
      link.setAttribute("aria-current", "page");
    } else {
      link.removeAttribute("aria-current");
    }
  });

  document.querySelectorAll("[data-cart-add]").forEach(function (button) {
    button.addEventListener("click", function () {
      var count = document.querySelector("[data-cart-count]");
      if (!count) return;
      count.textContent = String(Number(count.textContent || "0") + 1);
    });
  });

  document.querySelectorAll("[data-tab-target]").forEach(function (button) {
    button.addEventListener("click", function () {
      var targetId = button.getAttribute("data-tab-target");
      var panel = document.getElementById(targetId);
      if (!panel) return;

      document.querySelectorAll("[data-tab-target]").forEach(function (tab) {
        tab.classList.remove("active");
        tab.setAttribute("aria-selected", "false");
      });
      document.querySelectorAll("[data-tab-panel]").forEach(function (item) {
        item.classList.add("hidden");
      });

      button.classList.add("active");
      button.setAttribute("aria-selected", "true");
      panel.classList.remove("hidden");
    });
  });

  document.querySelectorAll("[data-view-toggle]").forEach(function (button) {
    button.addEventListener("click", function () {
      var view = button.getAttribute("data-view-toggle");
      // find the closest section (or container) for this toggle, then target
      // the related-products element within that container. Fallback to the
      // first global related-products if none is found locally.
      var container = button.closest('section') || document;
      var products = container.querySelector("[data-related-products]") || document.querySelector("[data-related-products]");
      if (!products) return;

      // only update toggles that are within the same container (so multiple
      // product lists on the page can be toggled independently)
      var toggles = container.querySelectorAll("[data-view-toggle]");
      if (!toggles || toggles.length === 0) toggles = document.querySelectorAll("[data-view-toggle]");

      toggles.forEach(function (toggle) {
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
      try { openButton.focus(); } catch (e) { /* ignore focus errors */ }
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

  document.querySelectorAll("[data-mobile-toggle]").forEach(function (button) {
    button.addEventListener("click", function () {
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
          setTimeout(function () {
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
  document.querySelectorAll("[data-mobile-close]").forEach(function (btn) {
    btn.addEventListener('click', function () { closeMobileMenu(); });
  });

  // Close mobile menu when a nav link is clicked (mobile)
  document.querySelectorAll("[data-nav]").forEach(function (link) {
    link.addEventListener("click", function () {
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
    toggles.forEach(function (t) { t.setAttribute('aria-expanded', 'false'); });
    var bd = document.querySelector('.sidebar-backdrop');
    if (bd) { bd.style.display = 'none'; bd.onclick = null; }
    var main = document.querySelector('main');
    if (main) main.removeAttribute('aria-hidden');
  }

  document.querySelectorAll('[data-sidebar-toggle]').forEach(function (button) {
    button.addEventListener('click', function () {
      var expanded = button.getAttribute('aria-expanded') === 'true';
      button.setAttribute('aria-expanded', String(!expanded));
      document.body.classList.toggle('sidebar-open', !expanded);
      var bd = ensureBackdrop();
      bd.style.display = !expanded ? 'block' : 'none';
      bd.onclick = function () { closeSidebar(); };
    });
  });

  // Close mobile menu on Escape
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" || e.key === "Esc") {
      // close mobile menu if open
      closeMobileMenu();
      // also close sidebar if open
      if (document.body.classList.contains('sidebar-open')) {
        closeSidebar();
      }
    }
  });

  // Carousel initialization (auto-advance, manual controls, touch)
  (function initCarousels() {
    var autoplayDelay = 3000;
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
        slides.forEach(function(s){ s.style.width = width + 'px'; });
        track.style.width = (slides.length * width) + 'px';
      }

      function update() {
        track.style.transition = 'transform 640ms cubic-bezier(.2,.9,.2,1)';
        track.style.transform = 'translateX(' + (-index * width) + 'px)';
        slides.forEach(function(s,i){
          s.setAttribute('aria-hidden', i !== index);
          s.setAttribute('aria-roledescription', 'slide');
          s.setAttribute('aria-label', (i+1) + ' of ' + slides.length);
        });
        if (dots && dots.length === slides.length) {
          dots.forEach(function(d,i){ d.classList.toggle('active', i === index); d.setAttribute('aria-selected', String(i===index)); });
        }
      }

      function goTo(i) { index = (i + slides.length) % slides.length; update(); }
      function next() { goTo(index + 1); }
      function prev() { goTo(index - 1); }

      function start() { if (timer) clearInterval(timer); timer = setInterval(function(){ if (!isPaused) next(); }, autoplayDelay); }
      function stop() { if (timer) { clearInterval(timer); timer = null; } }
      function pause() { isPaused = true; }
      function resume() { isPaused = false; }

      window.addEventListener('resize', function () { refreshSizes(); update(); });

      if (nextBtn) nextBtn.addEventListener('click', function(){ next(); pause(); setTimeout(resume, autoplayDelay); });
      if (prevBtn) prevBtn.addEventListener('click', function(){ prev(); pause(); setTimeout(resume, autoplayDelay); });

      if (dots && dots.length === slides.length) {
        dots.forEach(function(d,i){ d.addEventListener('click', function(){ goTo(i); pause(); setTimeout(resume, autoplayDelay); }); });
      }

      // touch support
      track.addEventListener('touchstart', function (e) {
        startX = e.touches[0].clientX;
        currentTranslate = -index * width;
        track.style.transition = 'none';
        pause();
      }, {passive:true});
      track.addEventListener('touchmove', function (e) {
        var dx = e.touches[0].clientX - startX;
        track.style.transform = 'translateX(' + (currentTranslate + dx) + 'px)';
      }, {passive:true});
      track.addEventListener('touchend', function (e) {
        var dx = e.changedTouches[0].clientX - startX;
        track.style.transition = 'transform 320ms cubic-bezier(.2,.9,.2,1)';
        if (Math.abs(dx) > (width * 0.15) || Math.abs(dx) > 40) {
          if (dx < 0) next(); else prev();
        } else {
          update();
        }
        setTimeout(resume, 250);
      });

      carousel.addEventListener('mouseenter', function(){ pause(); });
      carousel.addEventListener('mouseleave', function(){ resume(); });
      carousel.addEventListener('focusin', function(){ pause(); });
      carousel.addEventListener('focusout', function(){ resume(); });

      carousel.setAttribute('tabindex','0');
      carousel.addEventListener('keydown', function(e){
        if (e.key === 'ArrowLeft') { prev(); pause(); setTimeout(resume, autoplayDelay); }
        if (e.key === 'ArrowRight') { next(); pause(); setTimeout(resume, autoplayDelay); }
      });

      // initial setup
      refreshSizes();
      update();
      start();
    });
  })();
});

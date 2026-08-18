/* =============================================================================
   CGS — site behaviour
   Vanilla JS. jQuery is loaded for the theme's carousel plugins; nothing here
   depends on it.
   ========================================================================== */
(function () {
  'use strict';

  /* Marks that JS is running, so the reveal styles only hide content when
     something is actually there to reveal it. Set synchronously, before
     first paint, to avoid a flash of un-hidden content. */
  document.documentElement.classList.add('js');

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  /* --- Sticky header shadow ------------------------------------------------
     The header is position:sticky in CSS; this only toggles the shadow that
     separates it from the page once it has lifted off the top.

     A sentinel + IntersectionObserver, not a scroll listener: a scroll
     handler fires on every frame and does layout work on the main thread.
     The observer fires twice in the entire page lifetime.                   */
  var header = document.getElementById('cgs-header');

  /* Exposed as a CSS var so the services scroll-pin can sit its sticky
     stage directly under the header instead of behind it. Read from the
     real element rather than hard-coded, so a header height change never
     silently desyncs the two. */
  var setHeaderHeightVar = function () {
    if (!header) { return; }
    document.documentElement.style.setProperty('--cgs-header-h', header.offsetHeight + 'px');
  };
  setHeaderHeightVar();
  window.addEventListener('resize', setHeaderHeightVar);

  if (header && 'IntersectionObserver' in window) {
    var sentinel = document.createElement('div');
    sentinel.setAttribute('aria-hidden', 'true');
    sentinel.style.cssText = 'position:absolute;top:0;left:0;width:1px;height:1px;pointer-events:none;';
    document.body.prepend(sentinel);

    new IntersectionObserver(function (entries) {
      header.classList.toggle('is-stuck', !entries[0].isIntersecting);
    }, { threshold: 0 }).observe(sentinel);
  }

  /* --- Mobile menu: dropdown, not a side panel ------------------------------
     Drops down from the navbar (top:100% of .cgs-header in CSS) instead of
     sliding in from a screen edge — the same interaction language as the
     desktop Services hover dropdown just above it in the markup.

     No forced focus-move on open: focus stays on the toggle button, so the
     visitor's very next Tab press naturally lands on the first link inside
     the now-unhidden panel. This sidesteps a real timing bug found while
     building a similar panel elsewhere on this site — removing `inert`
     does not make an element focusable within the same synchronous task
     (the browser updates the accessibility tree on a later step than
     layout), so a forced `.focus()` call right after unhiding an inert
     element can silently land on <body>. Not moving focus at all avoids
     the bug outright rather than working around its timing.             */
  var menuToggle = document.querySelector('[data-cgs-menu-toggle]');
  var menuPanel  = document.querySelector('[data-cgs-menu]');
  var menuScrim  = document.querySelector('[data-cgs-menu-scrim]');
  var menuClose  = document.querySelector('[data-cgs-menu-close]');

  if (menuToggle && menuPanel) {
    var desktopQuery = window.matchMedia('(min-width: 1200px)');
    var lastMenuFocus = null;

    var openMenu = function () {
      lastMenuFocus = document.activeElement;
      menuPanel.classList.add('is-open');
      if (menuScrim) { menuScrim.classList.add('is-open'); }
      menuToggle.setAttribute('aria-expanded', 'true');
      menuToggle.setAttribute('aria-label', 'Close menu');
      menuPanel.removeAttribute('inert');
    };

    var closeMenu = function (returnFocus) {
      menuPanel.classList.remove('is-open');
      if (menuScrim) { menuScrim.classList.remove('is-open'); }
      menuToggle.setAttribute('aria-expanded', 'false');
      menuToggle.setAttribute('aria-label', 'Open menu');
      menuPanel.setAttribute('inert', '');
      if (returnFocus && lastMenuFocus) { lastMenuFocus.focus(); }
    };

    menuToggle.addEventListener('click', function () {
      if (menuPanel.classList.contains('is-open')) { closeMenu(false); }
      else { openMenu(); }
    });

    if (menuClose) {
      menuClose.addEventListener('click', function () { closeMenu(true); });
    }

    if (menuScrim) {
      menuScrim.addEventListener('click', function () { closeMenu(false); });
    }

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && menuPanel.classList.contains('is-open')) {
        closeMenu(true);
      }
    });

    /* Resizing past the desktop breakpoint (rotating a tablet, or a real
       window resize) should not leave the panel stuck open behind the now
       hover-driven desktop nav. */
    var handleBreakpointChange = function (event) {
      if (event.matches && menuPanel.classList.contains('is-open')) { closeMenu(false); }
    };
    if (typeof desktopQuery.addEventListener === 'function') {
      desktopQuery.addEventListener('change', handleBreakpointChange);
    }
  }

  /* --- Scroll reveal -------------------------------------------------------
     Purpose: sequence. Cells arrive in reading order rather than all at once,
     which makes a grid of five scan as a list instead of a wall. Decorative,
     so it is dropped entirely under reduced motion, and `once` means it never
     replays and never blocks interaction.                                   */
  var revealTargets = document.querySelectorAll('[data-reveal]');

  if (revealTargets.length) {
    var revealAll = function () {
      revealTargets.forEach(function (el) { el.classList.add('is-visible'); });
    };

    if (reduceMotion.matches || !('IntersectionObserver' in window)) {
      revealAll();
    } else {
      var revealObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) { return; }
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -32px 0px' });

      revealTargets.forEach(function (el) { revealObserver.observe(el); });

      /* Safety net. The observer coalesces callbacks when the scroll position
         jumps a long way in one frame, which a flick on a slow phone does, and
         anything it skips would stay at opacity 0 permanently. This is real
         content, not decoration, so it must not depend on an animation firing:
         after a short grace period everything still hidden is shown outright.
         Normal scrolling reveals items long before this runs. */
      window.setTimeout(revealAll, 2500);
    }
  }

  /* --- Hero carousel -------------------------------------------------------
     Purpose: storytelling. Three capabilities get the hero slot in rotation
     instead of one winning it permanently. Only the copy and the photograph
     move; the CTA, the facts card and the layout are fixed, so autoplay never
     shifts a target the visitor is reaching for.

     Clickable dot pagination gives sighted users the same "how many, which
     one" visibility the services section already provides a few scrolls
     later — the two carousels on this page now match instead of one being
     silent. Autoplay still pauses on hover, on keyboard focus, and while
     the tab or section is off-screen, and it never starts at all under
     reduced motion — WCAG 2.2.2's automatic-only mitigations, kept as a
     second layer on top of the manual control rather than a substitute
     for it. */
  var heroEl = document.querySelector('[data-hero-swiper]');

  if (heroEl && typeof window.Swiper === 'function') {
    var slideCount = heroEl.querySelectorAll('.swiper-slide').length;
    var autoplayOn = slideCount > 1 && !reduceMotion.matches;
    var heroPaginationEl = heroEl.querySelector('.cgs-hero__pagination');

    var heroSwiper = new Swiper(heroEl, {
      loop: slideCount > 1,
      speed: reduceMotion.matches ? 0 : 700,
      effect: 'slide',
      autoHeight: false,
      grabCursor: slideCount > 1,
      watchSlidesProgress: true,
      /* The slide copy is decorative motion on top of a content change, so
         it is the first thing dropped under reduced motion (see CSS). */
      autoplay: autoplayOn
        ? { delay: 6000, disableOnInteraction: false, pauseOnMouseEnter: true }
        : false,
      pagination: (slideCount > 1 && heroPaginationEl)
        ? { el: heroPaginationEl, clickable: true }
        : false,
      a11y: { enabled: true },
      keyboard: { enabled: true, onlyInViewport: true }
    });

    /* Pause while the section is off-screen: an autoplaying carousel nobody
       is looking at is wasted work and wasted battery. */
    if ('IntersectionObserver' in window && autoplayOn) {
      new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (!heroSwiper.autoplay) { return; }
          if (entry.isIntersecting) { heroSwiper.autoplay.start(); }
          else { heroSwiper.autoplay.stop(); }
        });
      }, { threshold: 0.2 }).observe(heroEl);
    }

    /* Stop autoplay while anything inside the hero has keyboard focus, so a
       keyboard user is not moved off the link they just tabbed to. */
    if (autoplayOn) {
      heroEl.addEventListener('focusin', function () { heroSwiper.autoplay.stop(); });
      heroEl.addEventListener('focusout', function (event) {
        if (heroEl.contains(event.relatedTarget)) { return; }
        heroSwiper.autoplay.start();
      });
    }
  }

  /* --- Services scroll-pin --------------------------------------------------
     Purpose: one service at a time, advanced by scrolling through the
     section rather than swiping across it. On desktop, with motion
     allowed, the section is given its own extra height (services-count x
     100vh, in CSS) and its content sticks in place while that height
     scrolls past — each service crossfades in in turn, then the page
     continues on to the next section once the last one has shown, the
     same shape as a product-page scroll gallery.

     Mobile and reduced-motion visitors get the identical crossfade, just
     driven by the arrows instead of scroll position — no pin, no extra
     section height, so there is nothing to scroll-hijack on a touch
     device. No Swiper here: the pinned case needs scroll-position math
     Swiper has no hook for, so both paths share one small state machine
     instead of running two different libraries side by side. */
  var servicesPin = document.querySelector('[data-services-pin]');

  if (servicesPin) {
    var serviceSlides = Array.prototype.slice.call(servicesPin.querySelectorAll('[data-service-slide]'));
    var servicesCount = serviceSlides.length;
    var servicesPrev = document.querySelector('[data-services-prev]');
    var servicesNext = document.querySelector('[data-services-next]');
    var servicesFill = servicesPin.querySelector('[data-services-fill]');
    var servicesActive = 0;

    var setServiceActive = function (index) {
      index = Math.max(0, Math.min(servicesCount - 1, index));
      servicesActive = index;
      serviceSlides.forEach(function (slide, i) {
        var isActive = i === index;
        slide.classList.toggle('is-active', isActive);
        /* Keeps a keyboard user from tabbing into a slide that is either
           display:none or sitting invisibly underneath the active one. */
        slide.toggleAttribute('inert', !isActive);
      });
      if (servicesPrev) { servicesPrev.disabled = index === 0; }
      if (servicesNext) { servicesNext.disabled = index === servicesCount - 1; }
    };

    setServiceActive(0);

    /* Pin eligibility is decided once per load/resize, not fought over
       every frame: desktop width, more than one service, motion allowed. */
    var pinQuery = window.matchMedia('(min-width: 992px)');
    var pinActive = false;

    var updateFromScroll = function () {
      var rect = servicesPin.getBoundingClientRect();
      var total = rect.height - window.innerHeight;
      if (total <= 0) { return; }
      var progressed = Math.min(Math.max(-rect.top, 0), total);
      var progress = progressed / total;
      setServiceActive(Math.round(progress * (servicesCount - 1)));
      if (servicesFill) { servicesFill.style.width = (progress * 100) + '%'; }
    };
    var onScroll = function () { window.requestAnimationFrame(updateFromScroll); };

    var applyPinMode = function () {
      var shouldPin = servicesCount > 1 && pinQuery.matches && !reduceMotion.matches;
      if (shouldPin === pinActive) { return; }
      pinActive = shouldPin;
      servicesPin.classList.toggle('is-pinned', pinActive);
      servicesPin.style.removeProperty('height');
      if (pinActive) {
        window.addEventListener('scroll', onScroll, { passive: true });
        updateFromScroll();
      } else {
        window.removeEventListener('scroll', onScroll);
        if (servicesFill) { servicesFill.style.width = '0%'; }
      }
    };
    applyPinMode();
    window.addEventListener('resize', applyPinMode);

    /* Arrow clicks: scroll the page to the target service's position while
       pinned (there is no other way to change "how far scrolled" is), or
       just swap the active slide directly otherwise. */
    var stepService = function (delta) {
      var target = servicesActive + delta;
      if (target < 0 || target > servicesCount - 1) { return; }
      if (pinActive) {
        var rect = servicesPin.getBoundingClientRect();
        var total = rect.height - window.innerHeight;
        var pinTop = window.scrollY + rect.top;
        window.scrollTo({ top: pinTop + (target / (servicesCount - 1)) * total, behavior: 'smooth' });
      } else {
        setServiceActive(target);
      }
    };
    if (servicesPrev) { servicesPrev.addEventListener('click', function () { stepService(-1); }); }
    if (servicesNext) { servicesNext.addEventListener('click', function () { stepService(1); }); }
  }

  /* --- Partners marquee ------------------------------------------------------
     Purpose: a continuous drift, not a carousel someone steps through. Loop
     mode plus slidesPerView:'auto' plus a near-zero autoplay delay reads as
     one steady strip rather than discrete slide changes; speed scales with
     slide count so adding more logos later doesn't change how fast any one
     logo crosses the screen. No pauseOnMouseEnter: it needs to keep moving
     regardless of where the cursor happens to be sitting, since nothing
     here is meant to be read individually or interacted with — allowTouchMove
     is off for the same reason. Not initialised at all under reduced
     motion — this is pure decoration, so it falls back to the plain static
     row the base Swiper CSS already lays out, no separate markup needed. */
  var partnersEl = document.querySelector('[data-partners-swiper]');
  if (partnersEl && !reduceMotion.matches && window.Swiper) {
    var partnersSlideCount = partnersEl.querySelectorAll('.swiper-slide').length;
    if (partnersSlideCount > 1) {
      new Swiper(partnersEl, {
        loop: true,
        slidesPerView: 'auto',
        spaceBetween: 24,
        allowTouchMove: false,
        speed: partnersSlideCount * 350,
        autoplay: { delay: 1, disableOnInteraction: false, pauseOnMouseEnter: false }
      });
    }
  }

  /* --- Keyboard access for the desktop dropdown ----------------------------
     Hover opens it for pointer users and :focus-within covers tabbing, but
     neither gives a keyboard user a way to close it without tabbing all the
     way through. Escape returns focus to the parent link.                   */
  document.querySelectorAll('.cgs-nav .has-dropdown').forEach(function (item) {
    item.addEventListener('keydown', function (event) {
      if (event.key !== 'Escape') { return; }
      var trigger = item.querySelector(':scope > a');
      if (trigger) { trigger.focus(); }
    });
  });

  /* --- Homepage video band + hero background video --------------------------
     These are decorative background footage, not media players, so no
     `controls` fallback: autoplay refused or reduced motion just leaves
     the poster frame showing, muted and static.                          */
  document.querySelectorAll('.cgs-video-band video, .cgs-hero__media video').forEach(function (video) {
    video.removeAttribute('controls');

    var play = function () {
      var attempt = video.play();
      // Older Safari returns undefined rather than a promise; autoplay
      // being refused just leaves the poster frame up.
      if (attempt && typeof attempt.catch === 'function') {
        attempt.catch(function () {});
      }
    };

    var applyMotionPreference = function () {
      if (reduceMotion.matches) {
        video.pause();
        video.removeAttribute('autoplay');
      } else if (video.paused) {
        play();
      }
    };

    applyMotionPreference();

    if (typeof reduceMotion.addEventListener === 'function') {
      reduceMotion.addEventListener('change', applyMotionPreference);
    }

    /* Don't decode a video nobody is looking at. */
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (reduceMotion.matches) { return; }
          if (entry.isIntersecting) { play(); } else { entry.target.pause(); }
        });
      }, { threshold: 0.25 }).observe(video);
    }
  });

  /* --- Fleet split: the offset pair drops into place -----------------------
     Purpose: the CSS already composes the two images as two objects at
     different depths (the second one dropped lower and overlapping the
     first — see .cgs-split__media's own comment). The entrance dramatises
     that same idea instead of a generic fade: the back image settles
     quietly in place, the front image visibly drops into its offset a
     beat later, then the copy follows. One shot, CSS-driven off a single
     class so there is nothing to keep in sync by hand. */
  var splitMedia = document.querySelector('.cgs-split__media');

  if (splitMedia) {
    if (reduceMotion.matches || !('IntersectionObserver' in window)) {
      splitMedia.classList.add('is-visible');
    } else {
      new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) { return; }
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        });
      }, { threshold: 0.2, rootMargin: '0px 0px -32px 0px' }).observe(splitMedia);
    }
  }

  /* --- Operations gallery: contained carousel --------------------------------
     Purpose: the same photography as a paced, one-frame-at-a-time carousel
     instead of a free scroll strip — dedicated prev/next arrows flank the
     frame for manual control, and autoplay steps it on its own. Same
     autoplay etiquette as the hero carousel above: pauses on hover, on
     keyboard focus, and while the section is off-screen, and never starts
     at all under reduced motion. */
  var galleryEl = document.querySelector('[data-gallery-swiper]');

  if (galleryEl && typeof window.Swiper === 'function') {
    var gallerySlideCount = galleryEl.querySelectorAll('.swiper-slide').length;
    var galleryAutoplayOn = gallerySlideCount > 1 && !reduceMotion.matches;

    var gallerySwiper = new Swiper(galleryEl, {
      loop: gallerySlideCount > 2,
      slidesPerView: 'auto',
      spaceBetween: 16,
      speed: reduceMotion.matches ? 0 : 500,
      grabCursor: gallerySlideCount > 1,
      watchOverflow: true,
      autoplay: galleryAutoplayOn
        ? { delay: 3200, disableOnInteraction: false, pauseOnMouseEnter: true }
        : false,
      navigation: {
        prevEl: document.querySelector('[data-gallery-prev]'),
        nextEl: document.querySelector('[data-gallery-next]')
      },
      a11y: { enabled: true },
      keyboard: { enabled: true, onlyInViewport: true }
    });

    if ('IntersectionObserver' in window && galleryAutoplayOn) {
      new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (!gallerySwiper.autoplay) { return; }
          if (entry.isIntersecting) { gallerySwiper.autoplay.start(); }
          else { gallerySwiper.autoplay.stop(); }
        });
      }, { threshold: 0.2 }).observe(galleryEl);
    }

    if (galleryAutoplayOn) {
      galleryEl.addEventListener('focusin', function () { gallerySwiper.autoplay.stop(); });
      galleryEl.addEventListener('focusout', function (event) {
        if (galleryEl.contains(event.relatedTarget)) { return; }
        gallerySwiper.autoplay.start();
      });
    }
  }

}());

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
     stage directly under the header instead of behind it (and the hero
     can size itself to what's actually left of the viewport below it).
     header.offsetTop, not just offsetHeight: .cgs-topbar sits in normal
     flow above #cgs-header and is real height on desktop/lg+ (it's
     display:none below that, so offsetTop is naturally 0 there) — using
     offsetTop + offsetHeight captures topbar-plus-header as one figure
     without measuring the topbar separately. Read from the real elements
     rather than hard-coded, so a height change on either never silently
     desyncs this. */
  var setHeaderHeightVar = function () {
    if (!header) { return; }
    document.documentElement.style.setProperty('--cgs-header-h', (header.offsetTop + header.offsetHeight) + 'px');
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
     instead of one winning it permanently — just the background photograph
     now (client, 2026-08-21: "in hero remove overlay all text and
     pagination" — the scrim, the slide copy and the dot pagination are all
     gone from the markup; heroPaginationEl below resolves to null and
     Swiper's pagination option just turns itself off). Autoplay still
     pauses on hover, on keyboard focus, and while the tab or section is
     off-screen, and it never starts at all under reduced motion — WCAG
     2.2.2's automatic-only mitigations, kept as a second layer on top of
     the manual (arrow-key) control rather than a substitute for it. */
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

     Below 992px (no room to pin, and where the mobile/tablet card redesign
     lives — see cgs.css) the same arrow buttons instead drive a real
     Swiper carousel that autoplays, same etiquette as the hero and gallery
     swipers below: pauses on hover/keyboard focus/off-screen, never
     autoplays under reduced motion. Swiper only ever owns the sub-992px
     case — the pinned case needs scroll-position math Swiper has no hook
     for, so the two paths still share one state machine for that half,
     just handing off to Swiper for the other. The markup's wrapper div
     around the slides (data-services-track) has no classes of its own;
     cgs.js adds swiper/swiper-wrapper/swiper-slide only while the carousel
     is actually active, so Swiper's own display:flex CSS never touches the
     pin/crossfade layout or the no-JS fallback. */
  var servicesPin = document.querySelector('[data-services-pin]');

  if (servicesPin) {
    var servicesStage = document.querySelector('[data-services-stage]');
    var servicesTrack = document.querySelector('[data-services-track]');
    var serviceSlides = Array.prototype.slice.call(servicesPin.querySelectorAll('[data-service-slide]'));
    var servicesCount = serviceSlides.length;
    var servicesPrev = document.querySelector('[data-services-prev]');
    var servicesNext = document.querySelector('[data-services-next]');
    var servicesFill = servicesPin.querySelector('[data-services-fill]');
    var servicesActive = 0;
    var servicesSwiper = null;
    var carouselActive = false;

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
      if (!carouselActive) {
        if (servicesPrev) { servicesPrev.disabled = index === 0; }
        if (servicesNext) { servicesNext.disabled = index === servicesCount - 1; }
      }
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

    /* Carousel eligibility: below the pin width, more than one service,
       Swiper actually loaded. Toggled the same way as pin mode above —
       decided on load/resize, not fought over every frame. */
    var carouselQuery = window.matchMedia('(max-width: 991.98px)');

    var applyCarouselMode = function () {
      var shouldCarousel = servicesCount > 1 && carouselQuery.matches && typeof window.Swiper === 'function';
      if (shouldCarousel === carouselActive) { return; }
      carouselActive = shouldCarousel;

      if (carouselActive) {
        servicesStage.classList.add('swiper');
        servicesTrack.classList.add('swiper-wrapper');
        serviceSlides.forEach(function (slide) {
          slide.classList.add('swiper-slide');
          /* setServiceActive marked every non-active slide inert for the
             state-machine case above, and never runs again once Swiper
             owns the section — left alone, that inert attribute makes
             whichever slide autoplay brings up next untouchable (inert
             elements don't receive pointer/touch events at all), so no
             swipe starting on it would ever reach Swiper. Swiper tracks
             its own active slide via .swiper-slide-active instead. */
          slide.removeAttribute('inert');
          slide.classList.remove('is-active');
        });
        if (servicesPrev) { servicesPrev.disabled = false; }
        if (servicesNext) { servicesNext.disabled = false; }

        var carouselAutoplayOn = !reduceMotion.matches;
        servicesSwiper = new Swiper(servicesStage, {
          loop: true,
          autoHeight: true,
          spaceBetween: 16,
          speed: reduceMotion.matches ? 0 : 500,
          autoplay: carouselAutoplayOn
            ? { delay: 4200, disableOnInteraction: false, pauseOnMouseEnter: true }
            : false,
          navigation: { prevEl: servicesPrev, nextEl: servicesNext },
          a11y: { enabled: true },
          keyboard: { enabled: true, onlyInViewport: true }
        });

        if ('IntersectionObserver' in window && carouselAutoplayOn) {
          new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
              if (!servicesSwiper || !servicesSwiper.autoplay) { return; }
              if (entry.isIntersecting) { servicesSwiper.autoplay.start(); }
              else { servicesSwiper.autoplay.stop(); }
            });
          }, { threshold: 0.2 }).observe(servicesStage);
        }

        if (carouselAutoplayOn) {
          servicesStage.addEventListener('focusin', function () {
            if (servicesSwiper && servicesSwiper.autoplay) { servicesSwiper.autoplay.stop(); }
          });
          servicesStage.addEventListener('focusout', function (event) {
            if (servicesStage.contains(event.relatedTarget)) { return; }
            if (servicesSwiper && servicesSwiper.autoplay) { servicesSwiper.autoplay.start(); }
          });
        }
      } else if (servicesSwiper) {
        servicesSwiper.destroy(true, true);
        servicesSwiper = null;
        servicesStage.classList.remove('swiper');
        servicesTrack.classList.remove('swiper-wrapper');
        serviceSlides.forEach(function (slide) { slide.classList.remove('swiper-slide'); });
        setServiceActive(servicesActive);
      }
    };
    applyCarouselMode();
    window.addEventListener('resize', applyCarouselMode);

    /* Arrow clicks: while the carousel owns the section, Swiper's own
       navigation binding (above) already moves it — this only drives the
       pin (scroll to the target service's position, the only way to
       change "how far scrolled" is) or plain state-machine cases. */
    var stepService = function (delta) {
      if (carouselActive) { return; }
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

  /* --- Testimonials carousel -------------------------------------------------
     Three cards side by side on desktop (client: "make the testimonial
     three cards"), two on tablet, one on mobile — still a Swiper so the
     narrower breakpoints keep pagination dots, arrows and autoplay doing
     real work even though desktop, with all 3 real quotes visible at
     once, has nothing left to advance to. rewind (not loop) takes it back
     to slide 1 after the last position without Swiper's loop mode cloning
     slides, which only makes sense with a slide count well past
     slidesPerView — same etiquette as the hero and services carousels
     above: pauses on hover, on keyboard focus, and while the section is
     off-screen, and autoplay never starts at all under reduced motion. */
  var testimonialsEl = document.querySelector('[data-testimonials-swiper]');
  if (testimonialsEl && typeof window.Swiper === 'function') {
    var testimonialsSlideCount = testimonialsEl.querySelectorAll('.swiper-slide').length;
    var testimonialsAutoplayOn = testimonialsSlideCount > 1 && !reduceMotion.matches;
    var testimonialsPaginationEl = testimonialsEl.querySelector('.cgs-testimonials__pagination');
    var testimonialsPrev = document.querySelector('[data-testimonials-prev]');
    var testimonialsNext = document.querySelector('[data-testimonials-next]');

    var testimonialsSwiper = new Swiper(testimonialsEl, {
      slidesPerView: 1,
      spaceBetween: 24,
      breakpoints: {
        768:  { slidesPerView: 2, spaceBetween: 24 },
        992:  { slidesPerView: 3, spaceBetween: 28 }
      },
      rewind: testimonialsSlideCount > 1,
      speed: reduceMotion.matches ? 0 : 500,
      grabCursor: testimonialsSlideCount > 1,
      autoplay: testimonialsAutoplayOn
        ? { delay: 5500, disableOnInteraction: false, pauseOnMouseEnter: true }
        : false,
      pagination: (testimonialsSlideCount > 1 && testimonialsPaginationEl)
        ? { el: testimonialsPaginationEl, clickable: true }
        : false,
      navigation: { prevEl: testimonialsPrev, nextEl: testimonialsNext },
      a11y: { enabled: true },
      keyboard: { enabled: true, onlyInViewport: true }
    });

    if ('IntersectionObserver' in window && testimonialsAutoplayOn) {
      new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (!testimonialsSwiper.autoplay) { return; }
          if (entry.isIntersecting) { testimonialsSwiper.autoplay.start(); }
          else { testimonialsSwiper.autoplay.stop(); }
        });
      }, { threshold: 0.2 }).observe(testimonialsEl);
    }

    if (testimonialsAutoplayOn) {
      testimonialsEl.addEventListener('focusin', function () { testimonialsSwiper.autoplay.stop(); });
      testimonialsEl.addEventListener('focusout', function (event) {
        if (testimonialsEl.contains(event.relatedTarget)) { return; }
        testimonialsSwiper.autoplay.start();
      });
    }
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
     row the base Swiper CSS already lays out, no separate markup needed.

     With slidesPerView:'auto', Swiper needs the *real* slide count to cover
     however many tiles fit on screen at once, or it silently disables loop
     mode entirely and the whole strip freezes at translateX(0) — logged as
     a console warning, not a thrown error, so it's easy to miss. On a big
     enough monitor more of our ~230px tiles fit than there were partner
     logos to begin with, which is why index.php renders the $partners list
     repeated 3x into the DOM rather than relying on Swiper's own loop
     duplication (loopAdditionalSlides pads the same side of that
     comparison as the visible-tile estimate, so it can't fix a shortfall
     in real slides — only more real slides can). */
  var partnersEl = document.querySelector('[data-partners-swiper]');
  if (partnersEl && !reduceMotion.matches && window.Swiper) {
    var partnersSlideCount = partnersEl.querySelectorAll('.swiper-slide').length;
    if (partnersSlideCount > 1) {
      var partnersSwiper = new Swiper(partnersEl, {
        loop: true,
        slidesPerView: 'auto',
        spaceBetween: 24,
        allowTouchMove: false,
        speed: partnersSlideCount * 350,
        autoplay: { delay: 1, disableOnInteraction: false, pauseOnMouseEnter: false }
      });

      /* Swiper measures every slide's width at the instant it initialises,
         to decide whether loop mode has enough real content to cover the
         container. A logo <img> that hasn't finished loading yet reports
         as narrower than its final size at that exact moment, so the
         strip can init looking "too narrow" purely by load-timing luck,
         silently drop loop mode, and sit frozen with autoplay technically
         running but nothing to loop through (client: "sometimes the
         partner swiper autoplay is not running", on both mobile and
         desktop — a timing race, not a screen-size problem). Re-measuring
         once every logo has actually finished loading, and nudging
         autoplay back on if that measurement left it stopped, catches it
         without a guessed fixed delay. */
      var partnersImgs = partnersEl.querySelectorAll('img');
      var partnersPending = 0;
      var recheckPartnersSwiper = function () {
        partnersSwiper.update();
        if (partnersSwiper.autoplay && !partnersSwiper.autoplay.running) {
          partnersSwiper.autoplay.start();
        }
      };
      partnersImgs.forEach(function (img) {
        if (img.complete) { return; }
        partnersPending++;
        var onSettle = function () {
          partnersPending--;
          if (partnersPending === 0) { recheckPartnersSwiper(); }
        };
        img.addEventListener('load', onSettle, { once: true });
        img.addEventListener('error', onSettle, { once: true });
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

  /* --- Hero background video --------------------------------------------
     Decorative footage, not a media player: no `controls`, autoplay
     refused or reduced motion just leaves the poster frame showing,
     muted and static, and it pauses itself off-screen so nothing decodes
     that nobody's looking at. */
  document.querySelectorAll('.cgs-hero__media video').forEach(function (video) {
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

    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (reduceMotion.matches) { return; }
          if (entry.isIntersecting) { play(); } else { entry.target.pause(); }
        });
      }, { threshold: 0.25 }).observe(video);
    }

    /* Sound toggle: the only control a visitor gets over otherwise-decorative
       footage. Starts muted (autoplay requires it); a click is a direct user
       gesture, so unmuting from there is never blocked by autoplay policy. */
    var heroSection = video.closest('.cgs-hero');
    var soundBtn = heroSection && heroSection.querySelector('[data-hero-sound]');
    if (soundBtn) {
      var syncSoundButton = function () {
        var muted = video.muted;
        soundBtn.classList.toggle('is-unmuted', !muted);
        soundBtn.setAttribute('aria-pressed', String(!muted));
        soundBtn.setAttribute('aria-label', muted ? 'Unmute background video' : 'Mute background video');
      };
      syncSoundButton();
      soundBtn.addEventListener('click', function () {
        video.muted = !video.muted;
        if (!video.muted) { play(); }
        syncSoundButton();
      });
    }
  });

  /* --- Video band ---------------------------------------------------------
     Real content, not decoration: it keeps its `controls` markup, so
     play/pause/mute stay in the visitor's hands. Autoplays muted on load
     unless reduced motion is set (then it just sits on its poster frame,
     controls still there to start it manually) — deliberately no
     IntersectionObserver override here, since forcing it back to play
     every time it scrolls into view would fight a visitor who paused it
     themselves. */
  document.querySelectorAll('.cgs-video-band video').forEach(function (video) {
    if (reduceMotion.matches) {
      video.removeAttribute('autoplay');
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

  /* --- Resources page: "big tabs" -------------------------------------------
     Standard ARIA tabs keyboard pattern (arrow keys move focus and activate;
     Home/End jump to the ends) over the client's requested Incoterms /
     Insurance / Chargeable Weight tab group. Plain class toggles, no
     animation library — the panel swap is instant, same as any other
     show/hide on this site. */
  var tabList = document.querySelector('[data-cgs-tabs]');

  if (tabList) {
    var tabButtons = Array.prototype.slice.call(tabList.querySelectorAll('[data-cgs-tab]'));
    var tabPanels  = Array.prototype.slice.call(tabList.querySelectorAll('[data-cgs-panel]'));

    var activateTab = function (targetBtn, moveFocus) {
      tabButtons.forEach(function (btn) {
        var isTarget = btn === targetBtn;
        btn.classList.toggle('is-active', isTarget);
        btn.setAttribute('aria-selected', isTarget ? 'true' : 'false');
        btn.setAttribute('tabindex', isTarget ? '0' : '-1');
      });
      tabPanels.forEach(function (panel) {
        var isTarget = panel.id === targetBtn.getAttribute('aria-controls');
        panel.classList.toggle('is-active', isTarget);
      });
      if (moveFocus) { targetBtn.focus(); }
    };

    tabButtons.forEach(function (btn, index) {
      btn.addEventListener('click', function () { activateTab(btn, false); });

      btn.addEventListener('keydown', function (event) {
        var nextIndex = null;
        if (event.key === 'ArrowRight' || event.key === 'ArrowDown') { nextIndex = (index + 1) % tabButtons.length; }
        else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') { nextIndex = (index - 1 + tabButtons.length) % tabButtons.length; }
        else if (event.key === 'Home') { nextIndex = 0; }
        else if (event.key === 'End') { nextIndex = tabButtons.length - 1; }
        if (nextIndex !== null) {
          event.preventDefault();
          activateTab(tabButtons[nextIndex], true);
        }
      });
    });
  }

}());

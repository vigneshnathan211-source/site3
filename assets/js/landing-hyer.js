/* =============================================================================
   CGS — "Hyer" landing concept behaviour
   Vanilla JS, no dependency on the site's main assets/js/cgs.js so this page
   stays fully standalone and removable.
   ========================================================================== */
(function () {
  'use strict';

  document.documentElement.classList.add('js');

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  /* --- Nav glass state -------------------------------------------------------
     Sentinel + IntersectionObserver, not a scroll listener: a scroll handler
     runs layout work on every frame, and this only needs to fire once each
     way the boundary is crossed. */
  var nav = document.querySelector('.hy-nav');
  if (nav && 'IntersectionObserver' in window) {
    var sentinel = document.createElement('div');
    sentinel.setAttribute('aria-hidden', 'true');
    sentinel.style.cssText = 'position:absolute;top:0;left:0;width:1px;height:1px;pointer-events:none;';
    document.body.prepend(sentinel);
    new IntersectionObserver(function (entries) {
      nav.classList.toggle('is-stuck', !entries[0].isIntersecting);
    }).observe(sentinel);
  }

  /* --- Full-screen menu overlay ----------------------------------------------
     Purpose: the top nav only carries three links; this is where the rest of
     the site lives. Focus is trapped while open and returned to the trigger
     on close, and Escape closes it, so a keyboard user is never dropped
     into a page with no visible way back. */
  var burger  = document.querySelector('[data-hy-burger]');
  var overlay = document.querySelector('[data-hy-overlay]');

  if (burger && overlay) {
    var closeBtn = overlay.querySelector('[data-hy-close]');
    var lastFocused = null;

    var getFocusable = function () {
      return overlay.querySelectorAll('a[href], button:not([disabled])');
    };

    var openOverlay = function () {
      lastFocused = document.activeElement;
      overlay.classList.add('is-open');
      document.body.classList.add('hy-lock');
      burger.setAttribute('aria-expanded', 'true');
      burger.setAttribute('aria-label', 'Close menu');
      overlay.removeAttribute('inert');
      /* Removing `inert` does not make an element focusable within the same
         synchronous task — Chromium updates the accessibility tree on a
         later step than style/layout, so a .focus() call made right here
         silently fails and focus falls back to <body> with no error.
         Forcing a reflow (offsetHeight) does not help; only waiting a
         frame does. rAF is enough in testing, but a dialog opening is rare
         enough that the extra margin of a second frame costs nothing and
         removes any doubt across browsers. */
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          var focusable = getFocusable();
          if (focusable.length) { focusable[0].focus(); }
        });
      });
    };

    var closeOverlay = function () {
      overlay.classList.remove('is-open');
      document.body.classList.remove('hy-lock');
      burger.setAttribute('aria-expanded', 'false');
      burger.setAttribute('aria-label', 'Open menu');
      overlay.setAttribute('inert', '');
      if (lastFocused) { lastFocused.focus(); }
    };

    burger.addEventListener('click', function () {
      var isOpen = overlay.classList.contains('is-open');
      if (isOpen) { closeOverlay(); } else { openOverlay(); }
    });

    if (closeBtn) { closeBtn.addEventListener('click', closeOverlay); }

    overlay.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closeOverlay();
        return;
      }
      if (event.key !== 'Tab') { return; }
      var focusable = getFocusable();
      if (!focusable.length) { return; }
      var first = focusable[0];
      var last  = focusable[focusable.length - 1];
      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    });

    /* Inert until first opened, so it never intercepts Tab or a click while
       hidden behind the page. */
    overlay.setAttribute('inert', '');
  }

  /* --- Scroll reveal -----------------------------------------------------
     Purpose: sequence. Rows and cells resolve in reading order instead of
     appearing as one slab. Real content, not decoration, so a timeout
     safety net force-reveals anything the observer skips (a fast flick on a
     slow phone coalesces callbacks and can otherwise strand content at
     opacity 0 permanently). */
  var revealTargets = document.querySelectorAll('[data-hy-reveal]');

  if (revealTargets.length) {
    var revealAll = function () {
      revealTargets.forEach(function (el) { el.classList.add('is-visible'); });
    };

    if (reduceMotion.matches || !('IntersectionObserver' in window)) {
      revealAll();
    } else {
      var observer = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) { return; }
          entry.target.classList.add('is-visible');
          obs.unobserve(entry.target);
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -32px 0px' });

      revealTargets.forEach(function (el) { observer.observe(el); });
      window.setTimeout(revealAll, 2500);
    }
  }

}());

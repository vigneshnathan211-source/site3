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

  if (header && 'IntersectionObserver' in window) {
    var sentinel = document.createElement('div');
    sentinel.setAttribute('aria-hidden', 'true');
    sentinel.style.cssText = 'position:absolute;top:0;left:0;width:1px;height:1px;pointer-events:none;';
    document.body.prepend(sentinel);

    new IntersectionObserver(function (entries) {
      header.classList.toggle('is-stuck', !entries[0].isIntersecting);
    }, { threshold: 0 }).observe(sentinel);
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

  /* --- Homepage video band -------------------------------------------------
     Autoplay is muted + playsinline so mobile permits it. Someone who has
     asked for reduced motion gets a still frame and the controls instead,
     which CSS alone cannot do.                                              */
  document.querySelectorAll('.cgs-video-band video').forEach(function (video) {
    var play = function () {
      var attempt = video.play();
      // Older Safari returns undefined rather than a promise.
      if (attempt && typeof attempt.catch === 'function') {
        attempt.catch(function () {
          // Autoplay refused: leave the poster up and give the user controls.
          video.setAttribute('controls', '');
        });
      }
    };

    var applyMotionPreference = function () {
      if (reduceMotion.matches) {
        video.pause();
        video.removeAttribute('autoplay');
        video.setAttribute('controls', '');
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

}());

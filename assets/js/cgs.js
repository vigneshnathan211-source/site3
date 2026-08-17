/* =============================================================================
   CGS — site behaviour
   Vanilla JS. jQuery is loaded for the carousel plugins only; nothing here
   depends on it.
   ========================================================================== */
(function () {
  'use strict';

  /* --- Sticky header shadow ------------------------------------------------
     The header is position:sticky in CSS, so this only adds the shadow that
     separates it from the page once it has actually lifted off the top.     */
  var header = document.getElementById('cgs-header');

  if (header) {
    var ticking = false;

    var syncHeader = function () {
      header.classList.toggle('is-stuck', window.scrollY > 12);
      ticking = false;
    };

    window.addEventListener('scroll', function () {
      if (!ticking) {
        window.requestAnimationFrame(syncHeader);
        ticking = true;
      }
    }, { passive: true });

    syncHeader();
  }

  /* --- Keyboard access for the desktop dropdown ----------------------------
     Hover opens it for pointer users and :focus-within covers tabbing, but
     neither gives a keyboard user a way to close it again without tabbing
     all the way through. Escape returns focus to the parent link.          */
  document.querySelectorAll('.cgs-nav .has-dropdown').forEach(function (item) {
    item.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        var trigger = item.querySelector(':scope > a');
        if (trigger) { trigger.focus(); }
      }
    });
  });

  /* --- Homepage video band -------------------------------------------------
     Autoplay is muted + playsinline so mobile browsers allow it. Someone who
     has asked for reduced motion gets a still first frame and the controls
     instead, which CSS alone cannot do.                                     */
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  document.querySelectorAll('.cgs-video-band video').forEach(function (video) {
    var applyMotionPreference = function () {
      if (reduceMotion.matches) {
        video.pause();
        video.removeAttribute('autoplay');
        video.setAttribute('controls', '');
      } else if (video.paused) {
        var attempt = video.play();
        // Older Safari returns undefined rather than a promise.
        if (attempt && typeof attempt.catch === 'function') {
          attempt.catch(function () {
            // Autoplay refused anyway — leave the poster and offer controls.
            video.setAttribute('controls', '');
          });
        }
      }
    };

    applyMotionPreference();

    if (typeof reduceMotion.addEventListener === 'function') {
      reduceMotion.addEventListener('change', applyMotionPreference);
    }

    /* Don't burn battery decoding a video nobody is looking at. */
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (reduceMotion.matches) { return; }
          if (entry.isIntersecting) {
            var attempt = entry.target.play();
            if (attempt && typeof attempt.catch === 'function') { attempt.catch(function () {}); }
          } else {
            entry.target.pause();
          }
        });
      }, { threshold: 0.25 }).observe(video);
    }
  });

}());

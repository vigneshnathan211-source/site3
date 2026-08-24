<?php
/*
|--------------------------------------------------------------------------
| FOOT SCRIPTS
|--------------------------------------------------------------------------
| Loaded last on every page. Only the plugins actually in use are listed —
| the theme ships several more in assets/js/plugins/, add them here if a
| page starts needing one.
*/
?>
<script src="<?php echo url('assets/js/plugins/jquery-3-7-1.min.js'); ?>"></script>
<script src="<?php echo url('assets/js/plugins/bootstrap.min.js'); ?>"></script>
<script src="<?php echo url('assets/js/plugins/fontawesome.js'); ?>"></script>
<script src="<?php echo url('assets/js/plugins/aos.js'); ?>"></script>
<script src="<?php echo url('assets/js/plugins/owlcarousel.min.js'); ?>"></script>
<script src="<?php echo url('assets/js/plugins/magnific-popup.js'); ?>"></script>
<script src="<?php echo url('assets/js/plugins/swiper-bundle.min.js'); ?>"></script>
<script src="<?php echo asset_url('assets/js/cgs.js'); ?>"></script>

<script>
  if (window.AOS) {
    AOS.init({
      duration: 800,
      once: true,
      offset: 60,
      // Scroll animations are decoration; skip them entirely when the
      // visitor has asked for reduced motion.
      disable: function () {
        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      }
    });
  }

  // Certificate badges (homepage credentials strip): open the real PDF in
  // a lightbox instead of a new tab. jQuery-dependent (Magnific Popup is a
  // jQuery plugin) — kept out of cgs.js, which stays plugin-free on
  // purpose. Anchors keep their real href as a no-JS fallback.
  //
  // The #toolbar=0&navpanes=0&scrollbar=0 fragment is the standard PDF
  // "open parameters" spec: Chrome/Edge/Safari's built-in PDF viewer reads
  // it and hides its own toolbar, thumbnail sidebar and scrollbar, so the
  // popup shows just the certificate. view=FitH fits the page to the
  // iframe's width instead of opening at whatever zoom the browser
  // defaults to. There's no JS control over that native viewer beyond
  // this fragment — a browser without a built-in PDF viewer just
  // downloads the file, which is an acceptable fallback here.
  if (window.jQuery && jQuery.fn.magnificPopup) {
    jQuery.magnificPopup.defaults.iframe.patterns.pdf = {
      index: '.pdf',
      id: '',
      src: '%id%#toolbar=0&navpanes=0&scrollbar=0&view=FitH'
    };
    jQuery('.cgs-pdf-trigger').magnificPopup({
      type: 'iframe',
      mainClass: 'cgs-pdf-popup',
      iframe: {
        markup: '<div class="mfp-iframe-scaler">'
          + '<div class="mfp-close"></div>'
          + '<iframe class="mfp-iframe" frameborder="0" allowfullscreen title="Certificate PDF"></iframe>'
          + '</div>'
      }
    });
  }

  // Homepage gallery: open the clicked photo full-size, with gallery
  // navigation so the visitor can arrow through the rest of the set
  // instead of closing and re-clicking every tile.
  if (window.jQuery && jQuery.fn.magnificPopup) {
    jQuery('.cgs-gallery-trigger').magnificPopup({
      type: 'image',
      mainClass: 'cgs-gallery-popup',
      gallery: { enabled: true },
      image: { titleSrc: function (item) { return item.el.find('img').attr('alt'); } }
    });
  }

  // Past Projects video grid: play the YouTube video in an in-page
  // lightbox instead of leaving the site. Magnific Popup's built-in
  // iframe "youtube" pattern (any href containing youtube.com) handles
  // the embed conversion on its own — no custom markup needed here,
  // unlike the PDF pattern above.
  if (window.jQuery && jQuery.fn.magnificPopup) {
    jQuery('.cgs-video-trigger').magnificPopup({
      type: 'iframe',
      mainClass: 'cgs-video-popup'
    });
  }
</script>
</body>
</html>

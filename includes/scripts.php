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
<script src="<?php echo url('assets/js/cgs.js'); ?>"></script>

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
</script>
</body>
</html>

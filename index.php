<?php
/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
| Structure per docs/PROJECT-BRIEF.md:
|   1. Header (top bar + navbar)      — includes/header.php
|   2. Hero
|   3. 20-second video band           — explicitly required by the brief
|   4. Services · Fleet · Why CGS · Gallery · CTA   ← still to build
|   5. Footer
|
| Copy below is PLACEHOLDER. The client has supplied no homepage text — see
| docs/CONTENT.md. Nothing here states a verifiable fact (fleet size,
| tonnage, years, client names) that was not supplied.
*/

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Project Logistics & Heavy Lift Freight Forwarding Singapore | '
           . $settings['company_name'];
$pageDesc  = 'Carriage Global (S) Pte Ltd moves heavy lift, break bulk and project '
           . 'cargo by sea, air and road from Singapore. ISO 9001:2015 certified.';
$bodyClass = 'page-home';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <!-- ==========================================================
       HERO
       ========================================================== -->
  <section class="cgs-hero">
    <div class="cgs-hero__media">
      <img src="<?php echo url($settings['hero_bg_image']); ?>"
           alt="Project cargo being lifted onto a barge alongside a geared vessel"
           width="1600" height="1200" fetchpriority="high">
    </div>

    <div class="container-fluid px-4">
      <div class="cgs-hero__inner">
        <!-- The ISO line already sits in the top bar and again in the facts
             row below, so the eyebrow carries the sector framing instead of
             saying the same thing a third time. -->
        <p class="cgs-hero__eyebrow">
          <i class="fa-solid fa-anchor" aria-hidden="true"></i>
          Project Cargo &middot; Heavy Lift &middot; Break Bulk
        </p>

        <h1 class="cgs-hero__title"><?php echo e($settings['hero_heading']); ?></h1>

        <p class="cgs-hero__lead"><?php echo e($settings['hero_subheading']); ?></p>

        <div class="cgs-hero__actions">
          <a href="<?php echo url('contact.php'); ?>" class="cgs-btn cgs-btn--primary">
            Send Us Your Packing List <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
          </a>
          <a href="<?php echo url('services.php'); ?>" class="cgs-btn cgs-btn--on-dark">
            Our Services
          </a>
        </div>

        <ul class="cgs-hero__facts">
          <li>
            <strong>Singapore &amp; Johor Bahru</strong>
            <span>Two offices, one operations team</span>
          </li>
          <li>
            <strong>24/7</strong>
            <span><a href="tel:<?php echo e($phone247Tel); ?>"><?php echo e($settings['phone_247']); ?></a></span>
          </li>
          <li>
            <strong>ISO 9001:2015</strong>
            <span>Quality management certified</span>
          </li>
        </ul>
      </div>
    </div>
  </section>

  <!-- ==========================================================
       VIDEO BAND — the 20-second section the brief places
       directly after the hero.
       Muted + playsinline so mobile allows autoplay; cgs.js pauses
       it off-screen and honours prefers-reduced-motion.
       ========================================================== -->
  <section class="cgs-video-band" aria-label="Carriage Global operations">
    <video
      src="<?php echo url($settings['hero_video']); ?>"
      <?php if (!empty($settings['hero_video_poster'])): ?>
      poster="<?php echo url($settings['hero_video_poster']); ?>"
      <?php endif; ?>
      autoplay muted loop playsinline preload="metadata"></video>

    <div class="cgs-video-band__overlay">
      <div class="container-fluid px-4">
        <p class="cgs-eyebrow cgs-eyebrow--on-dark">In Operation</p>
        <h2>Cargo that does not fit a container, moved anyway</h2>
        <a href="<?php echo url('services.php'); ?>" class="cgs-btn cgs-btn--on-dark">
          See How We Do It <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- Sections 4–8 (services, fleet, why CGS, gallery, CTA) still to build. -->

</main>

<?php
require __DIR__ . '/includes/footer.php';
require __DIR__ . '/includes/scripts.php';

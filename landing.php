<?php
/*
|--------------------------------------------------------------------------
| LANDING CONCEPT — "Hyer"
|--------------------------------------------------------------------------
| A standalone design exploration, not part of the site's day-to-day build.
| Applies the system documented at
| https://styles.refero.design/style/f61cf515-ccd5-4494-bdd1-be9fe4d7258c
| (a monochromatic editorial-luxury language built for a private aviation
| brand) to Carriage Global: deep ink + white, one calculated warm accent,
| extreme-tight display type, full-pill buttons, hairline dividers,
| alternating light/dark full-bleed bands.
|
| Deliberately does not use includes/head.php, header.php or footer.php —
| those carry the site's actual visual language (assets/css/cgs.css), and
| this page is a different one entirely, scoped under body.page-hyer so it
| cannot leak onto any other page. It does reuse includes/bootstrap.php for
| $settings and $services, so contact details and service names stay
| DB-driven rather than hardcoded, consistent with the rest of the CMS.
|
| Copy status: every headline, the hero subtext and the approach framing are
| PLACEHOLDER — this is a visual concept, not new client-approved copy. The
| service names and blurbs are the real, client-supplied text already in the
| services table. No fleet size, tonnage, year count or client name is
| stated anywhere on this page.
*/

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Carriage Global: Beyond the Container';
$pageDesc  = 'A design concept for Carriage Global (S) Pte Ltd, applying an editorial '
           . 'monochromatic system to project cargo, heavy lift and break bulk logistics.';

/* Four of the five services, for an even 2x2 grid. */
$featureServices = array_slice($services, 0, 4);

/* Same filter header.php uses: an icon renders only when that social URL is
   actually set in $settings, so an unconfigured platform shows nothing. */
$activeSocials = array_filter(
    $socialPlatforms,
    static fn($meta, $col): bool => !empty($settings[$col]),
    ARRAY_FILTER_USE_BOTH
);

/* Stroke icon per service, matching the reference's minimal stroke-based
   iconography rather than reusing one glyph across four different cells. */
$serviceIcons = [
    'project-freight-forwarding'   => 'fa-clipboard-list',
    'heavy-lift-chartering'        => 'fa-anchor',
    'tug-and-barge-chartering'     => 'fa-water',
    'roll-on-roll-off'             => 'fa-truck-ramp-box',
    'air-freight'                  => 'fa-plane',
];

$approach = [
    ['title' => 'Packing list analysis', 'body' => 'Cargo dimensions matched directly against equipment capability before anything is quoted, so the mode fits the cargo rather than the other way round.'],
    ['title' => 'Cost efficiency',       'body' => 'Urgency balanced against commercial constraint. Where a charter is not warranted, we say so rather than sell one.'],
    ['title' => 'Feasibility studies',   'body' => 'Routes, handling gear and vessel types checked against the real constraints of your site before anything is committed.'],
];

$sectors = ['Oil and gas', 'Offshore vessels', 'Heavy lift', 'Energy', 'Construction', 'Mining'];
?>
<!DOCTYPE html>
<html lang="en-SG">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>document.documentElement.classList.add('js');</script>

<title><?php echo e($pageTitle); ?></title>
<meta name="description" content="<?php echo e($pageDesc); ?>">
<meta name="robots" content="noindex, nofollow">
<!-- noindex: this is a design concept exploring an alternate visual system,
     not a page the site should be found by. Remove if it graduates. -->

<link rel="shortcut icon" href="<?php echo url($settings['favicon']); ?>" type="image/x-icon">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?php echo url('assets/css/plugins/fontawesome.css'); ?>">
<link rel="stylesheet" href="<?php echo url('assets/css/landing-hyer.css'); ?>">
</head>
<body class="page-hyer">

<a class="cgs-skip-link" href="#hy-main" style="position:absolute;left:-9999px;top:0;z-index:2000;padding:12px 20px;background:#000d10;color:#fff;text-decoration:none;">Skip to main content</a>

<!-- ==========================================================
     HEADER — thin contact/social topbar, then the nav. Both
     fixed in one wrapper so the topbar can collapse on scroll
     while the nav stays anchored to the viewport edge.
     ========================================================== -->
<div class="hy-header" data-hy-header>

  <div class="hy-topbar">
    <ul class="hy-topbar__contacts">
      <li>
        <a href="tel:<?php echo e($phoneTel); ?>">
          <i class="fa-solid fa-phone" aria-hidden="true"></i><?php echo e($settings['phone']); ?>
        </a>
      </li>
      <li>
        <a href="mailto:<?php echo e($settings['email']); ?>">
          <i class="fa-solid fa-envelope" aria-hidden="true"></i><?php echo e($settings['email']); ?>
        </a>
      </li>
    </ul>
    <div class="hy-topbar__right">
      <?php if ($activeSocials): ?>
      <ul class="hy-topbar__socials">
        <?php foreach ($activeSocials as $col => $meta): ?>
        <li>
          <a href="<?php echo e($settings[$col]); ?>" target="_blank" rel="noopener"
             aria-label="<?php echo e($meta[1]); ?>">
            <i class="<?php echo e($meta[0]); ?>" aria-hidden="true"></i>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>
  </div>

  <!-- NAV — three links, a circular button opens the rest. -->
  <header class="hy-nav">
    <a class="hy-nav__brand" href="<?php echo url('landing.php'); ?>">
      <img src="<?php echo url($settings['logo']); ?>" alt="" width="36" height="36">
      <span class="hy-nav__word">CARRIAGE GLOBAL</span>
    </a>

    <nav class="hy-nav__links" aria-label="Primary">
      <a href="#services">Services</a>
      <a href="#fleet">Fleet</a>
      <a href="<?php echo url('contact.php'); ?>">Contact</a>
    </nav>

    <div class="hy-nav__right">
      <a href="<?php echo url('contact.php'); ?>" class="hy-pill hy-pill--ink" style="padding:12px 22px; font-size:13.5px;">
        Get a Quote
      </a>
      <button class="hy-nav__burger" type="button" data-hy-burger aria-expanded="false" aria-controls="hy-overlay" aria-label="Open menu">
        <svg class="hy-icon-open" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <svg class="hy-icon-close" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </button>
    </div>

    <!-- MENU DROPDOWN — anchored under the nav, opens on hover
         (pointer devices) or click/tap (touch), not a full-page
         takeover. -->
    <div class="hy-overlay" id="hy-overlay" data-hy-overlay role="menu" aria-label="Site menu">
      <button type="button" data-hy-close class="hy-nav__burger" style="position:absolute; top:16px; right:16px; width:34px; height:34px; border-color:rgba(255,255,255,.4); color:#fff;" aria-label="Close menu">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true" width="14" height="14"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </button>

      <ul class="hy-overlay__links">
        <li><a href="<?php echo url('index.php'); ?>">Home <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></li>
        <li><a href="#services">Services <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></li>
        <li><a href="#fleet">Fleet <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></li>
        <li><a href="<?php echo url('resources.php'); ?>">Resources <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></li>
        <li><a href="<?php echo url('contact.php'); ?>">Contact <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></li>
      </ul>

      <dl class="hy-overlay__foot">
        <div>
          <dt>Singapore</dt>
          <dd><a href="tel:<?php echo e($phoneTel); ?>"><?php echo e($settings['phone']); ?></a></dd>
          <dd><a href="mailto:<?php echo e($settings['email']); ?>"><?php echo e($settings['email']); ?></a></dd>
        </div>
        <div>
          <dt>24/7 operations</dt>
          <dd><a href="tel:<?php echo e($phone247Tel); ?>"><?php echo e($settings['phone_247']); ?></a></dd>
        </div>
        <div>
          <dt>Certification</dt>
          <dd><?php echo e($settings['iso_statement']); ?></dd>
        </div>
      </dl>
    </div>
  </header>
</div>

<main id="hy-main">

  <!-- ==========================================================
       HERO — editorial manifesto. The headline is the design.
       ========================================================== -->
  <section class="hy-hero">
    <div class="hy-wrap">
      <div class="hy-hero__grid">
        <div class="hy-hero__copy">
          <h1 class="hy-hero__title">Beyond the<br>container.</h1>
        </div>

        <figure class="hy-hero__media">
          <img src="<?php echo url('assets/img/hyer/hero-lift.jpg'); ?>"
               alt="Heavy lift crane loading an oversized unit onto a low-bed trailer at a Singapore port"
               width="1600" height="900" fetchpriority="high">
        </figure>
      </div>

      <div style="max-width:640px; display:flex; flex-direction:column; gap:28px; padding-bottom:64px;">
        <p class="hy-hero__lead">
          Heavy lift, break bulk and project cargo, planned from your packing list
          and moved by sea, air and road.
        </p>
        <div class="hy-hero__actions">
          <a href="<?php echo url('contact.php'); ?>" class="hy-pill hy-pill--ink">
            Get a Quote <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
          <a href="#services" class="hy-pill hy-pill--ghost-ink">
            View Services
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================
       INTRO — right-weighted single column.
       ========================================================== -->
  <section class="hy-band">
    <div class="hy-wrap hy-intro">
      <div class="hy-intro__col" data-hy-reveal>
        <h2>Integrated customized logistics.</h2>
        <p>
          Carriage Global is a Singapore-based project logistics operator, ISO
          9001:2015 certified, with offices in Singapore and Johor Bahru. We plan
          every shipment around what you are actually moving before we plan
          around what it costs to move it, for the oil and gas, offshore, heavy
          lift, energy, construction and mining sectors.
        </p>
      </div>
    </div>
  </section>

  <!-- ==========================================================
       SERVICES — 2x2, hairline-divided, no photography.
       ========================================================== -->
  <section class="hy-band" id="services" style="padding-top:0;">
    <div class="hy-wrap">
      <?php if ($featureServices): ?>
      <div class="hy-features">
        <?php foreach ($featureServices as $i => $svc): ?>
        <article class="hy-features__cell" data-hy-reveal style="--hy-delay: <?php echo $i * 70; ?>ms">
          <span class="hy-features__icon">
<?php /* fa-solid, not fa-regular: only solid-900 and brands-400 are bundled
             in assets/css/fonts (see docs/ASSET-INVENTORY.md); fa-regular
             renders as missing-glyph boxes for anything past the handful of
             icons already used regular-weight elsewhere on the site. The
             thin circle around it keeps the mark reading as a stroke icon
             even though the glyph itself is a solid weight. */ ?>
            <i class="fa-solid <?php echo e($serviceIcons[$svc['slug']] ?? 'fa-anchor'); ?>" aria-hidden="true"></i>
          </span>
          <h3><?php echo e($svc['title']); ?></h3>
          <?php if (!empty($svc['short_desc'])): ?>
            <p><?php echo e($svc['short_desc']); ?></p>
          <?php endif; ?>
          <a href="<?php echo url($svc['link']); ?>">
            Read more <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </article>
        <?php endforeach; ?>
      </div>
      <div style="margin-top:28px;">
        <a href="<?php echo url('services.php'); ?>" class="hy-pill hy-pill--ghost-ink">
          All Services <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ==========================================================
       APPROACH — dark band, numbered hairline rows.
       ========================================================== -->
  <section class="hy-band hy-dark">
    <div class="hy-wrap">
      <div class="hy-approach__head" data-hy-reveal>
        <h2>We read the packing list before we quote.</h2>
        <p>
          Project freight forwarding is a choice between modes, made against
          cargo size, urgency, budget and site constraint.
        </p>
      </div>

      <ol class="hy-approach__list">
        <?php foreach ($approach as $n => $step): ?>
        <li data-hy-reveal style="--hy-delay: <?php echo $n * 70; ?>ms">
          <span class="hy-approach__num"><?php echo str_pad((string) ($n + 1), 2, '0', STR_PAD_LEFT); ?></span>
          <div>
            <h3><?php echo e($step['title']); ?></h3>
            <p><?php echo e($step['body']); ?></p>
          </div>
        </li>
        <?php endforeach; ?>
      </ol>

      <div class="hy-approach__cta" data-hy-reveal>
        <a href="<?php echo url('contact.php'); ?>" class="hy-pill hy-pill--ghost-white">
          Get a Quote <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- ==========================================================
       FEATURED CLAY CARD — the single warm accent on the page.
       ========================================================== -->
  <section class="hy-clay">
    <div class="hy-wrap">
      <div class="hy-clay__inner" data-hy-reveal>
        <h2>Send us your packing list.</h2>
        <p>Dimensions and a deadline are enough to start. We come back with the mode, the route and what it costs.</p>
        <a href="<?php echo url('contact.php'); ?>" class="hy-pill hy-pill--ink">
          Get a Quote <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- ==========================================================
       FLEET — isolated crops, grayscale.
       ========================================================== -->
  <section class="hy-band" id="fleet">
    <div class="hy-wrap">
      <div class="hy-crops">
        <figure data-hy-reveal>
          <div class="hy-crops__media">
            <img src="<?php echo url('assets/img/hyer/tug-crop.jpg'); ?>"
                 alt="Tug alongside a support vessel in Singapore waters" loading="lazy" width="900" height="675">
          </div>
          <figcaption>Tug and barge, alongside.</figcaption>
        </figure>
        <figure data-hy-reveal style="--hy-delay: 80ms;">
          <div class="hy-crops__media">
            <img src="<?php echo url('assets/img/hyer/reel-crop.jpg'); ?>"
                 alt="Large cable reel lifted for transport at the quayside" loading="lazy" width="900" height="675">
          </div>
          <figcaption>Reel and spool handling.</figcaption>
        </figure>
      </div>
    </div>
  </section>

  <!-- ==========================================================
       SECTORS — plain hairline-divided list.
       ========================================================== -->
  <section class="hy-sectors">
    <div class="hy-wrap hy-sectors__row" data-hy-reveal>
      <span class="hy-sectors__label">Sectors</span>
      <?php foreach ($sectors as $sector): ?>
        <span><?php echo e($sector); ?></span>
      <?php endforeach; ?>
    </div>
  </section>

</main>

<!-- ==========================================================
     FOOTER — full-bleed terminal, oversized wordmark.
     ========================================================== -->
<footer class="hy-footer">
  <div class="hy-footer__bg">
    <img src="<?php echo url('assets/img/hyer/footer-night.jpg'); ?>" alt="" aria-hidden="true" loading="lazy">
  </div>

  <div class="hy-wrap hy-footer__inner">
    <div class="hy-footer__brand">
      <img src="<?php echo url($settings['logo']); ?>" alt="<?php echo e($settings['company_name']); ?>" width="56" height="56">
      <p class="hy-footer__word">Carriage Global.</p>
    </div>

    <dl class="hy-footer__grid">
      <div>
        <dt>Singapore</dt>
        <dd><?php echo e($settings['address']); ?></dd>
        <dd><a href="tel:<?php echo e($phoneTel); ?>"><?php echo e($settings['phone']); ?></a></dd>
        <dd><a href="mailto:<?php echo e($settings['email']); ?>"><?php echo e($settings['email']); ?></a></dd>
      </div>
      <div>
        <dt>Johor Bahru</dt>
        <dd><?php echo e($settings['my_address']); ?></dd>
      </div>
      <div>
        <dt>Quick links</dt>
        <dd><a href="<?php echo url('index.php'); ?>">Home</a></dd>
        <dd><a href="#services">Services</a></dd>
        <dd><a href="<?php echo url('contact.php'); ?>">Contact</a></dd>
      </div>
      <div>
        <dt>Certification</dt>
        <dd><?php echo e($settings['iso_statement']); ?></dd>
        <dd>UEN <?php echo e($settings['uen']); ?></dd>
      </div>
    </dl>

    <div class="hy-footer__bottom">
      <span>&copy; <?php echo date('Y'); ?> <?php echo e($settings['company_name']); ?>. All rights reserved.</span>
      <span>Design concept, applying a style referenced from styles.refero.design.</span>
    </div>
  </div>
</footer>

<script src="<?php echo url('assets/js/landing-hyer.js'); ?>"></script>
</body>
</html>

<?php
/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
| Section order and the layout family each one uses. The families are all
| different on purpose: eight near-identical card rows is what makes a page
| read as templated.
|
|   1. Hero                full-bleed media, copy on the left
|   2. Video band          full-bleed video (the brief's post-hero section)
|   3. Services            asymmetric bento, 5 cells for 5 services
|   4. The CGS approach    numbered editorial rows
|   5. Fleet teaser        two-image split
|   6. Operations gallery  horizontal scroll-snap
|   7. Sectors             inline chip list
|   8. CTA                 centred band on navy
|
| Copy status: hero, approach, fleet and CTA text is PLACEHOLDER. The client
| has supplied homepage copy for none of it (docs/CONTENT.md). The service
| blurbs and the sector list are real, taken from the client's own emails and
| the old site. Nothing here asserts a fleet size, tonnage, headcount or
| project reference that was not supplied.
*/

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Project Logistics & Heavy Lift Freight Forwarding Singapore | '
           . $settings['company_name'];
$pageDesc  = 'Carriage Global (S) Pte Ltd moves heavy lift, break bulk and project '
           . 'cargo by sea, air and road from Singapore. ISO 9001:2015 certified.';
$bodyClass = 'page-home';

/* Hero slides. Falls back to the single `settings` hero when the table is
   empty, so the homepage still renders correctly during setup. */
$heroSlides = db_all(
    $pdo,
    "SELECT * FROM hero_slides WHERE status = 'active' ORDER BY sort_order ASC, id ASC"
);
if (!$heroSlides) {
    $heroSlides = [[
        'eyebrow'    => 'Project cargo, heavy lift, break bulk',
        'heading'    => $settings['hero_heading'],
        'subheading' => $settings['hero_subheading'],
        'image'      => $settings['hero_bg_image'],
        'alt_text'   => 'Carriage Global project cargo operation',
        'cta_label'  => 'Get a Quote',
        'cta_link'   => 'contact.php',
    ]];
}

/* Featured operations photography. Falls back to the files on disk until the
   client has uploaded and tagged their own through the admin gallery. */
$galleryRows = db_all(
    $pdo,
    "SELECT * FROM gallery WHERE status = 'active' AND featured = 1
     ORDER BY sort_order ASC, id ASC LIMIT 8"
);
if (!$galleryRows) {
    $galleryRows = [
        ['image_path' => 'assets/img/gallery/ops-01.jpg', 'alt_text' => 'Project cargo lifted aboard a geared vessel'],
        ['image_path' => 'assets/img/gallery/ops-02.jpg', 'alt_text' => 'Break bulk unit slung under a ship crane'],
        ['image_path' => 'assets/img/gallery/ops-03.jpg', 'alt_text' => 'Heavy lift module on the quayside'],
        ['image_path' => 'assets/img/gallery/ops-04.jpg', 'alt_text' => 'Cargo transferred to a barge alongside'],
        ['image_path' => 'assets/img/gallery/ops-05.jpg', 'alt_text' => 'Oversized cargo secured for sea transport'],
        ['image_path' => 'assets/img/gallery/ops-06.jpg', 'alt_text' => 'Barge operation in Singapore waters'],
    ];
}

/* The CGS approach. Verbatim from the client's Project Freight Forwarding
   email (docs/CONTENT.md). */
$approach = [
    [
        'title' => 'Packing list analysis',
        'body'  => 'We match cargo dimensions directly against equipment capability before quoting anything, so the mode fits the cargo rather than the other way round.',
    ],
    [
        'title' => 'Cost efficiency',
        'body'  => 'Urgency gets balanced against commercial constraint. Where a charter is not warranted, we will tell you, rather than sell you one.',
    ],
    [
        'title' => 'Feasibility studies',
        'body'  => 'Routes, handling gear and vessel types are checked against the real constraints of your site before anything is committed.',
    ],
];

/* Sectors served, from the old site. */
$sectors = ['Oil and gas', 'Offshore vessels', 'Heavy lift', 'Energy', 'Construction', 'Mining'];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <!-- 1 ── HERO CAROUSEL ────────────────────────────────────
       Slides come from the hero_slides table so the client can reorder
       or retire them from the admin.

       Only the copy and the photograph change between slides. The CTA
       label, the facts band and the layout stay put, so the carousel
       never moves a target the visitor is reaching for.

       Marked aria-roledescription="carousel" with each slide labelled
       "n of N"; aria-live is set to "off" while autoplay runs and
       flipped to "polite" once the visitor takes manual control, so a
       screen reader is not interrupted every few seconds. -->
  <section class="cgs-hero" aria-roledescription="carousel" aria-label="Carriage Global capabilities">
    <div class="swiper cgs-hero__swiper" data-hero-swiper>
      <div class="swiper-wrapper">
        <?php foreach ($heroSlides as $i => $slide): ?>
        <div class="swiper-slide cgs-hero__slide"
             role="group"
             aria-roledescription="slide"
             aria-label="<?php echo ($i + 1) . ' of ' . count($heroSlides); ?>">

          <div class="cgs-hero__media">
            <img src="<?php echo url($slide['image']); ?>"
                 alt="<?php echo e($slide['alt_text'] ?: $slide['heading']); ?>"
                 width="1600" height="1200"
                 <?php echo $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
          </div>

          <div class="container-fluid px-4">
            <div class="cgs-hero__inner">
              <?php if (!empty($slide['eyebrow'])): ?>
              <p class="cgs-hero__eyebrow" data-hero-anim>
                <i class="fa-solid fa-anchor" aria-hidden="true"></i>
                <?php echo e($slide['eyebrow']); ?>
              </p>
              <?php endif; ?>

              <?php /* One h1 per document: the first slide carries it, the rest are h2. */ ?>
              <?php if ($i === 0): ?>
                <h1 class="cgs-hero__title" data-hero-anim><?php echo e($slide['heading']); ?></h1>
              <?php else: ?>
                <h2 class="cgs-hero__title" data-hero-anim><?php echo e($slide['heading']); ?></h2>
              <?php endif; ?>

              <?php if (!empty($slide['subheading'])): ?>
                <p class="cgs-hero__lead" data-hero-anim><?php echo e($slide['subheading']); ?></p>
              <?php endif; ?>

              <div class="cgs-hero__actions" data-hero-anim>
                <a href="<?php echo url($slide['cta_link'] ?: 'contact.php'); ?>" class="cgs-btn cgs-btn--primary">
                  <?php echo e($slide['cta_label'] ?: 'Get a Quote'); ?>
                  <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
                </a>
                <a href="<?php echo url('services.php'); ?>" class="cgs-btn cgs-btn--on-dark">
                  Our Services
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <?php if (count($heroSlides) > 1): ?>
      <div class="container-fluid px-4 cgs-hero__controls">
        <div class="cgs-hero__pagination" data-hero-pagination></div>
        <div class="cgs-hero__buttons">
          <button class="cgs-hero__nav" data-hero-prev type="button" aria-label="Previous slide">
            <i class="fa-solid fa-angle-left" aria-hidden="true"></i>
          </button>
          <button class="cgs-hero__nav" data-hero-next type="button" aria-label="Next slide">
            <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
          </button>
          <button class="cgs-hero__toggle" data-hero-toggle type="button" aria-label="Pause slideshow">
            <i class="fa-solid fa-pause" aria-hidden="true"></i>
          </button>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Static across all slides: the facts do not belong to any one of them. -->
    <div class="cgs-hero__facts">
      <div class="container-fluid px-4">
        <ul>
          <li>
            <strong>ISO 9001:2015</strong>
            <span>Quality management certified</span>
          </li>
          <li>
            <strong>Singapore and Johor Bahru</strong>
            <span>Two offices, one operations team</span>
          </li>
          <li>
            <strong>24/7 operations line</strong>
            <span><a href="tel:<?php echo e($phone247Tel); ?>"><?php echo e($settings['phone_247']); ?></a></span>
          </li>
        </ul>
      </div>
    </div>
  </section>

  <!-- 2 ── VIDEO BAND ───────────────────────────────────────
       The brief places a video section directly after the hero.
       Muted + playsinline so mobile permits autoplay; cgs.js pauses
       it off-screen and honours prefers-reduced-motion. -->
  <section class="cgs-video-band" aria-label="Carriage Global operations">
    <video
      src="<?php echo url($settings['hero_video']); ?>"
      <?php if (!empty($settings['hero_video_poster'])): ?>
      poster="<?php echo url($settings['hero_video_poster']); ?>"
      <?php endif; ?>
      autoplay muted loop playsinline preload="metadata"></video>
    <div class="cgs-video-band__overlay">
      <div class="container-fluid px-4">
        <h2>From the packing list to the final site</h2>
      </div>
    </div>
  </section>

  <!-- 3 ── SERVICES ─────────────────────────────────────────
       Bento: 5 cells for 5 services. The first is the wide feature
       cell, the remaining four fill a 2x2. Not five equal cards. -->
  <section class="cgs-section" id="services">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>What we move, and how</h2>
        <a href="<?php echo url('services.php'); ?>" class="cgs-textlink">
          All services <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
        </a>
      </header>

      <?php if ($services): ?>
      <div class="cgs-bento">
        <?php foreach ($services as $i => $svc): ?>
          <a class="cgs-bento__cell <?php echo $i === 0 ? 'is-feature' : ''; ?>"
             href="<?php echo url($svc['link']); ?>"
             data-reveal style="--reveal-delay: <?php echo $i * 60; ?>ms">
            <?php if (!empty($svc['image'])): ?>
            <span class="cgs-bento__media">
              <img src="<?php echo url($svc['image']); ?>"
                   alt="<?php echo e($svc['alt_text'] ?: $svc['title']); ?>"
                   loading="lazy" width="800" height="600">
            </span>
            <?php endif; ?>
            <span class="cgs-bento__body">
              <span class="cgs-bento__title"><?php echo e($svc['title']); ?></span>
              <?php if (!empty($svc['short_desc'])): ?>
                <span class="cgs-bento__desc"><?php echo e($svc['short_desc']); ?></span>
              <?php endif; ?>
              <span class="cgs-bento__more">
                Read more <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
              </span>
            </span>
          </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- 4 ── THE CGS APPROACH ─────────────────────────────────
       Numbered editorial rows. Client copy, from the Project
       Freight Forwarding email. -->
  <section class="cgs-section cgs-section--tint">
    <div class="container-fluid px-4">
      <div class="cgs-approach">
        <div class="cgs-approach__intro">
          <p class="cgs-eyebrow">The CGS approach</p>
          <h2>We read the packing list before we quote</h2>
          <p>
            Project freight forwarding is a choice between modes, made against
            cargo size, urgency, budget and site constraint. Analysing what you
            are actually shipping produces a practical answer more often than
            reaching for the most expensive charter.
          </p>
          <!-- No CTA here on purpose. "Send us your packing list" and
               "Get a Quote" are the same intent pointing at the same page;
               two labels for one action is how a page ends up with four
               buttons that all mean "contact us". The hero, the nav and the
               closing band already carry it. -->
        </div>

        <ol class="cgs-approach__list">
          <?php foreach ($approach as $n => $step): ?>
          <li data-reveal style="--reveal-delay: <?php echo $n * 70; ?>ms">
            <span class="cgs-approach__num"><?php echo str_pad((string) ($n + 1), 2, '0', STR_PAD_LEFT); ?></span>
            <span class="cgs-approach__text">
              <strong><?php echo e($step['title']); ?></strong>
              <span><?php echo e($step['body']); ?></span>
            </span>
          </li>
          <?php endforeach; ?>
        </ol>
      </div>
    </div>
  </section>

  <!-- 5 ── FLEET TEASER ─────────────────────────────────────
       Two-image split. Copy is placeholder: the client has supplied
       nothing for the fleet, lashing or yard. -->
  <section class="cgs-section">
    <div class="container-fluid px-4">
      <div class="cgs-split">
        <div class="cgs-split__media">
          <img src="<?php echo url('assets/img/fleet/fleet-trailer.jpg'); ?>"
               alt="Carriage Global low-bed trailer loaded with an oversized vessel section"
               loading="lazy" width="900" height="700">
          <img src="<?php echo url('assets/img/fleet/lashing.jpg'); ?>"
               alt="Cargo chained and lashed to a Carriage Global trailer"
               loading="lazy" width="700" height="900">
        </div>

        <div class="cgs-split__body">
          <h2>Our own trailers, our own lashing crew</h2>
          <p>
            Owning the equipment and the people who secure the cargo removes the
            handover where most project shipments go wrong. Our fleet, our
            in-house lashing team and our open yard for storage and re-working
            all sit under one operation.
          </p>
          <ul class="cgs-split__points">
            <li>
              <strong>Fleet</strong>
              <span>Low-beds and flat-beds for oversized and heavy units.</span>
            </li>
            <li>
              <strong>In-house lashing</strong>
              <span>Lifting and lashing calculations, certified gear, sea-worthy securing.</span>
            </li>
            <li>
              <strong>Open yard</strong>
              <span>Cargo storage and re-working space between arrival and sailing.</span>
            </li>
          </ul>
          <a href="<?php echo url('our-fleet.php'); ?>" class="cgs-btn cgs-btn--ghost">
            See the fleet <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- 6 ── OPERATIONS GALLERY ───────────────────────────────
       Horizontal scroll-snap. Breadth without a wall of equal tiles. -->
  <section class="cgs-section cgs-section--dark cgs-gallery-section">
    <div class="container-fluid px-4">
      <header class="cgs-section-head cgs-section-head--dark">
        <h2>Recent operations</h2>
      </header>
    </div>

    <ul class="cgs-gallery" role="list">
      <?php foreach ($galleryRows as $shot): ?>
      <li>
        <img src="<?php echo url($shot['image_path']); ?>"
             alt="<?php echo e($shot['alt_text'] ?: 'Carriage Global project cargo operation'); ?>"
             loading="lazy" width="720" height="540">
      </li>
      <?php endforeach; ?>
    </ul>
  </section>

  <!-- 7 ── SECTORS ──────────────────────────────────────────
       Inline chip list. Real list, from the old site. -->
  <section class="cgs-section cgs-sectors">
    <div class="container-fluid px-4">
      <h2>Sectors we work in</h2>
      <ul>
        <?php foreach ($sectors as $sector): ?>
          <li><?php echo e($sector); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <!-- 8 ── CTA ──────────────────────────────────────────────
       One CTA intent on this page: "Get a Quote". Same label in the
       nav, the hero and here. -->
  <section class="cgs-cta">
    <div class="container-fluid px-4">
      <h2>Tell us what needs to move</h2>
      <p>
        Send the dimensions and the deadline. We will come back with the mode,
        the route and what it costs.
      </p>
      <div class="cgs-cta__actions">
        <a href="<?php echo url('contact.php'); ?>" class="cgs-btn cgs-btn--on-dark">
          Get a Quote <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
        </a>
        <a href="tel:<?php echo e($phoneTel); ?>" class="cgs-btn cgs-btn--outline-light">
          <i class="fa-solid fa-phone" aria-hidden="true"></i> <?php echo e($settings['phone']); ?>
        </a>
      </div>
    </div>
  </section>

</main>

<?php
require __DIR__ . '/includes/footer.php';
require __DIR__ . '/includes/scripts.php';

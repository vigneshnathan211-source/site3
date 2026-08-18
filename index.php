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
|   4. The CGS approach    dark band, numbered editorial rows
|   4b. Accent CTA card    single-accent band, "Send us your packing list"
|   5. Fleet teaser        two-image split
|   6. Operations gallery  horizontal scroll-snap
|   7. Sectors             inline chip list
|   8. CTA                 centred band on navy
|
| Copy status: the approach section is verbatim from the client's Project
| Freight Forwarding email (docs/CONTENT.md); the service blurbs and the
| sector list are real, taken from the client's own emails and the old
| site; the fleet/lashing/yard capability copy is confirmed accurate by the
| client. Hero and CTA copy is original marketing phrasing written for this
| build rather than a client quote, but it asserts no fleet size, tonnage,
| headcount or project reference that was not supplied — nothing on this
| page states an unverified fact.
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
      <div class="swiper-pagination cgs-hero__pagination"></div>
      <?php endif; ?>
    </div>

    <!-- Floating card, static across all slides: the facts do not belong to
         any one of them. Icons pop in once on load, not on every slide
         change — this band never re-animates while the carousel rotates. -->
    <div class="cgs-hero__facts">
      <ul>
        <li>
          <span class="cgs-hero__facts-icon" aria-hidden="true"><i class="fa-solid fa-certificate"></i></span>
          <span class="cgs-hero__facts-text">
            <strong>ISO 9001:2015</strong>
            <span>Quality management certified</span>
          </span>
        </li>
        <li>
          <span class="cgs-hero__facts-icon" aria-hidden="true"><i class="fa-solid fa-location-dot"></i></span>
          <span class="cgs-hero__facts-text">
            <strong>Singapore and Johor Bahru</strong>
            <span>Two offices, one operations team</span>
          </span>
        </li>
        <li>
          <span class="cgs-hero__facts-icon" aria-hidden="true"><i class="fa-solid fa-headset"></i></span>
          <span class="cgs-hero__facts-text">
            <strong>24/7 operations line</strong>
            <span><a href="tel:<?php echo e($phone247Tel); ?>"><?php echo e($settings['phone_247']); ?></a></span>
          </span>
        </li>
      </ul>
    </div>
  </section>

  <!-- 2 ── VIDEO BAND ───────────────────────────────────────
       The brief places a video section directly after the hero. The
       current clip (assets/video/cgs-home-intro.mp4) is AI-generated —
       see docs/ASSET-INVENTORY.md's own pre-go-live note asking the
       client to confirm this is acceptable given the rest of the site is
       real operations photography. Until that's confirmed, the visible
       disclosure below keeps this honest rather than presenting an
       AI clip as documentary footage; remove it once real footage lands
       or the client signs off on the AI clip.
       Muted + playsinline so mobile permits autoplay; cgs.js pauses
       it off-screen and honours prefers-reduced-motion. -->
  <section class="cgs-video-band" aria-label="Carriage Global operations">
    <video
      src="<?php echo url($settings['hero_video']); ?>"
      <?php if (!empty($settings['hero_video_poster'])): ?>
      poster="<?php echo url($settings['hero_video_poster']); ?>"
      <?php endif; ?>
      autoplay muted loop playsinline preload="metadata"></video>
    <p class="cgs-video-band__disclosure">Concept visualization — final operations footage pending</p>
    <div class="cgs-video-band__overlay">
      <div class="container-fluid px-4">
        <h2>From the packing list to the final site</h2>
      </div>
    </div>
  </section>

  <!-- 3 ── SERVICES ─────────────────────────────────────────
       Scroll-pin: on desktop, with motion allowed, the section holds one
       screen (cgs-services-pin__sticky) while its own extra height
       (services-count x 100vh, added by cgs.js as .is-pinned) is scrolled
       through — each service crossfades in in turn, then the page
       continues to the next section, rather than requiring a swipe
       gesture to see all five. Mobile and reduced-motion visitors get the
       same crossfading stage without the pinned scroll: the arrows step
       it directly. Without JS, every service's grid is simply stacked in
       normal flow — nothing is hidden.

       The secondary photo cell uses a general operations shot (real
       company photography, not stock), cycled by index so no two
       services show the same picture — the feature cell keeps the one
       photo actually tied to that specific service. -->
  <section class="cgs-section cgs-services-pin" id="services" data-services-pin
           style="--services-count: <?php echo max(1, count($services)); ?>;">
    <div class="cgs-services-pin__sticky">
      <div class="container-fluid px-4">
        <header class="cgs-section-head">
          <h2>What we move, and how</h2>
          <div class="cgs-section-head__right">
            <a href="<?php echo url('services.php'); ?>" class="cgs-textlink">
              All services <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
            </a>
            <?php if (count($services) > 1): ?>
            <div class="cgs-services__arrows">
              <button class="cgs-services__arrow" data-services-prev type="button" aria-label="Previous service">
                <i class="fa-solid fa-angle-left" aria-hidden="true"></i>
              </button>
              <button class="cgs-services__arrow" data-services-next type="button" aria-label="Next service">
                <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
              </button>
            </div>
            <?php endif; ?>
          </div>
        </header>

        <?php if ($services): ?>
        <div class="cgs-services-pin__stage" data-services-stage>
          <?php foreach ($services as $i => $svc):
            $num       = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
            $total     = str_pad((string) count($services), 2, '0', STR_PAD_LEFT);
            $secondary = $galleryRows ? $galleryRows[$i % count($galleryRows)] : null;
          ?>
          <div class="cgs-service-bento" data-service-slide>

            <a class="cgs-service-card cgs-service-bento__feature" href="<?php echo url($svc['link']); ?>">
              <?php if (!empty($svc['image'])): ?>
              <span class="cgs-service-card__media">
                <img src="<?php echo url($svc['image']); ?>"
                     alt="<?php echo e($svc['alt_text'] ?: $svc['title']); ?>"
                     loading="lazy" width="800" height="600">
              </span>
              <?php endif; ?>
              <span class="cgs-service-card__body">
                <span class="cgs-service-card__title"><?php echo e($svc['title']); ?></span>
              </span>
            </a>

            <?php if (!empty($svc['short_desc'])): ?>
            <div class="cgs-service-bento__cell cgs-service-bento__desc">
              <p><?php echo e($svc['short_desc']); ?></p>
            </div>
            <?php endif; ?>

            <?php if ($secondary): ?>
            <div class="cgs-service-bento__cell cgs-service-bento__icon">
              <img src="<?php echo url($secondary['image_path']); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="500">
            </div>
            <?php endif; ?>

            <a class="cgs-service-bento__cell cgs-service-bento__cta" href="<?php echo url($svc['link']); ?>">
              Read more <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
            </a>

            <div class="cgs-service-bento__cell cgs-service-bento__index" aria-hidden="true">
              <strong><?php echo $num; ?></strong>
              <span>/ <?php echo $total; ?></span>
            </div>

          </div>
          <?php endforeach; ?>
        </div>

        <div class="cgs-services-pin__track" aria-hidden="true">
          <span class="cgs-services-pin__fill" data-services-fill></span>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- 4 ── THE CGS APPROACH ─────────────────────────────────
       Dark band, numbered editorial rows, closing with a single-accent
       CTA card — ported from the "Hyer" landing concept's approach and
       featured-clay sections (landing.php). Client copy, from the
       Project Freight Forwarding email. -->
  <section class="cgs-section cgs-section--dark">
    <div class="container-fluid px-4">
      <div class="cgs-approach">
        <div class="cgs-approach__intro">
          <p class="cgs-eyebrow cgs-eyebrow--on-dark">The CGS approach</p>
          <h2>We read the packing list before we quote</h2>
          <p>
            Project freight forwarding is a choice between modes, made against
            cargo size, urgency, budget and site constraint. Analysing what you
            are actually shipping produces a practical answer more often than
            reaching for the most expensive charter.
          </p>
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

  <section class="cgs-accent-cta">
    <div class="container-fluid px-4">
      <div class="cgs-accent-cta__inner" data-reveal>
        <h2>Send us your packing list</h2>
        <p>
          Dimensions and a deadline are enough to start. We come back with the
          mode, the route and what it costs.
        </p>
        <a href="<?php echo url('contact.php'); ?>" class="cgs-btn cgs-btn--on-dark">
          Get a Quote <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- 5 ── FLEET TEASER ─────────────────────────────────────
       Two-image split. In-house trailers, lashing crew and open yard are
       confirmed accurate by the client — no longer placeholder. -->
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

        <div class="cgs-split__body" data-reveal style="--reveal-delay: 280ms">
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

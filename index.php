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

  <!-- 1 ── HERO ─────────────────────────────────────────────
       Four text elements max: eyebrow, headline, subtext, CTAs.
       The facts row sits below the fold-line divider, not stacked
       into the hero message itself. -->
  <section class="cgs-hero">
    <div class="cgs-hero__media">
      <img src="<?php echo url($settings['hero_bg_image']); ?>"
           alt="Project cargo lifted onto a barge alongside a geared vessel in Singapore"
           width="1600" height="1200" fetchpriority="high">
    </div>

    <div class="container-fluid px-4">
      <div class="cgs-hero__inner">
        <p class="cgs-hero__eyebrow">
          <i class="fa-solid fa-anchor" aria-hidden="true"></i>
          Project cargo, heavy lift, break bulk
        </p>

        <h1 class="cgs-hero__title"><?php echo e($settings['hero_heading']); ?></h1>

        <p class="cgs-hero__lead"><?php echo e($settings['hero_subheading']); ?></p>

        <div class="cgs-hero__actions">
          <a href="<?php echo url('contact.php'); ?>" class="cgs-btn cgs-btn--primary">
            Get a Quote <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
          </a>
          <a href="<?php echo url('services.php'); ?>" class="cgs-btn cgs-btn--on-dark">
            Our Services
          </a>
        </div>
      </div>
    </div>

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

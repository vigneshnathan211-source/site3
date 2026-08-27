<?php
/*
|--------------------------------------------------------------------------
| CHARTERING OF TUG AND BARGES — service detail
|--------------------------------------------------------------------------
| Content is the client's "webpage -3rd email on Tug and Barge charter"
| email, transcribed verbatim in docs/CONTENT.md ("Service page 3 —
| Chartering Tug & Barge"). Nothing below is invented: every heading and
| sentence is the client's own, only its layout is new. PROJECT-BRIEF.md's
| sitemap previously listed this page's copy as "not supplied" — that line
| is now stale and has been corrected there.
|
| No dedicated photo set exists for this service (ASSET-INVENTORY.md's
| "coverage gaps" note flags Tug & Barge as having no labelled client
| photography at all), so the gallery below is the `gallery` table's own
| 'Chartering of Tug and Barges' category — 2 real photos, already live on
| past-projects.php — plus the existing services-grid thumbnail as the
| hero/intro image. That is genuinely the entire photo budget for this
| page, which is also why there is no full-bleed statement band the way
| Heavy Lift has one: a 3rd, distinct photo for that band does not exist.
|
| Client note not yet actioned: a YouTube video can go on this page ("try
| to mute sound") — no link supplied yet (see docs/CONTENT.md and
| PROJECT-BRIEF.md's open questions).
|
| Section families:
|
|   Intro          -> text + one photo, .cgs-svc-intro
|   Four Core
|   Pillars        -> icon-card grid, .cgs-svc-grid — the four independent
|                     evaluation criteria (deck strength, dimensions,
|                     distance, risk)
|   Proven Track
|   Record         -> numbered rail, .cgs-svc-rail — same "How It Works"
|                     treatment as Project Freight Forwarding's CGS
|                     Approach and RoRo's Loading Process (2026-08-26:
|                     originally a two-column feature list of its own,
|                     changed to match the other two pages' rail design
|                     for visual consistency across all three service
|                     pages, per client feedback). The four named routes/
|                     capabilities aren't a literal sequence, but numbering
|                     them 01-04 reads fine either way.
*/

require_once __DIR__ . '/includes/bootstrap.php';

$service = null;
foreach ($services as $svcRow) {
    if ($svcRow['slug'] === 'tug-and-barge-chartering') {
        $service = $svcRow;
        break;
    }
}
$serviceTitle = $service['title'] ?? 'Chartering of Tug and Barges';

$pageTitle = $serviceTitle . ' | ' . $settings['company_name'];
$pageDesc  = 'Ballastable barge, tug-and-barge or self-propelled vessel, matched to deck strength, cargo dimensions, travel distance and operational risk across Southeast Asia.';
$pageCanon = 'tug-and-barge-chartering.php';
$bodyClass = 'page-tug-and-barge-chartering';

$blockRows = db_all(
    $pdo,
    "SELECT * FROM page_blocks WHERE page_key = 'tug-and-barge-chartering' AND status = 'active' ORDER BY sort_order ASC"
);
$blocks = [];
foreach ($blockRows as $row) {
    $blocks[$row['block_key']] = $row;
}

$sectionRows = db_all(
    $pdo,
    "SELECT * FROM service_sections WHERE service_id = :sid AND status = 'active' ORDER BY sort_order ASC",
    ['sid' => $service['id'] ?? 0]
);
$pillars     = array_values(array_filter($sectionRows, fn($r) => $r['layout'] === 'cards'));
$trackRecord = array_values(array_filter($sectionRows, fn($r) => $r['layout'] === 'list'));

$pillarIcons = [
    'Deck Strength'     => 'fa-weight-hanging',
    'Cargo Dimensions'  => 'fa-ruler-combined',
    'Travel Distance'   => 'fa-route',
    'Operational Risk'  => 'fa-triangle-exclamation',
];

$gallery = db_all(
    $pdo,
    "SELECT * FROM gallery WHERE category = 'Chartering of Tug and Barges' AND status = 'active' ORDER BY sort_order ASC"
);

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <!-- 1 ── BANNER ─────────────────────────────────────────── -->
  <section class="cgs-page-banner">
    <div class="cgs-page-banner__media">
      <img src="<?php echo url('assets/img/services/tug-and-barge-chartering.jpg'); ?>"
           width="640" height="480" alt=""
           fetchpriority="high">
    </div>
    <div class="container-fluid px-4">
      <nav class="cgs-page-banner__crumbs" aria-label="Breadcrumb">
        <ol>
          <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
          <li aria-current="page"><?php echo e($serviceTitle); ?></li>
        </ol>
      </nav>
      <h1><?php echo e($serviceTitle); ?></h1>
    </div>
  </section>

  <!-- 2 ── INTRO ──────────────────────────────────────────── -->
  <?php $introBlock = $blocks['intro'] ?? null; ?>
  <section class="cgs-section" aria-labelledby="tugbarge-intro-heading">
    <div class="container-fluid px-4">
      <div class="cgs-svc-intro__inner" data-reveal>
        <div class="cgs-svc-intro__text">
          <h2 id="tugbarge-intro-heading"><?php echo e($introBlock['heading'] ?? 'Ballastable Tug and Barge versus Self-Propelled Barge'); ?></h2>
          <div class="cgs-prose"><?php echo $introBlock['body'] ?? ''; ?></div>
        </div>
        <div class="cgs-svc-intro__media">
          <img src="<?php echo url('assets/img/services/tug-and-barge-chartering.jpg'); ?>"
               alt="Tug boat alongside a barge in Singapore waters"
               loading="lazy" width="640" height="480">
        </div>
      </div>
    </div>
  </section>

  <!-- 3 ── FOUR CORE PILLARS ───────────────────────────────── -->
  <?php if ($pillars): $pillarsBlock = $blocks['pillars'] ?? null; ?>
  <section class="cgs-section cgs-section--tint" aria-labelledby="tugbarge-pillars-heading">
    <div class="container-fluid px-4">
      <header class="cgs-svc-grid__head" data-reveal>
        <?php if (!empty($pillarsBlock['eyebrow'])): ?>
        <p class="cgs-eyebrow"><?php echo e($pillarsBlock['eyebrow']); ?></p>
        <?php endif; ?>
        <h2 id="tugbarge-pillars-heading"><?php echo e($pillarsBlock['heading'] ?? 'Four Core Pillars We Evaluate'); ?></h2>
      </header>

      <ul class="cgs-svc-grid" data-reveal style="--reveal-delay: 80ms">
        <?php foreach ($pillars as $pillar): ?>
        <li>
          <div class="cgs-svc-grid__cell">
            <span class="cgs-svc-grid__icon" aria-hidden="true"><i class="fa-solid <?php echo e($pillarIcons[$pillar['heading']] ?? 'fa-anchor'); ?>"></i></span>
            <h3><?php echo e($pillar['heading']); ?></h3>
            <?php echo $pillar['body']; ?>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>
  <?php endif; ?>

  <!-- 4 ── PROVEN TRACK RECORD ─────────────────────────────── -->
  <?php if ($trackRecord): $trackBlock = $blocks['track-record'] ?? null; ?>
  <section class="cgs-section cgs-section--dark" aria-labelledby="tugbarge-track-heading">
    <div class="container-fluid px-4">
      <header class="cgs-svc-process__head" data-reveal>
        <?php if (!empty($trackBlock['eyebrow'])): ?>
        <p class="cgs-eyebrow cgs-eyebrow--on-dark"><?php echo e($trackBlock['eyebrow']); ?></p>
        <?php endif; ?>
        <h2 id="tugbarge-track-heading"><?php echo e($trackBlock['heading'] ?? 'Proven Track Record: Regional Logistics Expertise'); ?></h2>
        <?php echo $trackBlock['body'] ?? ''; ?>
      </header>

      <div class="cgs-svc-rail" data-reveal style="--reveal-delay: 80ms">
        <?php foreach ($trackRecord as $i => $item): ?>
        <div class="cgs-svc-step">
          <span class="cgs-svc-step__num" aria-hidden="true"><?php echo str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT); ?></span>
          <div class="cgs-svc-step__body">
            <div class="cgs-svc-step__text">
              <h3><?php echo e($item['heading']); ?></h3>
              <?php echo $item['body']; ?>
            </div>
            <?php if (!empty($item['image'])): ?>
            <div class="cgs-svc-step__media">
              <img src="<?php echo url($item['image']); ?>" alt="" loading="lazy" width="240" height="180">
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 5 ── GALLERY ─────────────────────────────────────────── -->
  <?php if ($gallery): ?>
  <section class="cgs-section cgs-gallery" aria-label="Tug and barge charter photography">
    <div class="container-fluid px-4">
      <div class="cgs-svc-gallery__head" data-reveal>
        <h2>Recent Tug and Barge Charters</h2>
        <p>Real photography from our own charters in Singapore waters.</p>
      </div>
      <div class="cgs-svc-gallery__grid" data-reveal style="--reveal-delay: 80ms">
        <?php foreach ($gallery as $shot): ?>
        <a class="cgs-svc-gallery__link cgs-gallery-trigger" href="<?php echo url($shot['image_path']); ?>">
          <img src="<?php echo url($shot['image_path']); ?>" alt="<?php echo e($shot['alt_text']); ?>" loading="lazy">
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 6 ── CTA ─────────────────────────────────────────────── -->
  <section class="cgs-cta">
    <div class="container-fluid px-4">
      <div class="cgs-cta__card" data-reveal>
        <div class="cgs-cta__copy">
          <h2>Tell us what needs to move</h2>
          <p>
            Send the dimensions and the deadline. We will come back with the mode,
            the route and what it costs.
          </p>
        </div>
        <div class="cgs-cta__actions">
          <a href="<?php echo url('contact.php'); ?>" class="cgs-btn cgs-btn--on-dark">
            Get a Quote <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
          </a>
          <a href="tel:<?php echo e($phoneTel); ?>" class="cgs-btn cgs-btn--outline-light">
            <i class="fa-solid fa-phone" aria-hidden="true"></i> <?php echo e($settings['phone']); ?>
          </a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php
require __DIR__ . '/includes/footer.php';
require __DIR__ . '/includes/scripts.php';

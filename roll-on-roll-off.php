<?php
/*
|--------------------------------------------------------------------------
| ROLL ON AND OFF (RO-RO) — service detail
|--------------------------------------------------------------------------
| Content is the client's "webpage -4th email Roll on-Roll off" email,
| transcribed verbatim in docs/CONTENT.md ("Service page 4 — Roll On /
| Roll Off"). Nothing below is invented: every heading and sentence is the
| client's own, only its layout is new. PROJECT-BRIEF.md's sitemap
| previously listed this page's copy as "not supplied" — that line is now
| stale and has been corrected there.
|
| No photography exists for this service at all (ASSET-INVENTORY.md's
| "coverage gaps" note flags RoRo specifically), so unlike the other two
| remaining service pages this one has no gallery section — forcing in an
| unrelated stock photo would misrepresent what CGS has actually shot.
| Flagged as an open question in PROJECT-BRIEF.md rather than worked
| around. The one image this page does have — the existing services-grid
| thumbnail — covers the hero banner and intro split; see
| docs/ASSET-INVENTORY.md before assuming any other RoRo imagery exists.
|
| Section families:
|
|   Intro           -> text + one photo, .cgs-svc-intro
|   Cargo Types     -> icon-card grid, .cgs-svc-grid — the four cargo
|                      categories Mafi trailers carry
|   Step-by-Step
|   Loading Process -> numbered rail, .cgs-svc-rail — the client's own
|                      five-step sequence, already numbered 1-5 in the
|                      source copy. Each step has an optional photo slot
|                      (2026-08-26, client asked whether photos could be
|                      added here later): `service_sections.image` already
|                      exists as a column and this template already
|                      renders it the moment a row has one — no admin
|                      dashboard exists yet at all (PROJECT-BRIEF.md's
|                      admin module table lists "Services" as planned, not
|                      built), so for now a photo has to be added straight
|                      into that column's seed row in database/schema.sql;
|                      once the admin's services editor exists it just
|                      needs to expose this column, no template change.
*/

require_once __DIR__ . '/includes/bootstrap.php';

$service = null;
foreach ($services as $svcRow) {
    if ($svcRow['slug'] === 'roll-on-roll-off') {
        $service = $svcRow;
        break;
    }
}
$serviceTitle = $service['title'] ?? 'Roll On and Off (Ro-Ro)';

$pageTitle = $serviceTitle . ' | ' . $settings['company_name'];
$pageDesc  = 'Mafi trailer and ramp operations for rolling stock and awkward breakbulk units — from pre-staging and lashing to towing, rolling on and securing for the voyage.';
$pageCanon = 'roll-on-roll-off.php';
$bodyClass = 'page-roll-on-roll-off';

$blockRows = db_all(
    $pdo,
    "SELECT * FROM page_blocks WHERE page_key = 'roll-on-roll-off' AND status = 'active' ORDER BY sort_order ASC"
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
$cargoTypes = array_values(array_filter($sectionRows, fn($r) => $r['layout'] === 'cards'));
$loadSteps  = array_values(array_filter($sectionRows, fn($r) => $r['layout'] === 'list'));

$cargoTypeIcons = [
    'Heavy Industrial Machines'          => 'fa-industry',
    'Construction and Mining Equipment'  => 'fa-truck-monster',
    'Steel Structures'                   => 'fa-layer-group',
    'Large Crated Goods'                 => 'fa-boxes-stacked',
];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <!-- 1 ── BANNER ─────────────────────────────────────────── -->
  <section class="cgs-page-banner">
    <div class="cgs-page-banner__media">
      <img src="<?php echo url('assets/img/services/roll-on-roll-off.jpg'); ?>"
           width="1200" height="900" alt=""
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
  <section class="cgs-section" aria-labelledby="roro-intro-heading">
    <div class="container-fluid px-4">
      <div class="cgs-svc-intro__inner" data-reveal>
        <div class="cgs-svc-intro__text">
          <h2 id="roro-intro-heading"><?php echo e($introBlock['heading'] ?? 'Roll-On/Roll-Off (RoRo) Operations'); ?></h2>
          <div class="cgs-prose"><?php echo $introBlock['body'] ?? ''; ?></div>
        </div>
        <div class="cgs-svc-intro__media">
          <img src="<?php echo url('assets/img/services/roll-on-roll-off.jpg'); ?>"
               alt="Crane lowering heavy equipment onto a low-bed trailer alongside a Ro-Ro vessel"
               loading="lazy" width="1200" height="900">
        </div>
      </div>
    </div>
  </section>

  <!-- 3 ── CARGO TYPES ─────────────────────────────────────── -->
  <?php if ($cargoTypes): $cargoBlock = $blocks['cargo-types'] ?? null; ?>
  <section class="cgs-section cgs-section--tint" aria-labelledby="roro-cargo-heading">
    <div class="container-fluid px-4">
      <header class="cgs-svc-grid__head" data-reveal>
        <?php if (!empty($cargoBlock['eyebrow'])): ?>
        <p class="cgs-eyebrow"><?php echo e($cargoBlock['eyebrow']); ?></p>
        <?php endif; ?>
        <h2 id="roro-cargo-heading"><?php echo e($cargoBlock['heading'] ?? 'Cargo Types Loaded on Mafi Trailers'); ?></h2>
      </header>

      <ul class="cgs-svc-grid" data-reveal style="--reveal-delay: 80ms">
        <?php foreach ($cargoTypes as $cargo): ?>
        <li>
          <div class="cgs-svc-grid__cell">
            <span class="cgs-svc-grid__icon" aria-hidden="true"><i class="fa-solid <?php echo e($cargoTypeIcons[$cargo['heading']] ?? 'fa-box'); ?>"></i></span>
            <h3><?php echo e($cargo['heading']); ?></h3>
            <?php echo $cargo['body']; ?>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>
  <?php endif; ?>

  <!-- 4 ── STEP-BY-STEP LOADING PROCESS ────────────────────── -->
  <?php if ($loadSteps): $stepsBlock = $blocks['process'] ?? null; ?>
  <section class="cgs-section cgs-section--dark" aria-labelledby="roro-process-heading">
    <div class="container-fluid px-4">
      <header class="cgs-svc-process__head" data-reveal>
        <?php if (!empty($stepsBlock['eyebrow'])): ?>
        <p class="cgs-eyebrow cgs-eyebrow--on-dark"><?php echo e($stepsBlock['eyebrow']); ?></p>
        <?php endif; ?>
        <h2 id="roro-process-heading"><?php echo e($stepsBlock['heading'] ?? 'Step-by-Step Loading Process'); ?></h2>
      </header>

      <div class="cgs-svc-rail" data-reveal style="--reveal-delay: 80ms">
        <?php foreach ($loadSteps as $i => $step): ?>
        <div class="cgs-svc-step">
          <span class="cgs-svc-step__num" aria-hidden="true"><?php echo str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT); ?></span>
          <div class="cgs-svc-step__body">
            <div class="cgs-svc-step__text">
              <h3><?php echo e($step['heading']); ?></h3>
              <?php echo $step['body']; ?>
            </div>
            <?php if (!empty($step['image'])): ?>
            <div class="cgs-svc-step__media">
              <img src="<?php echo url($step['image']); ?>" alt="" loading="lazy" width="240" height="180">
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 5 ── CTA ─────────────────────────────────────────────── -->
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

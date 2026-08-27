<?php
/*
|--------------------------------------------------------------------------
| PROJECT FREIGHT FORWARDING — service detail
|--------------------------------------------------------------------------
| Third service-detail page built (after Heavy Lift Chartering); content is
| the client's "In Service tab Project freight forwarding page" email (sent
| twice, identical both times), transcribed verbatim in docs/CONTENT.md
| ("Service page 1 — Project Freight Forwarding"). Nothing below is
| invented: every heading and sentence is the client's own, only its layout
| is new.
|
| Unlike Heavy Lift, no dedicated photo set exists for this page — the
| gallery below reuses the `gallery` table's existing 'Project Freight
| Forwarding' category (5 real CGS-branded photos, already live on
| past-projects.php) rather than a page-scoped array, since that is
| genuinely the only photography this service has.
|
| Three section families:
|
|   Intro       -> text + one photo, .cgs-svc-intro (shared with the other
|                  two remaining service pages — see cgs.css's header
|                  comment on why these three share one component set)
|   Transport
|   Solutions   -> icon-card grid, .cgs-svc-grid — the five transport
|                  modes from the client's copy. Three of the five double
|                  as internal links per docs/CONTENT.md's own note ("Mafi
|                  Trailers -> Roll On/Roll Off, Tugs and Barges -> Tug &
|                  Barge Chartering, Geared/Semi-Geared -> Heavy Lift
|                  Chartering") — the only two with no sibling page
|                  (Container Vessels, Specialized Equipment) render as
|                  plain, unlinked cards.
|   The CGS
|   Approach    -> numbered rail, .cgs-svc-rail — three genuinely
|                  sequential steps (analyse the packing list, weigh cost,
|                  then confirm feasibility), unlike Transport Solutions'
|                  five independent options above it.
*/

require_once __DIR__ . '/includes/bootstrap.php';

$service = null;
foreach ($services as $svcRow) {
    if ($svcRow['slug'] === 'project-freight-forwarding') {
        $service = $svcRow;
        break;
    }
}
$serviceTitle = $service['title'] ?? 'Project Freight Forwarding';

$pageTitle = $serviceTitle . ' | ' . $settings['company_name'];
$pageDesc  = 'Mode selection driven by packing-list analysis — container, Mafi trailer, specialized equipment, tug and barge or geared vessel, matched to cargo size, urgency, budget and site constraints.';
$pageCanon = 'project-freight-forwarding.php';
$bodyClass = 'page-project-freight-forwarding';

$blockRows = db_all(
    $pdo,
    "SELECT * FROM page_blocks WHERE page_key = 'project-freight-forwarding' AND status = 'active' ORDER BY sort_order ASC"
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
$transportModes = array_values(array_filter($sectionRows, fn($r) => $r['layout'] === 'cards'));
$approachSteps  = array_values(array_filter($sectionRows, fn($r) => $r['layout'] === 'list'));

/* The three internal cross-links docs/CONTENT.md calls out, plus an icon
   per mode — matched by heading text since service_sections has no link
   column of its own (a one-off UX touch for this page, not new content). */
$transportModeMeta = [
    'Container Vessels'          => ['icon' => 'fa-cubes'],
    'Mafi Trailers'               => ['icon' => 'fa-truck-ramp-box', 'link' => 'roll-on-roll-off.php'],
    'Specialized Equipment'       => ['icon' => 'fa-gears'],
    'Tugs and Barges'             => ['icon' => 'fa-anchor', 'link' => 'tug-and-barge-chartering.php'],
    'Geared/Semi-Geared Vessels'  => ['icon' => 'fa-ship', 'link' => 'heavy-lift-chartering.php'],
];

$gallery = db_all(
    $pdo,
    "SELECT * FROM gallery WHERE category = 'Project Freight Forwarding' AND status = 'active' ORDER BY sort_order ASC"
);

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <!-- 1 ── BANNER ─────────────────────────────────────────── -->
  <section class="cgs-page-banner">
    <div class="cgs-page-banner__media">
      <img src="<?php echo url('assets/img/services/project-freight-forwarding.jpg'); ?>"
           width="1200" height="675" alt=""
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
  <section class="cgs-section" aria-labelledby="pff-intro-heading">
    <div class="container-fluid px-4">
      <div class="cgs-svc-intro__inner" data-reveal>
        <div class="cgs-svc-intro__text">
          <h2 id="pff-intro-heading"><?php echo e($introBlock['heading'] ?? 'Choosing the Right Mode of Transport'); ?></h2>
          <div class="cgs-prose"><?php echo $introBlock['body'] ?? ''; ?></div>
        </div>
        <div class="cgs-svc-intro__media">
          <img src="<?php echo url('assets/img/services/project-freight-forwarding.jpg'); ?>"
               alt="Oversized cargo strapped onto a Carriage Global low-bed trailer at a covered yard"
               loading="lazy" width="1200" height="675">
        </div>
      </div>
    </div>
  </section>

  <!-- 3 ── TRANSPORT SOLUTIONS ─────────────────────────────── -->
  <?php if ($transportModes): $modesBlock = $blocks['transport-solutions'] ?? null; ?>
  <section class="cgs-section cgs-section--tint" aria-labelledby="pff-modes-heading">
    <div class="container-fluid px-4">
      <header class="cgs-svc-grid__head" data-reveal>
        <?php if (!empty($modesBlock['eyebrow'])): ?>
        <p class="cgs-eyebrow"><?php echo e($modesBlock['eyebrow']); ?></p>
        <?php endif; ?>
        <h2 id="pff-modes-heading"><?php echo e($modesBlock['heading'] ?? 'Transport Solutions'); ?></h2>
      </header>

      <ul class="cgs-svc-grid" data-reveal style="--reveal-delay: 80ms">
        <?php foreach ($transportModes as $mode):
          $meta = $transportModeMeta[$mode['heading']] ?? ['icon' => 'fa-truck'];
        ?>
        <li>
          <?php if (!empty($meta['link'])): ?>
          <a class="cgs-svc-grid__cell" href="<?php echo url($meta['link']); ?>">
          <?php else: ?>
          <div class="cgs-svc-grid__cell">
          <?php endif; ?>
            <span class="cgs-svc-grid__icon" aria-hidden="true"><i class="fa-solid <?php echo e($meta['icon']); ?>"></i></span>
            <h3><?php echo e($mode['heading']); ?></h3>
            <?php echo $mode['body']; ?>
            <?php if (!empty($meta['link'])): ?>
            <span class="cgs-svc-grid__link-note">See the page <i class="fa-solid fa-angle-right" aria-hidden="true"></i></span>
            <?php endif; ?>
          <?php echo !empty($meta['link']) ? '</a>' : '</div>'; ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>
  <?php endif; ?>

  <!-- 4 ── THE CGS APPROACH ────────────────────────────────── -->
  <?php if ($approachSteps): $approachBlock = $blocks['approach'] ?? null; ?>
  <section class="cgs-section cgs-section--dark" aria-labelledby="pff-approach-heading">
    <div class="container-fluid px-4">
      <header class="cgs-svc-process__head" data-reveal>
        <?php if (!empty($approachBlock['eyebrow'])): ?>
        <p class="cgs-eyebrow cgs-eyebrow--on-dark"><?php echo e($approachBlock['eyebrow']); ?></p>
        <?php endif; ?>
        <h2 id="pff-approach-heading"><?php echo e($approachBlock['heading'] ?? 'The CGS Approach'); ?></h2>
      </header>

      <div class="cgs-svc-rail" data-reveal style="--reveal-delay: 80ms">
        <?php foreach ($approachSteps as $i => $step): ?>
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

  <!-- 5 ── GALLERY ─────────────────────────────────────────── -->
  <?php if ($gallery): ?>
  <section class="cgs-section cgs-gallery" aria-label="Project freight forwarding photography">
    <div class="container-fluid px-4">
      <div class="cgs-svc-gallery__head" data-reveal>
        <h2>Recent Project Freight Moves</h2>
        <p>Real photography from our own moves, matching cargo to the right vessel, trailer or route.</p>
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

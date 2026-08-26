<?php
/*
|--------------------------------------------------------------------------
| CHARTERING HEAVY LIFT AND SEMI-GEARED VESSELS — service detail
|--------------------------------------------------------------------------
| First of the five service-detail pages to be built (services.link already
| pointed here from index.php's bento grid; the file just did not exist
| yet). Content is the client's "Breakbulk chartering -1 pics" email
| (Angeline Tilokani -> contact@webowebsg.com, 14 Aug 2026, forwarded to the
| build team 19 Aug), transcribed verbatim in docs/CONTENT.md ("Service
| page 2 — Chartering Heavy Lift & Semi-Geared Vessels"). Nothing below is
| invented: every heading and sentence is the client's own, only its layout
| is new.
|
| Five section families, none repeated on this page:
|
|   Intro          -> text + one photo, .cgs-lift-intro
|   Statement      -> full-bleed photo band, one large pull-quote paragraph
|                     ("Our Vessel Chartering Services"), not another split
|   Process        -> a vertical connected rail for the seven-step "How
|                     Tailored Vessel Solutions Work" list — a long <ul>
|                     with a hairline under each row was the one thing to
|                     avoid here (see taste-skill Section 4.9)
|   Capabilities   -> an asymmetric 2+2 bento for the four discrete
|                     capabilities buried in the closing prose (fabrication/
|                     packing, lifting/lashing calculations, stowage plan/
|                     certified gear, contractual review — docs/CONTENT.md
|                     already flags these four as the intended layout)
|   Gallery        -> reuses .cgs-gallery wholesale (past-projects.php's
|                     lightbox), a dedicated set of photos for this page
|
| Photography: the client attached 18 photos across three separate
| Thunderbird emails to this same thread ("Breakbulk chartering -1 pics",
| "Breakbulk pics.", "Breakbulk pictures 2 email") — none of it published
| anywhere on the site yet. All 18 are used below, copied into
| assets/img/services/heavy-lift/ with descriptive names. None are a
| literal shot of "packing a box" or "reviewing a contract" (nobody
| photographs paperwork mid-charter), so the three photographed capability
| cells use the closest real operations photo as supporting proof
| photography for the surrounding text — the same "atmospheric stand-in"
| precedent already documented in database/schema.sql's fleet_items seed
| comment for Forklifts/Open Storage Yard on our-fleet.php. The two
| text-only process steps (Barge and LCT Operations, Port-to-Port Delivery,
| Compliance and Documentation) and one text-only capability (Contractual
| Review & Delivery) simply have no matching photo in the set and are not
| forced into one.
*/

require_once __DIR__ . '/includes/bootstrap.php';

$service = null;
foreach ($services as $svcRow) {
    if ($svcRow['slug'] === 'heavy-lift-chartering') {
        $service = $svcRow;
        break;
    }
}
$serviceTitle = $service['title'] ?? 'Chartering Heavy Lift and Semi-Geared Vessels';

$pageTitle = $serviceTitle . ' | ' . $settings['company_name'];
$pageDesc  = 'Vessel chartering for break bulk and project cargo on self-geared and semi-geared vessels, from cargo assessment to port-to-port delivery.';
$pageCanon = 'heavy-lift-chartering.php';
$bodyClass = 'page-heavy-lift-chartering';

$blockRows = db_all(
    $pdo,
    "SELECT * FROM page_blocks WHERE page_key = 'heavy-lift-chartering' AND status = 'active' ORDER BY sort_order ASC"
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
$processSteps    = array_values(array_filter($sectionRows, fn($r) => $r['layout'] === 'list'));
$capabilityCards = array_values(array_filter($sectionRows, fn($r) => $r['layout'] === 'cards'));

/* Gallery: a dedicated set for this page, not the shared `gallery` table
   (that table feeds past-projects.php's filterable grid — mixing the two
   would silently add these to that page's "Chartering Heavy Lift" filter
   too, which nobody asked for). Reuses the same .cgs-gallery-trigger
   lightbox class, wired once in includes/scripts.php for every page. */
$galleryShots = [
    ['file' => 'gallery-01.jpg', 'alt' => 'Cable-laying reel structure staged on a self-propelled trailer at a shipyard, a gantry crane marked "NO.8" behind'],
    ['file' => 'gallery-02.jpg', 'alt' => 'Deck crew looking up as a cable-reel structure is lowered toward the water during a marine charter'],
    ['file' => 'gallery-03.jpg', 'alt' => 'Crane lowering a cable-reel structure over open water alongside a tug and barge'],
    ['file' => 'gallery-04.jpg', 'alt' => 'Large cylindrical pressure vessel craned onto a flatbed trailer at a floodlit night berth'],
    ['file' => 'gallery-05.jpg', 'alt' => 'Mobile crane suspended on a spreader beam between two ship gantry cranes, blue sky behind'],
    ['file' => 'gallery-06.jpg', 'alt' => 'Underhook transfer of a mobile crane into an open cargo hold alongside open water'],
    ['file' => 'gallery-07.jpg', 'alt' => 'Spherical pressure vessel head wrapped for transit, crew standing by during positioning'],
    ['file' => 'gallery-08.jpg', 'alt' => 'Disassembled crawler crane components rigged for lift-out from a vessel\'s cargo hold'],
];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <!-- 1 ── BANNER ───────────────────────────────────────────
       Same .cgs-page-banner pattern as every other page — title matches
       the nav label exactly. -->
  <section class="cgs-page-banner">
    <div class="cgs-page-banner__media">
      <img src="<?php echo url('assets/img/services/heavy-lift/hero-lolo-transfer.jpg'); ?>"
           width="2016" height="1512" alt=""
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

  <!-- 2 ── INTRO ────────────────────────────────────────────
       Client's own opening paragraph, verbatim, beside a real underhook
       transfer photo. -->
  <?php $introBlock = $blocks['intro'] ?? null; ?>
  <section class="cgs-section" aria-labelledby="lift-intro-heading">
    <div class="container-fluid px-4">
      <div class="cgs-lift-intro__inner" data-reveal>
        <div class="cgs-lift-intro__text">
          <h2 id="lift-intro-heading"><?php echo e($introBlock['heading'] ?? 'Vessel Chartering for Break Bulk & Project Cargo'); ?></h2>
          <div class="cgs-prose"><?php echo $introBlock['body'] ?? ''; ?></div>
        </div>
        <div class="cgs-lift-intro__media">
          <img src="<?php echo url('assets/img/services/heavy-lift/intro-underhook-lift.jpg'); ?>"
               alt="Mobile crane suspended between two ship gantry cranes during an underhook cargo transfer"
               loading="lazy" width="899" height="1599">
        </div>
      </div>
    </div>
  </section>

  <!-- 3 ── STATEMENT ────────────────────────────────────────
       Full-bleed photo band, one pull-quote-style paragraph — a deliberate
       pause between the intro split above and the process rail below,
       rather than a second split or a card grid. -->
  <?php $statementBlock = $blocks['statement'] ?? null; ?>
  <section class="cgs-lift-statement" aria-labelledby="lift-statement-heading">
    <div class="cgs-lift-statement__media" aria-hidden="true">
      <img src="<?php echo url('assets/img/services/heavy-lift/statement-night-lift.jpg'); ?>" alt="" loading="lazy">
    </div>
    <div class="container-fluid px-4">
      <div class="cgs-lift-statement__inner" data-reveal>
        <h2 id="lift-statement-heading"><?php echo e($statementBlock['heading'] ?? 'Our Vessel Chartering Services'); ?></h2>
        <?php echo $statementBlock['body'] ?? ''; ?>
      </div>
    </div>
  </section>

  <!-- 4 ── PROCESS ──────────────────────────────────────────
       Vertical connected rail for the seven-step list, not a bare <ul> or
       a repeating card grid. All seven steps now carry a photo — the three
       that had no dedicated shot (Barge and LCT Operations, Port-to-Port
       Delivery, Compliance and Documentation) reuse three of the page's
       own gallery photos rather than an unrelated stock image, since no
       new client photography exists for these steps specifically. -->
  <?php if ($processSteps): $processBlock = $blocks['process'] ?? null; ?>
  <section class="cgs-section cgs-section--dark" id="process" aria-labelledby="lift-process-heading">
    <div class="container-fluid px-4">
      <header class="cgs-lift-process__head" data-reveal>
        <?php if (!empty($processBlock['eyebrow'])): ?>
        <p class="cgs-eyebrow cgs-eyebrow--on-dark"><?php echo e($processBlock['eyebrow']); ?></p>
        <?php endif; ?>
        <h2 id="lift-process-heading"><?php echo e($processBlock['heading'] ?? 'How Tailored Vessel Solutions Work'); ?></h2>
      </header>

      <div class="cgs-lift-rail" data-reveal style="--reveal-delay: 80ms">
        <?php foreach ($processSteps as $i => $step): ?>
        <div class="cgs-lift-step">
          <span class="cgs-lift-step__num" aria-hidden="true"><?php echo str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT); ?></span>
          <div class="cgs-lift-step__body">
            <div class="cgs-lift-step__text">
              <h3><?php echo e($step['heading']); ?></h3>
              <?php echo $step['body']; ?>
            </div>
            <?php if (!empty($step['image'])): ?>
            <div class="cgs-lift-step__media">
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

  <!-- 5 ── CAPABILITIES ─────────────────────────────────────
       Asymmetric 2+2 bento for the four discrete capabilities the client's
       closing prose names — three photographed, one text-only (see file
       header for why no photo is forced onto that fourth cell). -->
  <?php if ($capabilityCards): $capBlock = $blocks['capabilities'] ?? null; ?>
  <section class="cgs-section cgs-section--tint" id="capabilities" aria-labelledby="lift-capabilities-heading">
    <div class="container-fluid px-4">
      <header class="cgs-lift-capabilities__head" data-reveal>
        <?php if (!empty($capBlock['eyebrow'])): ?>
        <p class="cgs-eyebrow"><?php echo e($capBlock['eyebrow']); ?></p>
        <?php endif; ?>
        <h2 id="lift-capabilities-heading"><?php echo e($capBlock['heading'] ?? 'Comprehensive Cargo Handling and Marine Logistics Solutions'); ?></h2>
        <?php if (!empty($capBlock['body'])): ?>
        <?php echo $capBlock['body']; ?>
        <?php endif; ?>
      </header>

      <div class="cgs-lift-bento" data-reveal style="--reveal-delay: 80ms">
        <?php foreach ($capabilityCards as $c => $card): ?>
        <?php if (!empty($card['image'])): ?>
        <article class="cgs-lift-bento__cell<?php echo $c === 0 ? ' cgs-lift-bento__cell--wide' : ''; ?>">
          <img src="<?php echo url($card['image']); ?>" alt="" loading="lazy">
          <div class="cgs-lift-bento__body">
            <h3><?php echo e($card['heading']); ?></h3>
            <?php echo $card['body']; ?>
          </div>
        </article>
        <?php else: ?>
        <article class="cgs-lift-bento__cell cgs-lift-bento__cell--text cgs-lift-bento__cell--wide">
          <div class="cgs-lift-bento__body">
            <h3><?php echo e($card['heading']); ?></h3>
            <?php echo $card['body']; ?>
          </div>
        </article>
        <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 6 ── GALLERY ──────────────────────────────────────────
       Real photography from the same three "Breakbulk pics" emails, not
       yet published anywhere on the site — a dedicated set for this page
       (see file header for why it stays out of the shared gallery table).
       A single-row Swiper carousel, not the shared .cgs-gallery__grid
       masonry past-projects.php uses — clean uniform-aspect cards, no
       hover caption text, arrow/swipe browsing instead of a scroll grid.
       Reuses the existing data-gallery-swiper wiring already in cgs.js
       (built for this exact pattern, previously unused in any page's
       markup) and the shared .cgs-gallery-trigger lightbox binding from
       includes/scripts.php, so clicking a slide still opens the full-size
       lightbox with gallery navigation. -->
  <section class="cgs-section cgs-gallery" aria-label="Heavy lift and break bulk photography">
    <div class="container-fluid px-4">
      <div class="cgs-lift-gallery__head" data-reveal>
        <div class="cgs-lift-gallery__intro">
          <h2>Recent Heavy Lift & Break Bulk Moves</h2>
          <p>
            Real photography from our own charters, from crane lifts and underhook
            transfers to loading in the hold.
          </p>
        </div>
        <div class="cgs-lift-gallery__arrows">
          <button class="cgs-lift-gallery__arrow" data-gallery-prev type="button" aria-label="Previous photo">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
          </button>
          <button class="cgs-lift-gallery__arrow" data-gallery-next type="button" aria-label="Next photo">
            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </button>
        </div>
      </div>

      <div class="swiper cgs-lift-gallery__swiper" data-gallery-swiper data-reveal style="--reveal-delay: 80ms">
        <div class="swiper-wrapper">
          <?php foreach ($galleryShots as $shot):
            $shotPath = 'assets/img/services/heavy-lift/' . $shot['file'];
          ?>
          <div class="swiper-slide cgs-lift-gallery__slide">
            <a class="cgs-lift-gallery__link cgs-gallery-trigger" href="<?php echo url($shotPath); ?>">
              <img src="<?php echo url($shotPath); ?>" alt="<?php echo e($shot['alt']); ?>" loading="lazy">
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- 7 ── CTA ─────────────────────────────────────────────────
       Same content as every other page's closing CTA — one CTA intent
       site-wide, not a page-specific pitch. -->
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

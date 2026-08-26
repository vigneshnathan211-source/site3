<?php
/*
|--------------------------------------------------------------------------
| OUR FLEET — specialized fleet & infrastructure, port & terminal
| operations, regional transhipment, in-house lashing & lifting
|--------------------------------------------------------------------------
| Built from Angeline Tilokani's corrected "Our Fleet.docx" (angeline@
| carriageglobal.com -> contact@webowebsg.com, 25 Aug 2026, "Re: Our fleet
| page"), which explicitly supersedes an earlier plain-text draft sent the
| same morning ("Please disregard the earlier Fleet page; find the
| corrected version attached to avoid misunderstanding, re written with
| the help of in house fleet manager"). All copy below is that docx's
| text, unedited.
|
| database/schema.sql's fleet_items.category groups the page into the
| docx's own four blocks — Fleet, Port & Terminal, Transhipment, Lashing —
| with a matching page_blocks row per section for the heading/intro copy.
| The open storage yard (14 Penjuru Road) is one Fleet item in the docx,
| not its own category, which folds the docs/PROJECT-BRIEF.md sitemap's
| original three-block plan (fleet / lashing / open yard) into four —
| that plan predates this content.
|
|   Specialized Fleet & Infrastructure -> an asymmetric five-cell
|                             showcase. 2026-08-26: Angeline asked to
|                             replace the stand-in photos with the real
|                             WhatsApp fleet photos she sent; all five
|                             tiles now carry a real Carriage Global
|                             operations photo. Open Storage Yard has no
|                             photo of an actual yard in the asset set, so
|                             it reuses the Lashing section's photo below
|                             (client request) rather than staying
|                             icon-led — see the seed comment in
|                             schema.sql.
|   Port & Terminal Operations -> a dark, connected three-node process
|                             row, real container-terminal photography
|                             (assets/img/fleet/oocl-pipe-trailer.jpg,
|                             shot alongside OOCL boxes) dimmed in behind
|                             it rather than a stock icon watermark.
|   Regional Transhipment Services -> two small plotted-route diagrams
|                             (Singapore to Batam; Singapore through
|                             Malaysia to Thailand) whose lines draw
|                             themselves in on scroll, off the same
|                             generic [data-reveal] mechanism every other
|                             section already uses — no new JS.
|   In-House Lashing & Lifting -> the existing two-photo .cgs-split
|                             composition. lashing.jpg was removed from
|                             assets/img/fleet/ at some point and this
|                             slot broke silently; 2026-08-26 it was
|                             replaced with fleet-container-lowbed.jpg (one
|                             of the new WhatsApp photos — visible strap
|                             lashing on the cargo is a genuine content
|                             match), winch-transport.jpg is unchanged.
*/

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Our Fleet | ' . $settings['company_name'];
$pageDesc  = "Carriage Global's own specialized fleet, port and terminal operations, regional transhipment services, and in-house lashing and lifting team.";
$pageCanon = 'our-fleet.php';
$bodyClass = 'page-our-fleet';

$fleetBlockRows = db_all(
    $pdo,
    "SELECT * FROM page_blocks WHERE page_key = 'our-fleet' AND status = 'active' ORDER BY sort_order ASC"
);
$fleetBlocks = [];
foreach ($fleetBlockRows as $row) {
    $fleetBlocks[$row['block_key']] = $row;
}

$fleetItemRows = db_all(
    $pdo,
    "SELECT * FROM fleet_items WHERE status = 'active' ORDER BY category ASC, sort_order ASC, id ASC"
);
$fleetByCategory = [];
foreach ($fleetItemRows as $row) {
    $fleetByCategory[$row['category']][] = $row;
}

$specializedFleet = $fleetByCategory['Fleet']            ?? [];
$portTerminalOps  = $fleetByCategory['Port & Terminal']  ?? [];
$transhipmentOps  = $fleetByCategory['Transhipment']     ?? [];
$lashingPoints    = $fleetByCategory['Lashing']          ?? [];

/* Icon for every Specialized Fleet / Port & Terminal / Transhipment item
   that has no real photo to show instead (operations aren't equipment —
   Port & Terminal and Transhipment never had photos to begin with).
   Every glyph confirmed present in the self-hosted FontAwesome build. */
$fleetIcons = [
    /* fa-forklift is declared in fontawesome.css but missing from this
       self-hosted woff2's actual glyph subset (renders as a blank tofu
       box) — confirmed by rendering a candidate strip in the browser.
       fa-truck-loading is present and reads as the same forklift-style
       vehicle, so it stands in here. */
    'Forklifts'                          => 'fa-truck-loading',
    'Open Storage Yard'                  => 'fa-warehouse',
    'Pasir Panjang Auto Terminal'        => 'fa-truck-ramp-box',
    'PSA Container Terminal'             => 'fa-boxes-stacked',
    'Barge Operations'                   => 'fa-water',
];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <!-- 1 ── BANNER ───────────────────────────────────────────
       Same photo-banner pattern as resources.php / past-projects.php — no
       scrim, text-shadow carries legibility so the photo stays at full
       brightness. Swapped 2026-08-26 to fleet-iqip-tank-load.jpg (client
       request) — one of the WhatsApp photos sent for this page; not used
       elsewhere on our-fleet.php, only duplicated by index.php's Vision
       photo (a different page, so no on-page repeat). -->
  <section class="cgs-page-banner">
    <div class="cgs-page-banner__media">
      <img src="<?php echo url('assets/img/fleet/fleet-iqip-tank-load.jpg'); ?>"
           width="1600" height="1200" alt=""
           fetchpriority="high">
    </div>
    <div class="container-fluid px-4">
      <nav class="cgs-page-banner__crumbs" aria-label="Breadcrumb">
        <ol>
          <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
          <li aria-current="page">Our Fleet</li>
        </ol>
      </nav>
      <h1>Our Fleet</h1>
    </div>
  </section>

  <!-- 2 ── INTRO ────────────────────────────────────────────
       The docx's own opening two paragraphs, verbatim, paired with a real
       port/crane photo on the right so the client's own words open the
       page next to actual CGS capability rather than sitting alone. Text
       and image columns are locked to equal width/height via CSS grid. -->
  <?php $introBlock = $fleetBlocks['intro'] ?? null; ?>
  <section class="cgs-section cgs-fleet-intro" aria-labelledby="fleet-intro-heading">
    <div class="container-fluid px-4">
      <div class="cgs-fleet-intro__inner" data-reveal>
        <div class="cgs-fleet-intro__text">
          <h2 id="fleet-intro-heading"><?php echo e($introBlock['heading'] ?? 'Our Fleet & Capabilities'); ?></h2>
          <div class="cgs-prose cgs-fleet-intro__prose"><?php echo $introBlock['body'] ?? ''; ?></div>
        </div>
        <div class="cgs-fleet-intro__media">
          <img src="<?php echo url('assets/img/gallery/ops-17.jpg'); ?>"
               alt="Crane crew lowering oversized cargo onto a low-bed trailer at a Singapore port terminal"
               loading="lazy" width="1600" height="1152">
        </div>
      </div>
    </div>
  </section>

  <!-- 3 ── SPECIALIZED FLEET & INFRASTRUCTURE ──────────────────
       Asymmetric five-cell showcase, not a repeating card row: Modular
       Trailers leads wide (the SPMT photo is the single best equipment
       match in the asset set), Skeleton Chassis sits beside it, then Low
       Bed, Forklifts and Open Storage Yard share the row underneath. All
       five are now photo tiles — Forklifts and Open Storage Yard use the
       closest available real CGS photography as an atmospheric stand-in
       (see the fleet_items seed comment in schema.sql for why), not a
       literal equipment match, so both still get a real photo instead of
       falling back to the generic icon-tile treatment. -->
  <?php if ($specializedFleet): $fleetBlock = $fleetBlocks['fleet'] ?? null; ?>
  <section class="cgs-section cgs-section--tint" id="fleet" aria-labelledby="fleet-heading">
    <div class="container-fluid px-4">
      <header class="cgs-fleet-bento__head" data-reveal>
        <h2 id="fleet-heading"><?php echo e($fleetBlock['heading'] ?? 'Specialized Fleet & Infrastructure'); ?></h2>
        <?php if (!empty($fleetBlock['subheading'])): ?>
        <p><?php echo e($fleetBlock['subheading']); ?></p>
        <?php endif; ?>
      </header>

      <div class="cgs-fleet-bento" data-reveal style="--reveal-delay: 80ms">
        <?php foreach ($specializedFleet as $item):
          $icon = $fleetIcons[$item['title']] ?? 'fa-truck';
        ?>
          <?php if (!empty($item['image'])): ?>
          <article class="cgs-fleet-tile">
            <img src="<?php echo url($item['image']); ?>"
                 alt="<?php echo e($item['title']); ?>"
                 loading="lazy">
            <div class="cgs-fleet-tile__body">
              <h3><?php echo e($item['title']); ?></h3>
              <p><?php echo e($item['description']); ?></p>
              <?php if (!empty($item['specs'])): ?>
              <ul class="cgs-fleet-tile__specs">
                <?php foreach (preg_split('/\r\n|\r|\n/', trim($item['specs'])) as $spec): ?>
                <li><?php echo e($spec); ?></li>
                <?php endforeach; ?>
              </ul>
              <?php endif; ?>
            </div>
          </article>
          <?php else: ?>
          <article class="cgs-fleet-tile cgs-fleet-tile--icon">
            <span class="cgs-fleet-tile__icon" aria-hidden="true"><i class="fa-solid <?php echo e($icon); ?>"></i></span>
            <h3><?php echo e($item['title']); ?></h3>
            <p><?php echo e($item['description']); ?></p>
          </article>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 4 ── PORT & TERMINAL OPERATIONS ────────────────────────
       Dark section, three connected nodes on a hairline rail — a process,
       not a product, so no equipment photo would fit here honestly. The
       backdrop is real container-terminal photography (oocl-pipe-trailer,
       shot beside OOCL boxes) dimmed well back, not a stock icon
       watermark, since real photography exists for this one. -->
  <?php if ($portTerminalOps): $portBlock = $fleetBlocks['port-terminal'] ?? null; ?>
  <section class="cgs-section cgs-section--dark cgs-port-ops" id="port-terminal" aria-labelledby="port-terminal-heading">
    <div class="cgs-port-ops__media" aria-hidden="true">
      <img src="<?php echo url('assets/img/fleet/oocl-pipe-trailer.jpg'); ?>" alt="" loading="lazy">
    </div>
    <div class="container-fluid px-4">
      <header class="cgs-port-ops__head" data-reveal>
        <h2 id="port-terminal-heading"><?php echo e($portBlock['heading'] ?? 'Port & Terminal Operations'); ?></h2>
        <?php if (!empty($portBlock['subheading'])): ?>
        <p><?php echo e($portBlock['subheading']); ?></p>
        <?php endif; ?>
      </header>

      <div class="cgs-port-ops__row" data-reveal style="--reveal-delay: 100ms">
        <?php foreach ($portTerminalOps as $node): ?>
        <div class="cgs-port-node">
          <span class="cgs-port-node__icon" aria-hidden="true">
            <i class="fa-solid <?php echo e($fleetIcons[$node['title']] ?? 'fa-anchor'); ?>"></i>
          </span>
          <h3><?php echo e($node['title']); ?></h3>
          <p><?php echo e($node['description']); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 5 ── REGIONAL TRANSHIPMENT SERVICES ────────────────────
       Two small route diagrams instead of another card grid — the actual
       shape of the two lanes (a short hop to Batam, a longer overland run
       through Malaysia to Thailand) is the point, so it's drawn rather
       than described. Each line draws itself in off the same
       [data-reveal] mechanism every other section on the site already
       uses (see cgs.js) — no page-specific script. Each card now carries
       a real photo of that lane behind a navy scrim, the same
       media+overlay technique as .cgs-port-ops__media. Batam's photo was
       swapped 2026-08-26 (client request) to oocl-pipe-trailer.jpg — also
       used as the Port & Terminal section's backdrop above, reused here
       rather than duplicated as a second file — Malaysia/Thailand keeps
       its own overland-trailer photo (ops-07.jpg). -->
  <?php if ($transhipmentOps): $transBlock = $fleetBlocks['transhipment'] ?? null; ?>
  <section class="cgs-section cgs-transhipment" id="transhipment" aria-labelledby="transhipment-heading">
    <div class="container-fluid px-4">
      <header class="cgs-transhipment__head" data-reveal>
        <h2 id="transhipment-heading"><?php echo e($transBlock['heading'] ?? 'Regional Transhipment Services'); ?></h2>
        <?php if (!empty($transBlock['subheading'])): ?>
        <p><?php echo e($transBlock['subheading']); ?></p>
        <?php endif; ?>
      </header>

      <div class="cgs-route-cards" data-reveal style="--reveal-delay: 100ms">

        <article class="cgs-route-card">
          <div class="cgs-route-card__media" aria-hidden="true">
            <img src="<?php echo url('assets/img/fleet/oocl-pipe-trailer.jpg'); ?>" alt="" loading="lazy">
          </div>
          <svg class="cgs-route-card__svg" viewBox="0 0 220 20" aria-hidden="true">
            <line x1="10" y1="10" x2="210" y2="10" class="cgs-route-card__path"></line>
            <circle cx="10"  cy="10" r="4" class="cgs-route-card__dot"></circle>
            <circle cx="210" cy="10" r="4" class="cgs-route-card__dot"></circle>
          </svg>
          <div class="cgs-route-card__stops">
            <span>Singapore</span>
            <span>Batam</span>
          </div>
          <?php $batam = $transhipmentOps[0] ?? null; if ($batam): ?>
          <h3><?php echo e($batam['title']); ?></h3>
          <p><?php echo e($batam['description']); ?></p>
          <?php endif; ?>
        </article>

        <article class="cgs-route-card">
          <div class="cgs-route-card__media" aria-hidden="true">
            <img src="<?php echo url('assets/img/gallery/ops-07.jpg'); ?>" alt="" loading="lazy">
          </div>
          <svg class="cgs-route-card__svg" viewBox="0 0 220 20" aria-hidden="true">
            <line x1="10"  y1="10" x2="110" y2="10" class="cgs-route-card__path"></line>
            <line x1="110" y1="10" x2="210" y2="10" class="cgs-route-card__path" style="--path-delay: 260ms"></line>
            <circle cx="10"  cy="10" r="4" class="cgs-route-card__dot"></circle>
            <circle cx="110" cy="10" r="4" class="cgs-route-card__dot"></circle>
            <circle cx="210" cy="10" r="4" class="cgs-route-card__dot"></circle>
          </svg>
          <div class="cgs-route-card__stops">
            <span>Singapore</span>
            <span>Malaysia</span>
            <span>Thailand</span>
          </div>
          <?php $myTh = $transhipmentOps[1] ?? null; if ($myTh): ?>
          <h3><?php echo e($myTh['title']); ?></h3>
          <p><?php echo e($myTh['description']); ?></p>
          <?php endif; ?>
        </article>

      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 6 ── IN-HOUSE LASHING & LIFTING ────────────────────────
       Reuses the existing .cgs-split component (already built for the
       homepage, currently hidden there) rather than inventing a second
       image-pair pattern — the offset composition and its cgs.js
       entrance are already wired up sitewide. -->
  <?php if ($lashingPoints): $lashBlock = $fleetBlocks['lashing'] ?? null; ?>
  <section class="cgs-section" id="lashing" aria-labelledby="lashing-heading">
    <div class="container-fluid px-4">
      <div class="cgs-split">
        <div class="cgs-split__media">
          <img src="<?php echo url('assets/img/fleet/fleet-container-lowbed.jpg'); ?>"
               alt="Cargo strapped and lashed down on a Carriage Global low-bed trailer"
               loading="lazy" width="900" height="700">
          <img src="<?php echo url('assets/img/fleet/winch-transport.jpg'); ?>"
               alt="Winch and lifting equipment secured on a Carriage Global low-bed trailer"
               loading="lazy" width="700" height="900">
        </div>
        <div class="cgs-split__body" data-reveal style="--reveal-delay: 100ms">
          <?php if (!empty($lashBlock['subheading'])): ?>
          <p class="cgs-eyebrow"><?php echo e($lashBlock['subheading']); ?></p>
          <?php endif; ?>
          <h2 id="lashing-heading"><?php echo e($lashBlock['heading'] ?? 'In-House Lashing & Lifting'); ?></h2>
          <?php if (!empty($lashBlock['body'])): ?>
          <div class="cgs-prose"><?php echo $lashBlock['body']; ?></div>
          <?php endif; ?>
          <ul class="cgs-split__points">
            <?php foreach ($lashingPoints as $point): ?>
            <li>
              <strong><?php echo e($point['title']); ?></strong>
              <span><?php echo e($point['description']); ?></span>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 7 ── CTA ─────────────────────────────────────────────────
       Same content as index.php / resources.php's closing CTA — one CTA
       intent site-wide, not a page-specific pitch. -->
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

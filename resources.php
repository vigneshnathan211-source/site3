<?php
/*
|--------------------------------------------------------------------------
| RESOURCES — CBM/freight ton, Incoterms, insurance, certificates, T&Cs
|--------------------------------------------------------------------------
| Built from the client's "Fwd: CGS website 1" email (Angeline, forwarded
| 2026-08-14/19) and confirmed back into the sitemap 2026-08-24 ("HOME PAGE
| and VIDEO REVIESD 1" thread: "e) Resources (cgs certificates, terms and
| conditions, CBM and chargeable weight, incoterms) page is given").
|
| Read as ONE page with five DB-backed topics (database/schema.sql
| `resources`, grouped by `category`; the docs/PROJECT-BRIEF.md brief calls
| this "five anchored sections", not five separate pages) — but rendered as
| four visually distinct layout families rather than one repeated
| accordion, per the client's own instruction for the middle three.
| Certificates leads the page (client feedback, 2026-08-24: show the actual
| documents, not just a logo, and put it first) with a full document-card
| grid, not the homepage's small-badge strip; the other three sections each
| carry a real photograph or decorative accent so the page doesn't read as
| a single long block of reference copy:
|
|   Certificates           -> full cards (certificates table), each showing
|                             just the real scanned certificate image — opens
|                             the actual PDF in the existing Magnific Popup
|                             lightbox.
|   Cargo Measurement      -> rebuilt 2026-08-25 as an "instrument panel":
|                             an isometric crate diagram whose L/W/H edges
|                             draw themselves in on scroll and resolve into
|                             the CBM formula, beside the existing admin
|                             prose and a split-flap freight-ton converter
|                             (Gross Weight Ton <-> Metric Weight Ton).
|   Shipping Essentials    -> rebuilt 2026-08-25 as an expanding "manifest"
|                             of three leaves (client's original "big tabs"
|                             instruction, now literal cargo-tag leaves that
|                             fan open) — Incoterms as a flip-card grid,
|                             Liability vs Cargo Insurance as a VS split,
|                             Chargeable Weight as a live L/W/H calculator.
|                             $incotermsAnyMode / $incotermsSeaMode /
|                             the VS bullets below are the same
|                             resources.content facts already in the
|                             database (see database/schema.sql, ids 1/2/3/6),
|                             restructured for the new visual — not new
|                             claims. Any future topic added from the admin
|                             that isn't one of these three known slugs
|                             still renders, as a plain prose leaf, so the
|                             section never silently drops content.
|   Terms & Conditions     -> a document grid (General T&Cs, Code of
|                             Conduct, Alcohol & Drug Policy, Environmental
|                             Policy Statement) — all four PDFs arrived
|                             together as attachments on the same email —
|                             with a masked watermark accent, the same
|                             technique as the Contact page's desk section.
*/

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Resources | ' . $settings['company_name'];
$pageDesc  = 'CBM and freight ton, Incoterms, cargo insurance, chargeable weight, our certificates and general terms and conditions.';
$pageCanon = 'resources.php';
$bodyClass = 'page-resources';

$resourceRows = db_all(
    $pdo,
    "SELECT * FROM resources WHERE status = 'active' ORDER BY sort_order ASC, id ASC"
);
$resourcesByCategory = [];
foreach ($resourceRows as $row) {
    $resourcesByCategory[$row['category']][] = $row;
}

$cargoMeasurement  = $resourcesByCategory['Cargo Measurement'][0]  ?? null;
$shippingEssentials = $resourcesByCategory['Shipping Essentials']  ?? [];
$certificatesTopic  = $resourcesByCategory['Certificates'][0]      ?? null;
$termsDocs          = $resourcesByCategory['Terms & Conditions']   ?? [];

$certificateRows = db_all(
    $pdo,
    "SELECT * FROM certificates WHERE status = 'active' ORDER BY sort_order ASC, id ASC"
);

/* Icon + flip-grid data for the three known Shipping Essentials leaves —
   all three FontAwesome icons confirmed present in the self-hosted build
   (assets/css/plugins/fontawesome.css). The Incoterms rows are the same
   eleven terms as the resources.content table for id 2 (see
   database/schema.sql), split into the same "any mode" / "sea only"
   grouping the client's own copy describes, just rendered as flip cards
   instead of a plain <table>. */
$knownLeafIcons = [
    'incoterms'                          => 'fa-scale-balanced',
    'liability-versus-cargo-insurance'   => 'fa-shield-halved',
    'chargeable-weight-calculation'      => 'fa-weight-hanging',
];
/* Short label for the collapsed (vertical-text) tab state — the full
   title still shows once a leaf is open. "Freight Service Liability
   Insurance versus Cargo Insurance" set in vertical text needs real
   height to read without truncating, and since the row's height tracks
   whichever leaf content is tallest (see .cgs-manifest in cgs.css), a
   long collapsed title was quietly becoming that tallest thing instead
   of the actually-open panel's content. */
$knownLeafShortTitles = [
    'incoterms'                          => 'Incoterms',
    'liability-versus-cargo-insurance'   => 'Insurance',
    'chargeable-weight-calculation'      => 'Chargeable Weight',
];
$incotermsAnyMode = [
    ['EXW', 'Ex Works', "At the seller's premises, before loading"],
    ['FCA', 'Free Carrier', "Once goods are handed to the buyer's carrier"],
    ['CPT', 'Carriage Paid To', 'At the first carrier, though seller pays freight to destination'],
    ['CIP', 'Carriage and Insurance Paid To', 'At the first carrier; seller also insures to destination'],
    ['DAP', 'Delivered at Place', 'On arrival, ready for unloading'],
    ['DPU', 'Delivered at Place Unloaded', 'On arrival, after unloading'],
    ['DDP', 'Delivered Duty Paid', 'On arrival, duty and taxes cleared by seller'],
];
$incotermsSeaMode = [
    ['FAS', 'Free Alongside Ship', 'Once goods are placed alongside the vessel'],
    ['FOB', 'Free on Board', 'Once goods are loaded onto the vessel'],
    ['CFR', 'Cost and Freight', 'Once loaded, though seller pays freight to destination port'],
    ['CIF', 'Cost, Insurance and Freight', 'Once loaded; seller also insures to destination port'],
];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <!-- 1 ── BANNER ───────────────────────────────────────────
       A photo banner, not the plain dark-band intro past-projects.php
       still uses — title and breadcrumb only, no scrim behind the photo
       (DESIGN.md's No-Scrim Rule: text-shadow carries legibility instead,
       so the client's own photography stays at full brightness). -->
  <section class="cgs-page-banner">
    <div class="cgs-page-banner__media">
      <img src="<?php echo url('assets/img/gallery/ops-04.jpg'); ?>"
           width="1600" height="1200" alt=""
           fetchpriority="high">
    </div>
    <div class="container-fluid px-4">
      <nav class="cgs-page-banner__crumbs" aria-label="Breadcrumb">
        <ol>
          <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
          <li aria-current="page">Resources</li>
        </ol>
      </nav>
      <h1>Resources</h1>
    </div>
  </section>

  <!-- 2 ── CERTIFICATES ───────────────────────────────────────
       Full document cards, not the homepage's small-badge strip — the real
       scanned certificate image, no frame or caption text underneath (per
       2026-08-25 feedback). Leads the page, ahead of the reference
       material, per client feedback (2026-08-24: "not just logo"). -->
  <?php if ($certificateRows): ?>
  <section class="cgs-section cgs-credentials" id="certificates" aria-labelledby="certificates-heading">
    <div class="container-fluid px-4">
      <header class="cgs-credentials__head" data-reveal>
        <h2 id="certificates-heading"><?php echo e($certificatesTopic['title'] ?? 'Our Certificates'); ?></h2>
        <?php if (!empty($certificatesTopic['summary'])): ?>
        <p style="margin-top: 10px; color: var(--cgs-slate); max-width: 56ch;">
          <?php echo e($certificatesTopic['summary']); ?>
        </p>
        <?php endif; ?>
      </header>

      <div class="cgs-cert-grid" data-reveal style="--reveal-delay: 100ms">
        <?php foreach ($certificateRows as $cert): ?>
        <a class="cgs-cert-card cgs-pdf-trigger"
           href="<?php echo url($cert['file_path']); ?>"
           aria-label="<?php echo e($cert['title']); ?> — view full certificate PDF">
          <img src="<?php echo url($cert['preview_image'] ?? $cert['image']); ?>"
               alt="Scanned <?php echo e($cert['title']); ?> certificate document"
               loading="lazy">
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 3 ── CARGO MEASUREMENT: CBM & FREIGHT TON ─────────────
       Redesigned 2026-08-25 (instrument panel), then again on request to
       drop the crate diagram: a stacked reading layout instead — full-
       width intro, "What is CBM" and "Understanding Freight Ton" side by
       side, then a full-width "How CBM and Freight Ton Work Together"
       with the split-flap freight-ton converter under it. The admin copy
       is still one content blob (three <h3> sections after an intro
       paragraph); it's split on those <h3> boundaries below so each part
       can be placed, rather than turning it into three separate DB rows —
       this is bespoke placement of one fixed piece of copy, the same
       precedent as the Incoterms/Insurance/Chargeable Weight slugs below. -->
  <?php if ($cargoMeasurement): ?>
  <?php
    $cbmChunks = preg_split('/(?=<h3>)/', $cargoMeasurement['content']);
    $cbmIntro  = trim(array_shift($cbmChunks));
  ?>
  <section class="cgs-section cgs-section--dark" id="cargo-measurement" aria-labelledby="cargo-measurement-heading">
    <div class="container-fluid px-4">
      <div class="cgs-cbm">

        <div class="cgs-cbm__intro" data-reveal>
          <h2 id="cargo-measurement-heading">CBM and Freight Ton</h2>
          <?php if ($cbmIntro !== ''): ?>
          <div class="cgs-prose"><?php echo $cbmIntro; ?></div>
          <?php endif; ?>
        </div>

        <?php if ($cbmChunks): ?>
        <div class="cgs-cbm__split">
          <?php if (isset($cbmChunks[0])): ?>
          <div class="cgs-prose" data-reveal><?php echo $cbmChunks[0]; ?></div>
          <?php endif; ?>
          <?php if (isset($cbmChunks[1])): ?>
          <div class="cgs-prose" data-reveal style="--reveal-delay: 100ms"><?php echo $cbmChunks[1]; ?></div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="cgs-cbm__together" data-reveal>
          <?php if (isset($cbmChunks[2])): ?>
          <div class="cgs-prose"><?php echo $cbmChunks[2]; ?></div>
          <?php endif; ?>

          <div class="cgs-converter">
            <div class="cgs-converter__label">Freight ton converter</div>
            <div class="cgs-switch" role="group" aria-label="Choose ton convention">
              <button type="button" class="is-active" data-ton="short">Gross Weight Ton (US)</button>
              <button type="button" data-ton="metric">Metric Weight Ton</button>
            </div>
            <div class="cgs-converter__readout">
              <div class="cgs-flapboard" id="tonFlap" aria-live="polite"></div>
              <div class="cgs-converter__unit"><b id="tonUnitLabel">short tons</b><span id="tonSubLabel">2,000 lb per ton</span></div>
            </div>
            <div class="cgs-converter__formula" id="tonFormula"><b>Gross Weight Ton</b> = 5,000 lb &divide; 2,000</div>
          </div>
        </div>

      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 4 ── SHIPPING ESSENTIALS: MANIFEST ─────────────────────
       Redesigned 2026-08-25: the client's original "big tabs" instruction
       ("I want to show big tabs such as Incoterms... and third tab
       chargeable weight calculation") now literal cargo-tag leaves that
       fan open one at a time instead of a top tab row + panel underneath.
       Section background switched to plain white (was cgs-section--tint
       + the dot-grid cgs-shipping-decor backdrop) — the manifest's own
       navy/white leaves already carry enough contrast on their own; the
       dot-grid under that reads busy stacked on top of it. -->
  <?php if ($shippingEssentials): ?>
  <section class="cgs-section" id="shipping-essentials" aria-labelledby="shipping-essentials-heading">
    <div class="container-fluid px-4">
      <h2 id="shipping-essentials-heading" data-reveal>Incoterms, Insurance and Chargeable Weight</h2>

      <div class="cgs-manifest" data-reveal style="--reveal-delay: 80ms" id="manifest">
        <?php foreach ($shippingEssentials as $t => $topic): ?>
        <div class="cgs-leaf<?php echo $t === 0 ? ' is-active' : ''; ?>" data-leaf="<?php echo e($topic['slug']); ?>">
          <button class="cgs-leaf__tab" aria-expanded="<?php echo $t === 0 ? 'true' : 'false'; ?>" aria-controls="leaf-<?php echo e($topic['slug']); ?>">
            <span class="cgs-leaf__icon" aria-hidden="true"><i class="fa-solid <?php echo e($knownLeafIcons[$topic['slug']] ?? 'fa-file-lines'); ?>"></i></span>
            <span class="cgs-leaf__title"><?php echo e($topic['title']); ?></span>
            <span class="cgs-leaf__title-short"><?php echo e($knownLeafShortTitles[$topic['slug']] ?? $topic['title']); ?></span>
          </button>
          <div class="cgs-leaf__body" id="leaf-<?php echo e($topic['slug']); ?>">
            <div class="cgs-leaf__body-inner">

              <?php if ($topic['slug'] === 'incoterms'): ?>
              <p class="cgs-leaf__intro">Eleven standardized trade terms defining exactly where the seller&rsquo;s responsibility for cost, risk and delivery ends and the buyer&rsquo;s begins.</p>

              <div class="cgs-iterm-group">
                <p class="cgs-iterm-group__label"><i class="fa-solid fa-truck" aria-hidden="true"></i> Any mode of transport</p>
                <div class="cgs-iterm-grid">
                  <?php foreach ($incotermsAnyMode as $term): ?>
                  <div class="cgs-iterm-card">
                    <span class="cgs-iterm-code"><?php echo e($term[0]); ?></span>
                    <span class="cgs-iterm-name"><?php echo e($term[1]); ?></span>
                    <span class="cgs-iterm-risk"><?php echo e($term[2]); ?></span>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="cgs-iterm-group">
                <p class="cgs-iterm-group__label"><i class="fa-solid fa-ship" aria-hidden="true"></i> Sea &amp; inland waterway only</p>
                <div class="cgs-iterm-grid">
                  <?php foreach ($incotermsSeaMode as $term): ?>
                  <div class="cgs-iterm-card">
                    <span class="cgs-iterm-code"><?php echo e($term[0]); ?></span>
                    <span class="cgs-iterm-name"><?php echo e($term[1]); ?></span>
                    <span class="cgs-iterm-risk"><?php echo e($term[2]); ?></span>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <?php elseif ($topic['slug'] === 'liability-versus-cargo-insurance'): ?>
              <p class="cgs-leaf__intro">These two covers are often confused, but they protect different things and different parties — one is the carrier&rsquo;s own capped liability, the other is a policy you arrange yourself for the goods.</p>
              <div class="cgs-vs-split">
                <div class="cgs-vs-badge">VS</div>
                <div class="cgs-vs-side cgs-vs-side--a">
                  <div class="cgs-vs-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></div>
                  <h4>Freight Service Liability Insurance</h4>
                  <ul>
                    <li><i class="fa-solid fa-check" aria-hidden="true"></i> Covers the carrier&rsquo;s own, legally limited liability</li>
                    <li><i class="fa-solid fa-check" aria-hidden="true"></i> Applies only to loss or damage while cargo is in the carrier&rsquo;s care</li>
                    <li><i class="fa-solid fa-check" aria-hidden="true"></i> Capped by weight or a fixed sum per package</li>
                    <li><i class="fa-solid fa-check" aria-hidden="true"></i> Set by convention — Hague-Visby (sea), CMR (road)</li>
                    <li><i class="fa-solid fa-check" aria-hidden="true"></i> The cap is fixed by that convention, not by what the cargo is actually worth</li>
                  </ul>
                </div>
                <div class="cgs-vs-side cgs-vs-side--b">
                  <div class="cgs-vs-icon"><i class="fa-solid fa-box-archive" aria-hidden="true"></i></div>
                  <h4>Cargo Insurance</h4>
                  <ul>
                    <li><i class="fa-solid fa-check" aria-hidden="true"></i> Also called marine cargo insurance</li>
                    <li><i class="fa-solid fa-check" aria-hidden="true"></i> A separate policy the cargo owner takes out, not the carrier</li>
                    <li><i class="fa-solid fa-check" aria-hidden="true"></i> Covers the full declared value, not a capped amount</li>
                    <li><i class="fa-solid fa-check" aria-hidden="true"></i> Includes risks the carrier isn&rsquo;t liable for at all — piracy, general average</li>
                    <li><i class="fa-solid fa-check" aria-hidden="true"></i> Also covers damage from circumstances outside the carrier&rsquo;s control</li>
                  </ul>
                </div>
                <div class="cgs-vs-note">For high-value or project cargo, a carrier&rsquo;s liability cover alone almost always leaves a gap between what the cargo is worth and what they&rsquo;re obligated to pay out. Arranging your own cargo insurance closes that gap.</div>
              </div>

              <?php elseif ($topic['slug'] === 'chargeable-weight-calculation'): ?>
              <p class="cgs-leaf__intro">Air freight is charged on whichever is greater: actual weight, or volumetric weight. Drag the crate&rsquo;s dimensions and see which one wins.</p>

              <div class="cgs-calc-grid">
                <div>
                  <div class="cgs-slider-row">
                    <div class="cgs-slider-row__label"><span>Length</span><b id="valL">120</b><span class="cgs-slider-row__u"> cm</span></div>
                    <input type="range" id="sliderL" min="60" max="200" value="120">
                  </div>
                  <div class="cgs-slider-row">
                    <div class="cgs-slider-row__label"><span>Width</span><b id="valW">80</b><span class="cgs-slider-row__u"> cm</span></div>
                    <input type="range" id="sliderW" min="40" max="150" value="80">
                  </div>
                  <div class="cgs-slider-row">
                    <div class="cgs-slider-row__label"><span>Height</span><b id="valH">100</b><span class="cgs-slider-row__u"> cm</span></div>
                    <input type="range" id="sliderH" min="50" max="160" value="100">
                  </div>
                  <div class="cgs-slider-row">
                    <div class="cgs-slider-row__label"><span>Actual weight</span><b id="valWt">150</b><span class="cgs-slider-row__u"> kg</span></div>
                    <input type="range" id="sliderWt" min="50" max="300" value="150">
                  </div>
                  <p class="cgs-calc-note">Volumetric weight = (L &times; W &times; H) &divide; 6,000</p>
                </div>

                <div class="cgs-race">
                  <div>
                    <div class="cgs-race-row__head">
                      <span class="cgs-race-row__name">Actual weight</span>
                      <span class="cgs-race-row__val"><span class="cgs-chargeable-pill" id="pillActual">Chargeable</span> <span class="cgs-race-num" id="numActual">150.0 kg</span></span>
                    </div>
                    <div class="cgs-race-track"><div class="cgs-race-fill" id="barActual"></div></div>
                  </div>
                  <div>
                    <div class="cgs-race-row__head">
                      <span class="cgs-race-row__name">Volumetric weight</span>
                      <span class="cgs-race-row__val"><span class="cgs-chargeable-pill" id="pillVol">Chargeable</span> <span class="cgs-race-num" id="numVol">160.0 kg</span></span>
                    </div>
                    <div class="cgs-race-track"><div class="cgs-race-fill" id="barVol"></div></div>
                  </div>
                </div>
              </div>

              <?php else: ?>
              <h3><?php echo e($topic['title']); ?></h3>
              <div class="cgs-prose"><?php echo $topic['content']; ?></div>
              <?php endif; ?>

            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 5 ── TERMS, CONDUCT AND POLICIES ────────────────────────
       General T&Cs plus the three policy PDFs that arrived on the same
       email attachment set (Code of Conduct, Alcohol & Drug Policy,
       Environmental Policy Statement) — one document grid, not four
       separate sections. A masked watermark accent (same technique as the
       Contact page's desk section) keeps the section from reading as bare
       text against white. -->
  <?php if ($termsDocs): ?>
  <section class="cgs-section cgs-terms" id="terms-and-conditions" aria-labelledby="terms-heading">
    <div class="container-fluid px-4">
      <h2 id="terms-heading" data-reveal>Terms, Conduct and Policies</h2>
      <p data-reveal style="max-width: 56ch; color: var(--cgs-slate); margin: 10px 0 32px;">
        Our governing terms and company policies. Each opens as a PDF you
        can read in full or download.
      </p>

      <ul class="cgs-docs__grid" data-reveal style="--reveal-delay: 80ms">
        <?php foreach ($termsDocs as $doc): ?>
        <li>
          <a class="cgs-docs__link cgs-pdf-trigger" href="<?php echo url($doc['file_path']); ?>">
            <span class="cgs-docs__icon"><i class="fa-solid fa-file-pdf" aria-hidden="true"></i></span>
            <span class="cgs-docs__title"><?php echo e($doc['title']); ?></span>
            <?php if (!empty($doc['summary'])): ?>
            <span class="cgs-docs__desc"><?php echo e($doc['summary']); ?></span>
            <?php endif; ?>
            <span class="cgs-docs__meta">View PDF <i class="fa-solid fa-angle-right" aria-hidden="true"></i></span>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>
  <?php endif; ?>

  <!-- 6 ── CTA ─────────────────────────────────────────────────
       Same content as index.php's closing CTA — one CTA intent site-wide
       ("Get a Quote"), not a page-specific pitch, per client direction to
       keep this consistent across pages. -->
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

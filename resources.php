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
|                             the real scanned certificate, issuer, cert
|                             number, validity and scope — opens the actual
|                             PDF in the existing Magnific Popup lightbox.
|   Cargo Measurement      -> asymmetric split, prose left (led by a real
|                             yard photo) / formula reference card right
|                             (client supplied this copy verbatim, in-line
|                             in the email body).
|   Shipping Essentials    -> a "big tabs" module — Incoterms, Freight
|                             Service Liability Insurance vs Cargo
|                             Insurance, Chargeable Weight Calculation —
|                             beside a real cargo photo, on a dot-grid
|                             decorated section. The client asked for the
|                             tabs explicitly: "I want to show big tabs such
|                             as Incoterms... and third tab chargeable
|                             weight calculation." Copy is original (client:
|                             "we do not want to copy but i need you to
|                             re write"), grounded in the three reference
|                             links she sent, not reproduced from them.
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

/* Icon per Shipping Essentials slug — all three confirmed present in the
   self-hosted FontAwesome build (assets/css/plugins/fontawesome.css). */
$tabIcons = [
    'incoterms'                          => 'fa-scale-balanced',
    'liability-versus-cargo-insurance'   => 'fa-shield-halved',
    'chargeable-weight-calculation'      => 'fa-weight-hanging',
];

/* One real photo per tab (already vetted, captioned real client photography
   reused from index.php's gallery arrays — none repeated from the
   Certificates or Cargo Measurement sections above). */
$tabMedia = [
    'incoterms' => [
        'src' => 'assets/img/gallery/ops-08.jpg', 'w' => 1600, 'h' => 900,
        'alt' => 'MacGregor ship crane hoisting cargo over the water at a Singapore port',
    ],
    'liability-versus-cargo-insurance' => [
        'src' => 'assets/img/gallery/ops-13.jpg', 'w' => 1080, 'h' => 809,
        'alt' => "Wrapped process vessel lowered into a ship's cargo hold",
    ],
    'chargeable-weight-calculation' => [
        'src' => 'assets/img/gallery/ops-14.jpg', 'w' => 2048, 'h' => 1152,
        'alt' => 'Winch and crane equipment secured on a vessel deck alongside shipping containers',
    ],
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
       scanned certificate, issuer, cert number, validity and scope, per
       client feedback (2026-08-24: "not just logo"). Leads the page, ahead
       of the reference material, per the same feedback. -->
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
          <span class="cgs-cert-card__frame">
            <img src="<?php echo url($cert['preview_image'] ?? $cert['image']); ?>"
                 alt="Scanned <?php echo e($cert['title']); ?> certificate document"
                 loading="lazy">
          </span>
          <span class="cgs-cert-card__body">
            <h3><?php echo e($cert['title']); ?></h3>
            <?php if (!empty($cert['issuer'])): ?>
            <span class="cgs-cert-card__issuer"><?php echo e($cert['issuer']); ?></span>
            <?php endif; ?>
            <span class="cgs-cert-card__link">View Certificate PDF <i class="fa-solid fa-angle-right" aria-hidden="true"></i></span>
          </span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 3 ── CARGO MEASUREMENT: CBM & FREIGHT TON ─────────────
       Asymmetric split (prose / reference card), led by a real yard photo,
       not a third instance of the tab or grid pattern used below it. -->
  <?php if ($cargoMeasurement): ?>
  <section class="cgs-section" id="cargo-measurement" aria-labelledby="cargo-measurement-heading">
    <div class="container-fluid px-4">
      <div class="cgs-measure">

        <div data-reveal>
          <h2 id="cargo-measurement-heading">CBM and Freight Ton</h2>
          <div class="cgs-prose">
            <?php echo $cargoMeasurement['content']; ?>
          </div>
        </div>

        <div class="cgs-measure__col">
          <figure class="cgs-measure__media" data-reveal style="--reveal-delay: 80ms">
            <img src="<?php echo url('assets/img/gallery/ops-07.jpg'); ?>"
                 width="2048" height="1152" loading="lazy"
                 alt="Carriage Global trailer loaded with a large cable reel and crated cargo at a yard">
          </figure>

          <aside class="cgs-measure__ref" data-reveal style="--reveal-delay: 120ms">
            <p class="cgs-measure__ref-label">Quick Reference</p>
            <dl class="cgs-measure__ref-list">
              <div>
                <dt>CBM (Cubic Meter)</dt>
                <dd>Length &times; Width &times; Height (m)</dd>
              </div>
              <div>
                <dt>Gross Weight Ton (Short Ton)</dt>
                <dd>Weight (lbs) &divide; 2,000</dd>
              </div>
              <div>
                <dt>Metric Weight Ton</dt>
                <dd>Weight (kg) &divide; 1,000</dd>
              </div>
            </dl>
          </aside>
        </div>

      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 4 ── SHIPPING ESSENTIALS: BIG TABS ─────────────────────
       Client's own instruction, verbatim: "I want to show big tabs such as
       Incoterms... and third tab chargeable weight calculation." Tabs are
       centered, and each panel leads with its own real photo, so the
       module doesn't read as a bare text block. -->
  <?php if ($shippingEssentials): ?>
  <section class="cgs-section cgs-section--tint cgs-shipping-decor" id="shipping-essentials" aria-labelledby="shipping-essentials-heading">
    <div class="container-fluid px-4">
      <h2 id="shipping-essentials-heading" data-reveal>Incoterms, Insurance and Chargeable Weight</h2>

      <div data-cgs-tabs data-reveal style="--reveal-delay: 80ms">
        <div class="cgs-tabs__list" role="tablist" aria-label="Shipping essentials">
          <?php foreach ($shippingEssentials as $t => $topic): ?>
          <button type="button"
                  class="cgs-tabs__btn <?php echo $t === 0 ? 'is-active' : ''; ?>"
                  role="tab"
                  id="tab-<?php echo e($topic['slug']); ?>"
                  aria-controls="panel-<?php echo e($topic['slug']); ?>"
                  aria-selected="<?php echo $t === 0 ? 'true' : 'false'; ?>"
                  tabindex="<?php echo $t === 0 ? '0' : '-1'; ?>"
                  data-cgs-tab>
            <i class="fa-solid <?php echo e($tabIcons[$topic['slug']] ?? 'fa-file-lines'); ?>" aria-hidden="true"></i>
            <?php echo e($topic['title']); ?>
          </button>
          <?php endforeach; ?>
        </div>

        <div class="cgs-tabs__panels">
          <?php foreach ($shippingEssentials as $t => $topic): ?>
          <div class="cgs-tabs__panel <?php echo $t === 0 ? 'is-active' : ''; ?>"
               role="tabpanel"
               id="panel-<?php echo e($topic['slug']); ?>"
               aria-labelledby="tab-<?php echo e($topic['slug']); ?>"
               data-cgs-panel>
            <?php $media = $tabMedia[$topic['slug']] ?? null; ?>
            <?php if ($media): ?>
            <figure class="cgs-tabs__media">
              <img src="<?php echo url($media['src']); ?>"
                   width="<?php echo (int) $media['w']; ?>" height="<?php echo (int) $media['h']; ?>"
                   loading="lazy" alt="<?php echo e($media['alt']); ?>">
            </figure>
            <?php endif; ?>
            <div class="cgs-prose">
              <?php echo $topic['content']; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
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

  <!-- 6 ── CTA ─────────────────────────────────────────────── -->
  <section class="cgs-cta">
    <div class="container-fluid px-4">
      <div class="cgs-cta__card" data-reveal>
        <div class="cgs-cta__copy">
          <h2>Need cover beyond our carrier liability?</h2>
          <p>
            Multimodal transport insurance and public liability insurance
            are arranged case by case. Send us the shipment details and we
            will come back with the right cover for the cargo.
          </p>
        </div>
        <div class="cgs-cta__actions">
          <a href="<?php echo url('contact.php'); ?>" class="cgs-btn cgs-btn--on-dark">
            Contact Our Team <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php
require __DIR__ . '/includes/footer.php';
require __DIR__ . '/includes/scripts.php';

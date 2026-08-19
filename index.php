<?php
/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
| Section order and the layout family each one uses. The families are all
| different on purpose: eight near-identical card rows is what makes a page
| read as templated.
|
|   1. Hero                full-bleed media, copy on the left, black scrim
|   2. Video band          inset video card (the brief's post-hero section)
|   2b. Certifications     dark band, the 3 cert PDFs embedded live, large
|                          (moved here, after the video band, on client
|                          request; entity/registration details moved to the
|                          navbar logo lockup, client: "remove company
|                          address and only showcase certificates")
|   3. Services            asymmetric bento, 5 cells for 5 services
|   4. Divisions           white band, two-column capability lists + a
|                          4-item "why choose CGS" strip underneath
|   4b. Partners           logo marquee, continuous auto-scroll (moved
|                          here from just after Certifications, client:
|                          "I want partners at the above cta")
|   4c. Accent CTA card    single-accent band, "Send us your packing list"
|   5. Fleet teaser        two-image split, real equipment specifics
|   5b. Project desk       role-based contact grid
|   5c. Gallery            masonry of real operations photos, Magnific
|                          Popup lightbox with gallery nav — hidden via
|                          if (false) 2026-08-19, client: "hide the
|                          gallery section" (markup/CSS/JS left in place)
|   6. CTA                 rounded sea-blue card, inset on white
|
| 2026-08-19: pruned to hero, services, CTA (kept unconditionally) plus
| only the sections the client's own emails actually supply content for.
| Removed: core values, special services, the operations-gallery carousel
| and the FAQ accordion — all old-site scrapes, not emails.txt content
| (still real client copy, just not sourced from this document; they can
| return once there's a page/placement the client has actually briefed for
| them). The video band stays even though no email describes it, because
| CLAUDE.md/the project brief fixes it as a required structural section
| directly after the hero, independent of emails.txt.
|
| Copy status: the credentials strip, divisions, CGS-advantage strip and
| project-desk contacts are verbatim from the client's own "1st email on
| HOME Page" (docs/source/emails.txt), the email the client wrote
| specifically to brief this page. The fleet teaser's equipment specifics
| are verbatim from the client's separate "Email Our Fleet". The partners
| marquee list and the service blurbs are also real, client-supplied
| content. Hero and CTA copy is original marketing phrasing written for
| this build rather than a client quote, but it asserts no fleet size,
| tonnage, headcount or project reference that was not supplied — nothing
| on this page states an unverified fact.
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
   client has uploaded and tagged their own through the admin gallery.
   ops-05.jpg was dropped 2026-08-19: byte-for-byte identical to
   assets/img/fleet/lashing.jpg (confirmed via md5sum) — the same photo
   under two filenames, which meant it silently duplicated Division 01's
   gallery every time this array's 5th slot rendered in the Services bento
   below. Replaced with ops-07.jpg, a real, previously-unused client photo
   from client_assets/pic/ (client, 2026-08-19: pointed at that folder for
   more real photography after "dully check don't repeat images"). */
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
        ['image_path' => 'assets/img/gallery/ops-07.jpg', 'alt_text' => 'Carriage Global trailer loaded with a large cable reel and crated cargo at a yard'],
        ['image_path' => 'assets/img/gallery/ops-06.jpg', 'alt_text' => 'Barge operation in Singapore waters'],
    ];
}

/* Certifications. Verbatim instruction from the client's "1st email on
   HOME Page": "At the left hand corner write both company names and co
   registration numbers, ISO number, Bizsafe". Rebuilt several times on
   client follow-ups — full PDF page as a card, then the compact registrar
   badge with a click-to-view popup, then a two-column entities+certs split,
   and now (2026-08-19: "remove company address and only shocase
   certificates in large size") certs only, full width — the two entity
   names and the UEN moved up to the navbar logo lockup (includes/header.php)
   instead of repeating them here.

   All three cards render a static image preview rather than a live
   <iframe> of the real PDF. That started as a fix for just the WCA card —
   Chrome's built-in PDF viewer draws a thin dark page-border around every
   embedded PDF, barely visible on the ISO/bizSAFE white portrait pages but
   a heavy black frame on WCA's landscape gold-toned page (client: "wca
   certificate not look good make it correctly") — but the live iframe
   turned out to have a second, worse problem on mobile: Android Chrome
   doesn't reliably render an inline PDF preview inside an iframe at all,
   showing its generic black "filename.pdf / Open" file card instead
   (client screenshot, 2026-08-19: "in my mobile pdf are not showing").
   Switching ISO and bizSAFE to the same static-render pattern WCA already
   used fixes both problems at once. Each preview is a pixel-faithful
   render of the real PDF's first page (assets/img/certificates/*.jpg,
   rendered at 2.2x via PyMuPDF). Clicking the card still opens the real
   PDF (not the preview image) in the Magnific Popup lightbox — 'file'
   stays the actual PDF path, only the card face changed. 'ratio' is each
   PDF's own page width/height in points, used as the frame's CSS
   aspect-ratio so a landscape certificate isn't squeezed into a portrait
   box. */
$certificates = [
    [
        'label'   => 'ISO 9001:2015',
        'file'    => 'assets/certificates/iso-9001-2015.pdf',
        'ratio'   => '594 / 838',
        'preview' => 'assets/img/certificates/iso9001.jpg',
    ],
    [
        'label'   => 'bizSAFE Level 4',
        'file'    => 'assets/certificates/bizsafe-4.pdf',
        'ratio'   => '595 / 842',
        'preview' => 'assets/img/certificates/bizsafe.jpg',
    ],
    [
        'label'   => 'WCA Project, 15 yrs',
        'file'    => 'assets/certificates/wca-project-membership.pdf',
        'ratio'   => '765 / 567',
        'preview' => 'assets/img/certificates/wca-project-membership.jpg',
    ],
];

/* SECTION 1 of the client's HOME Page email: "Unrivaled Control: Two
   Specialized In-House Departments". Verbatim, split into its two named
   divisions. */
$divisions = [
    [
        'title'   => 'In-House Asset Transport & Technical Site Services',
        'tagline' => 'Eliminating transit risks through wholly-owned equipment, certified field crews, and regional overland lanes.',
        'intro'   => "Our land transport division is built on physical assets and boots-on-the-ground technical expertise. We don't rely on sub-contractors to secure your cargo; we deploy our own personnel and machinery to ensure total quality control.",
        'items'   => [
            ['title' => 'Wholly-owned specialized fleet', 'body' => 'Immediate access to an extensive, company-owned fleet of heavy-duty low-bed trailers, multi-axle configurations, and skeleton chassis designed for heavy-haul and out-of-gauge (OOG) transport.'],
            ['title' => 'Pan-Asian cross-border trucking', 'body' => 'High-frequency, secure overland corridors connecting Singapore, transiting West Malaysia, and reaching all the way up to Thailand. We handle all customs clearances, border permits, and transit documentation seamlessly.'],
            ['title' => 'In-house lashing, lifting & rigging teams', 'body' => 'Certified rigger-packers and lifting supervisors who calculate center-of-gravity dynamics, design customized lifting plans, and execute precise tie-downs using premium-grade materials.'],
            ['title' => 'Industrial packing & box fabrication', 'body' => 'On-site construction of heavy-duty, custom-engineered wooden boxes, skids, and crates tailored to the exact dimensional and weight requirements of sensitive or high-value machinery.'],
            ['title' => 'Cargo surveying & risk mitigation', 'body' => 'Rigorous pre-ops and post-ops cargo inspections, route surveys, pinch-point analysis, and continuous monitoring to guarantee the physical integrity of your assets.'],
            ['title' => 'Asset storage & environmental protection', 'body' => 'Access to secure, high-capacity open-yard storage facilities equipped for heavy grounding, with comprehensive industrial fumigation services meeting stringent international biosecurity standards.'],
        ],
    ],
    [
        'title'   => 'Project Freight Forwarding & Marine Engineering Desk',
        'tagline' => 'Navigating complex maritime lanes, vessel charters, and port geometry constraints.',
        'intro'   => 'When industrial cargo exceeds the limits of standard roads, our maritime division steps in. We analyze everything from coastal hydrology to port infrastructure to select, secure, and engineer the ideal ocean transit method for your project.',
        'items'   => [
            ['title' => 'Specialized container operations (OOG)', 'body' => 'Expert out-of-gauge stowage planning: precise loading, blocking, and lashing of oversized cargo onto flat racks and open-top containers, plus complex uncontainerised cargo (UC) safely positioned on container vessels.'],
            ['title' => 'Roll-on / roll-off (RoRo) & MAFI solutions', 'body' => 'Efficient handling of heavy rolling stock and stationary oversized industrial components using heavy-duty MAFI trailers for seamless RoRo vessel loading and discharge.'],
            ['title' => 'Tug & barge chartering', 'body' => 'Specialized coastal and inland waterway transport. We source and charter dedicated tug and barge configurations designed to navigate shallow-draft inland waterways and remote shorelines lacking mature port infrastructure.'],
            ['title' => 'Full & part vessel chartering', 'body' => "Direct access to global shipowners. We charter heavy-lift, geared, semi-geared, and gearless vessels tailored entirely to your project's unique cargo profile and budget."],
            ['title' => 'Port infrastructure assessment', 'body' => 'Comprehensive engineering analysis of the destination and receiving sites: length overall (LOA) limits, draft restrictions, berth capacities, and tidal variations, to determine exactly which class of vessel can safely dock and discharge your cargo.'],
        ],
    ],
];

/* SECTION 2 of the same email: "Why Global Industrial Leaders Choose CGS". */
$advantage = [
    ['icon' => 'fa-truck-ramp-box', 'title' => 'Asset-backed reliability', 'body' => 'We own the trailers, including super low-bed trailers (0.8m above the ground), skeleton chassis, forklifts from 3t to 16t, stuffing equipment, and the teams behind them, giving total control over scheduling, safety protocols, and pricing.'],
    ['icon' => 'fa-route',          'title' => 'True door-to-door execution', 'body' => 'From the moment we fabricate the protective crating to the final discharge at a remote deep-sea or river port, your cargo never leaves our care.'],
    ['icon' => 'fa-calculator',     'title' => 'Engineering-first approach', 'body' => 'We don\'t guess. We calculate. Every lift, lash and vessel charter is backed by precise calculations, draft assessments, and route surveys, with lifting equipment availability confirmed ahead of time to avoid last-minute disappointments.'],
    ['icon' => 'fa-earth-asia',     'title' => 'Global network, local power', 'body' => 'A global logistics network combined with localized, asset-heavy execution. We have been a WCA Project member for fifteen years, working only with trusted, asset-based partners built up over that time.'],
];

/* "Contact Our Project Desk" from the same email — real named roles and
   department addresses, not generic placeholders. The last row has no
   'name': the source email names a person for every other row
   ("PROJECT MANAGER- ANGELINE TILOKANI", "FLEET MANAGER... ALAN SOH", etc.)
   but for Shipping Documents gives only "SHIPPING RELATED DOCUMENTS-
   ADMIN@CARRIAGEGLOBAL.COM" — no name. An earlier pass filled that gap
   with an invented "Admin Team" label; caught on a 2026-08-19 audit
   ("no ai content, only content provided from emails.txt") and removed —
   the markup below renders just the role + email when 'name' is absent. */
$projectDesk = [
    ['role' => 'Project Manager',            'name' => 'Angeline Tilokani', 'email' => 'angeline@carriageglobal.com'],
    ['role' => 'Fleet Manager & Operations', 'name' => 'Alan Soh',          'email' => 'alan@carriageglobal.com'],
    ['role' => 'Yard Manager',               'name' => 'Mr Teo & Mr Khoo',  'email' => 'ops@carriageglobal.com'],
    ['role' => 'Accounts',                   'name' => 'Ashwini & Mr Ryan', 'email' => 'accounts@carriageglobal.com'],
    ['role' => 'Shipping Documents',         'email' => 'admin@carriageglobal.com'],
];

/* Partner/client logos for the homepage marquee. Replaced 2026-08-19 with
   the list the client actually named for this exact purpose (the same
   HOME Page email: "towards the end of the page write our clients...
   google the logo of following clients to insert them"), superseding the
   old-site-scraped list. All eight now have a working logo file.

   The first pass at the last three (oilstates.svg, skadi-offshore.svg,
   logo.webp) each had a real problem unrelated to file format — flagged
   back to the client rather than silently worked around: the first two
   were white-on-transparent marks built for a dark background, invisible
   against this section's light one; logo.webp was a website-header
   screenshot bundling Brooke Dockyard's mark with a second, unrelated
   company's logo and a tagline on a grey banner. The client's follow-up
   files fix the first two directly (oil-states-1.png, skadie_offshore_1.png
   — proper navy/gold and blue marks on white, 2026-08-19). For Brooke's
   (brooke.png) the badge only occupied a ~310x265 region inside a
   2928x291 canvas of flat grey padding — displayed at this tile's actual
   size that would have shrunk the badge to an unreadable speck, so
   brooke-mark.png is a crop down to just the badge with that flat grey
   (229,229,229) chroma-keyed to transparent (checked against this
   section's background for edge fringing before saving — none). The
   original brooke.png is left on disk unused, same as any other
   as-delivered source file. */
$partners = [
    ['name' => 'Sarens',          'logo' => 'assets/img/partners/sarens.png'],
    ['name' => 'IKM Subsea',      'logo' => 'assets/img/partners/ikm-subsea.png'],
    ['name' => 'Favelle Favco',   'logo' => 'assets/img/partners/favelle-favco.png'],
    ['name' => 'Pageo',           'logo' => 'assets/img/partners/Pageo-Logo.gif'],
    ['name' => 'Skadi Offshore',  'logo' => 'assets/img/partners/skadie_offshore_1.png'],
    ['name' => 'Aster Chemical',  'logo' => 'assets/img/partners/aster-logo.webp'],
    ['name' => 'Brooke Dockyard', 'logo' => 'assets/img/partners/brooke-mark.png'],
    ['name' => 'Oilstates',       'logo' => 'assets/img/partners/oil-states-1.png'],
];

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
            <?php if ($i === 0 && !empty($settings['hero_bg_video'])): ?>
            <video
              src="<?php echo url($settings['hero_bg_video']); ?>"
              poster="<?php echo url($slide['image']); ?>"
              autoplay muted loop playsinline preload="metadata"
              aria-hidden="true"></video>
            <?php else: ?>
            <img src="<?php echo url($slide['image']); ?>"
                 alt="<?php echo e($slide['alt_text'] ?: $slide['heading']); ?>"
                 width="1600" height="1200"
                 <?php echo $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
            <?php endif; ?>
          </div>
          <div class="cgs-hero__overlay" aria-hidden="true"></div>

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
  </section>

  <!-- 2 ── VIDEO BAND ───────────────────────────────────────
       The brief places a video section directly after the hero. Real
       client-supplied footage (assets/video/cgs-video-band.mp4), so
       unlike the earlier AI-generated placeholder this needs no honesty
       disclosure. Deliberately plain: no scrim, no overlay copy, just
       the video — native controls so the visitor can pause/mute it
       themselves. Muted + playsinline so mobile permits autoplay;
       cgs.js skips autoplay under reduced motion (controls still let
       someone start it manually). Settings keys are video_band_*, not
       hero_* — this section is distinct from the hero's own optional
       background video (hero_bg_video, above). -->
  <section class="cgs-video-band" aria-label="Carriage Global operations">
    <div class="container-fluid px-4">
      <div class="cgs-video-band__frame">
        <video
          src="<?php echo url($settings['video_band_src']); ?>"
          <?php if (!empty($settings['video_band_poster'])): ?>
          poster="<?php echo url($settings['video_band_poster']); ?>"
          <?php endif; ?>
          autoplay muted loop playsinline controls preload="metadata"></video>
      </div>
    </div>
  </section>

  <!-- 2b ── CERTIFICATIONS ──────────────────────────────────
       Client's own instruction for this exact page (docs/source/emails.txt,
       "1st email on HOME Page") named the ISO/bizSAFE credentials here;
       moved to directly after the video band on client request ("move the
       section after video"). Entity names, registration numbers and the
       UEN used to sit in a left column next to this — now shown in the
       navbar logo lockup instead (includes/header.php), so this band is
       certificates only, full width, large (client: "remove company
       address and only shocase certificates in large size"). Each cert
       embeds the real PDF live via <iframe> (client, earlier: "showcase
       full certificate use it as pdf") instead of a screenshot or cropped
       logo; clicking still opens the same PDF full-size in the Magnific
       Popup lightbox (init in includes/scripts.php) — the iframe is
       pointer-events:none (cgs.css) so the click reaches the wrapping <a>,
       and the href still points at the real file as a no-JS fallback.
       White background (client: "for credentials change background color
       to white") — cards keep a light border/shadow instead of the navy
       fill they used on the dark band. -->
  <section class="cgs-section cgs-credentials" aria-label="Certifications">
    <div class="container-fluid px-4">
      <header class="cgs-credentials__head">
        <p class="cgs-eyebrow">Credentials</p>
        <h2>Certifications</h2>
      </header>

      <div class="cgs-credentials__certs">
        <?php foreach ($certificates as $cert): ?>
        <a class="cgs-credentials__cert cgs-pdf-trigger" href="<?php echo url($cert['file']); ?>" aria-label="<?php echo e($cert['label']); ?> — view full certificate PDF">
          <span class="cgs-credentials__docframe" style="aspect-ratio: <?php echo e($cert['ratio']); ?>;">
            <?php if (!empty($cert['preview'])): ?>
              <img src="<?php echo url($cert['preview']); ?>" alt="" loading="lazy">
            <?php else: ?>
              <iframe src="<?php echo url($cert['file']); ?>#toolbar=0&amp;navpanes=0&amp;scrollbar=0&amp;view=FitH"
                      tabindex="-1" aria-hidden="true" loading="lazy" title=""></iframe>
            <?php endif; ?>
          </span>
          <span class="cgs-credentials__meta"><?php echo e($cert['label']); ?></span>
        </a>
        <?php endforeach; ?>
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
          <h2>Our Services</h2>
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
          <?php /* Plain wrapper in the markup, no classes — cgs.js promotes
             this to a real .swiper-wrapper (and each slide to .swiper-slide)
             only while the carousel below 992px is active, so the swiper
             CSS's own display:flex never touches the desktop pin/crossfade
             layout or the no-JS fallback (every service stacked in normal
             flow), both of which rely on it staying an inert div. */ ?>
          <div data-services-track>
          <?php foreach ($services as $i => $svc):
            $num       = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
            $total     = str_pad((string) count($services), 2, '0', STR_PAD_LEFT);
            $secondary = $galleryRows ? $galleryRows[$i % count($galleryRows)] : null;
          ?>
          <div class="cgs-service-bento" data-service-slide>

            <a class="cgs-service-card cgs-service-bento__feature" href="<?php echo url($svc['link']); ?>"
               <?php if (empty($svc['image'])): ?>aria-label="<?php echo e($svc['title']); ?>"<?php endif; ?>>
              <?php if (!empty($svc['image'])): ?>
              <span class="cgs-service-card__media">
                <img src="<?php echo url($svc['image']); ?>"
                     alt="<?php echo e($svc['alt_text'] ?: $svc['title']); ?>"
                     loading="lazy" width="800" height="600">
              </span>
              <?php endif; ?>
            </a>

            <div class="cgs-service-bento__cell cgs-service-bento__desc">
              <h3 class="cgs-service-bento__desc-title"><?php echo e($svc['title']); ?></h3>
              <?php if (!empty($svc['short_desc'])): ?>
              <p><?php echo e($svc['short_desc']); ?></p>
              <?php endif; ?>
            </div>

            <?php if ($secondary): ?>
            <div class="cgs-service-bento__cell cgs-service-bento__icon">
              <img src="<?php echo url($secondary['image_path']); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="500">
            </div>
            <?php endif; ?>

            <a class="cgs-service-bento__cell cgs-service-bento__cta" href="<?php echo url($svc['link']); ?>">
              <?php if (!empty($svc['image'])): ?>
              <span class="cgs-service-bento__cta-media" aria-hidden="true">
                <img src="<?php echo url($svc['image']); ?>" alt="" loading="lazy" width="300" height="220">
              </span>
              <?php endif; ?>
              <span class="cgs-service-bento__cta-label">
                Read more <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
              </span>
            </a>

            <div class="cgs-service-bento__cell cgs-service-bento__index" aria-hidden="true">
              <strong><?php echo $num; ?></strong>
              <span>/ <?php echo $total; ?></span>
            </div>

          </div>
          <?php endforeach; ?>
          </div>
        </div>

        <div class="cgs-services-pin__track" aria-hidden="true">
          <span class="cgs-services-pin__fill" data-services-fill></span>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- 4 ── DIVISIONS ────────────────────────────────────────
       The client's own two named in-house departments, verbatim from the
       HOME Page email, followed by a 4-item "why choose CGS" strip from
       the same email's second section — one band, two distinct rhythms
       within it. White section background (client: "change unrivaled
       into white background"). Each division is its own "feature module"
       (title/description + 3 asymmetrical photos + a card grid of its
       checklist items), alternating text/gallery sides left-to-right —
       see $divisionMedia below and cgs.css's .cgs-division-feature block. -->
  <section class="cgs-section cgs-divisions">
    <div class="container-fluid px-4">
      <header class="cgs-divisions__head">
        <p class="cgs-eyebrow">Unrivaled control</p>
        <h2>Two specialized in-house departments</h2>
        <p class="cgs-divisions__lede">
          We eliminate third-party delays, hidden markups, and communication
          gaps. By operating our own transport fleet alongside a dedicated
          marine engineering desk, CGS provides single-source accountability
          from the manufacturing floor to the final foundation.
        </p>
      </header>

      <?php
      /* Both divisions share one bespoke "feature module" layout (client,
         division 01: "01 > title/subtitle/description at left, right col
         3 asymmetrical images, section below 2x3 cards for points, whole
         background white"; division 02: "likewise change division 2 with
         exact layout in reverse direction" — same module, gallery and
         text swap sides via --reverse). Real photography per division, not
         generic filler: division 01 is land-transport/fleet themed,
         division 02 is maritime/vessel-charter themed, matching each
         division's own subject.
         Every file below is used exactly once on this page (audited
         2026-08-19, client: "dully check don't repeat images"). The first
         pass at this only checked filenames, not actual file content —
         assets/img/hero/cgs-trailer.jpg turned out to be a byte-for-byte
         copy of assets/img/services/project-freight-forwarding.jpg (same
         photo, two filenames), so it was silently duplicating Service #1's
         card. Caught via md5sum once the client pointed at
         client_assets/pic/ for more real photography. Both divisions were
         cut back to a single full-bleed lead photo each (2026-08-19,
         client: "in division 1 and 2 keep only 1 image") — the --solo
         gallery modifier below already existed in cgs.css for this case
         from when division 02 briefly had only one usable photo, so no CSS
         change was needed, just fewer array entries. Division 01's photo
         was swapped again the same day (client: "change the image for
         division 1") for a shot that both reads landscape at full size
         (2048x1152, no crop needed in the solo box) and carries the
         Carriage Global name directly on the trailer. The two photos here
         are unpublished CGS operations photography, each confirmed unique
         via md5sum against every other file already used on this page
         before being copied into assets/img/. */
      $divisionMedia = [
          [
              ['src' => 'assets/img/fleet/oocl-pipe-trailer.jpg', 'alt' => 'Carriage Global low-bed trailer hauling large yellow industrial pipes past an OOCL container', 'wide' => true],
          ],
          [
              ['src' => 'assets/img/gallery/ops-08.jpg', 'alt' => 'MacGregor ship crane hoisting cargo over the water at a Singapore port', 'wide' => true],
          ],
      ];
      ?>
      <?php foreach ($divisions as $d => $division): ?>
      <?php $reverse = ($d % 2) === 1; ?>
      <article class="cgs-division-feature<?php echo $reverse ? ' cgs-division-feature--reverse' : ''; ?>">
        <div class="cgs-division-feature__top">
          <div class="cgs-division-feature__text" data-reveal>
            <span class="cgs-division-feature__num"><?php echo str_pad((string) ($d + 1), 2, '0', STR_PAD_LEFT); ?></span>
            <h3><?php echo e($division['title']); ?></h3>
            <p class="cgs-division-feature__tagline"><?php echo e($division['tagline']); ?></p>
            <p class="cgs-division-feature__intro"><?php echo e($division['intro']); ?></p>
          </div>
          <?php $images = $divisionMedia[$d] ?? []; ?>
          <div class="cgs-division-feature__gallery<?php echo count($images) === 1 ? ' cgs-division-feature__gallery--solo' : ''; ?>" data-reveal style="--reveal-delay: 120ms">
            <?php foreach ($images as $img): ?>
            <div class="cgs-division-feature__gimg<?php echo !empty($img['wide']) ? ' cgs-division-feature__gimg--wide' : ''; ?>">
              <img src="<?php echo url($img['src']); ?>" alt="<?php echo e($img['alt']); ?>"
                   loading="lazy" width="900" height="460">
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="cgs-division-feature__points">
          <?php foreach ($division['items'] as $i => $item): ?>
          <div class="cgs-point-card" data-reveal style="--reveal-delay: <?php echo $i * 60; ?>ms">
            <i class="fa-solid fa-check" aria-hidden="true"></i>
            <h4><?php echo e($item['title']); ?></h4>
            <p><?php echo e($item['body']); ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </article>
      <?php endforeach; ?>

      <div class="cgs-advantage">
        <p class="cgs-eyebrow">The CGS advantage</p>
        <h3>Why global industrial leaders choose CGS</h3>
        <ul class="cgs-advantage__grid">
          <?php foreach ($advantage as $a => $point): ?>
          <li data-reveal style="--reveal-delay: <?php echo $a * 70; ?>ms">
            <span class="cgs-advantage__icon"><i class="fa-solid <?php echo e($point['icon']); ?>" aria-hidden="true"></i></span>
            <strong><?php echo e($point['title']); ?></strong>
            <p><?php echo e($point['body']); ?></p>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <!-- 4b ── PARTNERS ────────────────────────────────────────
       Continuous auto-scroll logo marquee, moved directly above the
       accent CTA (client: "I want partners at the above cta"). The exact
       client list from the HOME Page email (docs/PROJECT-BRIEF.md open
       question 13) — five of the eight names have no verified logo file
       yet and render as text. -->
  <?php if ($partners): ?>
  <section class="cgs-section cgs-partners" aria-label="Partners and carriers">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>Working with trusted partners</h2>
      </header>
    </div>

    <?php
    /* Swiper's loop mode needs the *real* slide count (before its own
       internal duplication) to cover however many tiles fit on screen at
       once, or it silently disables looping and the whole strip freezes
       (see cgs.js) — on a wide enough monitor, more ~230px tiles fit than
       there are partner logos. Repeating the same list into the DOM a few
       times keeps that covered regardless of screen width, without
       needing a second copy of the data itself. */
    $partnersLoop = array_merge($partners, $partners, $partners);
    /* No loading="lazy" on the logos below: Swiper measures every slide's
       width at init to decide whether loop mode has enough real content,
       and a still-loading (still zero-width) image at that exact moment
       makes the strip look narrower than it is — intermittently, only on
       whichever load was slow that time, which is why this only ever
       happened "sometimes" (client). cgs.js also re-measures once the
       images actually finish loading, as a second line of defence. */
    ?>
    <?php /* aria-hidden: the section's own aria-label already names the
       purpose; without this a screen reader would read out each partner
       name 3x now that the list is tripled for the loop-mode fix above. */ ?>
    <div class="swiper cgs-partners__swiper" data-partners-swiper aria-hidden="true">
      <div class="swiper-wrapper">
        <?php foreach ($partnersLoop as $partner): ?>
        <div class="swiper-slide cgs-partners__slide">
          <?php if (!empty($partner['logo'])): ?>
          <span class="cgs-partners__tile">
            <img src="<?php echo url($partner['logo']); ?>"
                 alt="<?php echo e($partner['name']); ?>"
                 width="160" height="60">
          </span>
          <?php else: ?>
          <span class="cgs-partners__tile cgs-partners__tile--text"><?php echo e($partner['name']); ?></span>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 4c ── ACCENT CTA card ─────────────────────────────────
       Removed 2026-08-19 (client: "remove send us your packing list") —
       left in place, not deleted, in case it comes back. Partners (above)
       now sits directly on the white Divisions-to-teal-CTA seam it was
       styled for, so removing this doesn't leave a color mismatch. -->
  <?php if (false): ?>
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
  <?php endif; ?>

  <!-- 5 ── FLEET TEASER ─────────────────────────────────────
       Two-image split. Copy upgraded 2026-08-19 with the client's own
       equipment specifics (docs/source/emails.txt): deck height and yard
       address from "Email Our Fleet", forklift tonnage from the "HOME
       Page" email's CGS-advantage bullet — replacing the previous
       generic bullets.
       Hidden 2026-08-19 (client: "hide the Our own trailers section") —
       left in place, not deleted, in case it comes back. -->
  <?php if (false): ?>
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
              <strong>Low-bed & super low-bed trailers</strong>
              <span>Deck heights from around 0.8m off the ground, for clearance on high and oversized cargo.</span>
            </li>
            <li>
              <strong>Skeleton chassis & lifting equipment</strong>
              <span>Various chassis sizes, plus forklifts from 3t to 16t for loading, positioning and project cargo.</span>
            </li>
            <li>
              <strong>In-house lashing, lifting & fabrication</strong>
              <span>Qualified rigging teams, plus in-house fabrication of boxes, frames and protective structures.</span>
            </li>
            <li>
              <strong>Open yard storage</strong>
              <span>14 Penjuru Road: space, accessibility and controlled handling before onward transportation.</span>
            </li>
          </ul>
          <a href="<?php echo url('our-fleet.php'); ?>" class="cgs-btn cgs-btn--ghost">
            See the fleet <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 5b ── PROJECT DESK ────────────────────────────────────
       "Contact Our Project Desk" from the client's HOME Page email — real
       named roles and department addresses, not generic placeholders, so a
       visitor with a live shipment reaches the right desk on the first
       try instead of a general enquiry queue. -->
  <section class="cgs-section cgs-section--tint cgs-desk">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>Contact our project desk</h2>
      </header>
      <ul class="cgs-desk__grid">
        <?php foreach ($projectDesk as $n => $contact): ?>
        <li data-reveal style="--reveal-delay: <?php echo $n * 60; ?>ms">
          <span class="cgs-desk__role"><?php echo e($contact['role']); ?></span>
          <?php if (!empty($contact['name'])): ?>
          <strong class="cgs-desk__name"><?php echo e($contact['name']); ?></strong>
          <?php endif; ?>
          <a class="cgs-desk__email" href="mailto:<?php echo e($contact['email']); ?>">
            <i class="fa-solid fa-envelope" aria-hidden="true"></i>
            <?php echo e($contact['email']); ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <!-- 5c ── GALLERY ─────────────────────────────────────────
       Real operations photography, last content section on the page before
       the closing CTA (client: "I need a gallery section at last also").
       Masonry (CSS multi-column, not a fixed-height grid) so portrait and
       landscape shots each keep their own natural aspect ratio rather than
       being force-cropped into a uniform cell (client, earlier: "place
       portrait images in correct layout"). All six files are freshly
       pulled from client_assets/pic/ and confirmed unique via md5sum
       against every other image already used on this page — none of these
       repeat the Hero, Services or Division photography above. Each tile
       opens the full photo in a Magnific Popup lightbox with gallery
       navigation (init in includes/scripts.php, kept out of cgs.js since
       Magnific Popup is jQuery-dependent).

       Hidden 2026-08-19 (client: "hide the gallery section") — markup, CSS
       (.cgs-gallery* in cgs.css) and the Magnific Popup init in
       scripts.php are all left in place, just not rendered, so this can
       come back with a one-line flip if the client wants it again. -->
  <?php if (false): ?>
  <?php
  $galleryShots = [
      ['src' => 'assets/img/gallery/ops-10.jpg', 'alt' => 'Green tarpaulin-wrapped cargo lifted by gantry crane at a Singapore container terminal'],
      ['src' => 'assets/img/gallery/ops-11.jpg', 'alt' => 'Support vessel lifted clear of the water by twin shipyard cranes'],
      ['src' => 'assets/img/gallery/ops-12.jpg', 'alt' => 'Large cylindrical pressure vessel lifted aboard a geared vessel at sea'],
      ['src' => 'assets/img/gallery/ops-13.jpg', 'alt' => "Wrapped process vessel lowered into a ship's cargo hold"],
      ['src' => 'assets/img/gallery/ops-14.jpg', 'alt' => 'Winch and crane equipment secured on a vessel deck alongside shipping containers'],
      ['src' => 'assets/img/gallery/ops-15.jpg', 'alt' => 'Twin deck cranes lifting cylindrical tanks aboard a vessel'],
  ];
  ?>
  <section class="cgs-section cgs-gallery" aria-label="Project photography">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>Our operations, in the field</h2>
      </header>

      <div class="cgs-gallery__grid">
        <?php foreach ($galleryShots as $g => $shot):
          /* Real pixel dimensions, not a guessed 800x600 — masonry sizes
             each tile from its image's own intrinsic ratio, so getting
             width/height right here is what keeps a tall portrait shot
             tall instead of the browser reserving a landscape-shaped box
             for it before the file loads. */
          $shotDims = @getimagesize(__DIR__ . '/' . $shot['src']);
          $shotW = $shotDims[0] ?? 800;
          $shotH = $shotDims[1] ?? 600;
        ?>
        <a class="cgs-gallery__tile cgs-gallery-trigger" href="<?php echo url($shot['src']); ?>"
           data-reveal style="--reveal-delay: <?php echo $g * 60; ?>ms">
          <img src="<?php echo url($shot['src']); ?>"
               alt="<?php echo e($shot['alt']); ?>"
               loading="lazy" width="<?php echo (int) $shotW; ?>" height="<?php echo (int) $shotH; ?>">
          <span class="cgs-gallery__caption" aria-hidden="true">
            <?php echo e($shot['alt']); ?>
            <i class="fa-solid fa-expand" aria-hidden="true"></i>
          </span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 6 ── CTA ──────────────────────────────────────────────
       One CTA intent on this page: "Get a Quote". Same label in the
       nav, the hero and here. Rounded navy card inset on a white section,
       copy on the left and actions on the right, with a hairline squiggle
       and dashed orbit rings as quiet decoration. Last section on the
       page, right above the footer. -->
  <section class="cgs-cta">
    <div class="container-fluid px-4">
      <div class="cgs-cta__card">
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

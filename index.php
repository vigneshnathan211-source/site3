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
|   3. Services            asymmetric bento, 5 cells for 5 services
|   3b. Partners           logo marquee, continuous auto-scroll
|   4. The CGS approach    dark band, numbered editorial rows
|   4b. Accent CTA card    single-accent band, "Send us your packing list"
|   4c. Core values        five-item grid, light
|   4d. Special services   three-card grid, tinted
|   5. Fleet teaser        two-image split
|   6. Operations gallery  contained carousel, arrows either side + autoplay
|   7. FAQ                 accordion, verbatim from the old site
|   8. CTA                 rounded sea-blue card, inset on white
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

/* Partner/carrier logos for the homepage marquee. Pulled from the client's
   old staging site (zvv.cra.mybluehost.me, "Our Clients" section) on
   2026-08-18 — the live carriageglobal.com domain has nothing deployed, so
   this staging URL, supplied by the client, was the actual source. See
   docs/PROJECT-BRIEF.md open question 13: still needs the client to confirm
   these relationships carry over to the new site before this goes live. */
$partners = [
    ['name' => 'Zodiac Milpro',            'logo' => 'assets/img/partners/zodiac-milpro.png'],
    ['name' => 'IKM Subsea',               'logo' => 'assets/img/partners/ikm-subsea.png'],
    ['name' => 'MMA Offshore',             'logo' => 'assets/img/partners/mma-offshore.png'],
    ['name' => 'Subsea 7',                 'logo' => 'assets/img/partners/subsea-7.png'],
    ['name' => 'Sarens',                   'logo' => 'assets/img/partners/sarens.png'],
    ['name' => 'ALE',                      'logo' => 'assets/img/partners/ale.jpg'],
    ['name' => 'Fugro',                    'logo' => 'assets/img/partners/fugro.png'],
    ['name' => 'MacGregor',                'logo' => 'assets/img/partners/macgregor.png'],
    ['name' => 'Favelle Favco',            'logo' => 'assets/img/partners/favelle-favco.png'],
    ['name' => 'Louis Dreyfus Armateurs',  'logo' => 'assets/img/partners/louis-dreyfus-armateurs.png'],
];

/* Core values. Verbatim from the old site (docs/CONTENT.md). */
$coreValues = [
    ['title' => 'Exceed customer expectations', 'body' => 'We are committed to exceeding the expectations of our customers.'],
    ['title' => 'Value our people',              'body' => 'We respect each other, recognizing geographic and cultural differences.'],
    ['title' => 'Work safely',                   'body' => 'We work in a manner that is safe for ourselves and the people around us.'],
    ['title' => 'Act with integrity and ethics',  'body' => 'We conduct business with integrity and trust.'],
    ['title' => 'Embrace teamwork',               'body' => 'We collaborate with our customers, supplier partners, liners, ship owners to achieve success.'],
];

/* Special services. Old-site ancillary services (docs/CONTENT.md,
   docs/PROJECT-BRIEF.md open question 9) — not among the five service
   pages, shown here as a capabilities strip pending the client's call on
   whether they get full pages of their own. Verbatim descriptions. */
/* Images reuse the same operations photography (and its already-verified
   captions) from the gallery fallback set above — thematic pairings, not a
   claim that any one photo documents that exact service. */
$specialServices = [
    [
        'icon'  => 'fa-triangle-exclamation',
        'title' => 'Dangerous goods',
        'body'  => 'A hazardous material is a general name for flammable, explosive, strongly corrosive, toxic, and radioactive materials. Such as gasoline, explosives, strong acid, strong alkali, benzene, naphthalene, etc.',
        'image' => 'assets/img/gallery/ops-05.jpg',
        'alt'   => 'Oversized cargo secured for sea transport',
    ],
    [
        'icon'  => 'fa-right-left',
        'title' => 'Door to door',
        'body'  => 'Door to Door Container and Oversize/Breakbulk Cargo service from Singapore-Batam, and Vice-Versa. Daily Service from Monday to Friday from Singapore to Batam and vice versa.',
        'image' => 'assets/img/gallery/ops-04.jpg',
        'alt'   => 'Cargo transferred to a barge alongside',
    ],
    [
        'icon'  => 'fa-box',
        'title' => 'Customized packing',
        'body'  => 'We provide customized packing and special projects packing solutions including Heat Shrink Wrapping, Plastic Crates Wooden crates, and pallets, as well as cargo choking and lashing services.',
        'image' => 'assets/img/gallery/ops-02.jpg',
        'alt'   => 'Break bulk unit slung under a ship crane',
    ],
];

/* Homepage FAQ. Verbatim from the old site's accordion (docs/CONTENT.md),
   extracted from the live DOM since the old site is a WordPress/Beaver
   Builder accordion that only renders answer text once expanded. */
$faqs = [
    [
        'q' => 'Which mode of the shipment should be advisable in terms of cost saving without having to compromise on safety and time constraints?',
        'a' => "There are various modes of shipment, Loading on Flat rack, Un containerized mode of shipment on Container vessel, On Mafi and Breakbulk, a combination of Road, Rail and Breakbulk or Combination of Road, Barge and Breakbulk/Un containerised option on Container vessel and many other combinations.\n\nClient's requirement to arrive on time with shortest transit time, obviously without compromising on safety and within a budget. Cargo, Hose Reel, weight 125 tons, Diameter 12.5m x Length 14.5m. Ex Yard with limited water front draft level of 1.2m.",
    ],
    [
        'q' => 'What is included in our oversize/over weight/ transportation plan?',
        'a' => 'Many aspects must be co ordinated when transporting oversize/overweight cargo. Each and every aspect should be addressed in the freight transportation plan. Each plan is tailor-made to the scope of the project and should include: custom permits, road permits and type of equipment needed; feasibility study; access to the loading and discharging locations; road and route survey, escort as it varies State by State depending upon project requirement; and potential repositioning of utility lines, trees, signage etc.',
    ],
    [
        'q' => 'Why carefully choosing a right project freight forwarder is important?',
        'a' => 'Only qualified and experienced project freight forwarders can come up with the right advice to save cost without having to compromise on safety. The correct procedure will vary based on freight characteristics and the usage of the right type of equipment, be it barge, type of trailer, lifting versus jack up and skidding, lifting versus jack down cargo on pre-placed concrete stools or a prefabricated frame on the barge, or a combination of both. Using the wrong type of equipment can be very expensive — lifting is not always the right solution; jack up and skidding is the other option to consider depending upon cargo location, infrastructure availability, feasibility, and many other factors. Experience is the key player here.',
    ],
    [
        'q' => 'Can we air freight a Length of 2.28m x Diameter of 2.714m without a charter flight from Ex Norway to Batam within a week? A question raised by one of our in-house clients.',
        'a' => "The answer is no, you cannot — but if you were to rotate the reel, which is unlikely in most cases due to the sensitive cable coil around the reel, or trim excess reel from the bottom and top, fabricate a cradle, in short, modify the dimensions to bring down the height to 2.42m to accommodate, you'd save yourself from the massive cost of chartering a flight and going on a liner schedule.\n\nWe offered a multi-modal transport solution: land transport from Norway to Luxembourg, followed by air transport from Luxembourg to Singapore, trucking from SATS to Jurong Port by road, barge from Singapore to Batu Ampar, Batam, and the last step by road to the final destination at a private jetty where a cable-laying vessel was waiting to receive this cable reel. The entire scope was concluded at USD110,000+, including road survey, obtaining escort and permits, liaising with suppliers, fabricator, airport authorities, airline ground planner, airlines to select the right time of freighter, barge operator, etc.",
    ],
    [
        'q' => 'Can we provide DAP, DDP, and DDU to the end user through the shipper does not have any establishment at the country of destination?',
        'a' => "Yes. We can assist using our license wherever CGS has its own offices, such as in Malaysia (including East Malaysia), Batam (Indonesia), Brunei, and Singapore. Outside these regions, we use our carefully selected in-house project freight forwarders, such as in Norway, Finland, the Netherlands, the Middle East, and India, to offer a complete destination, door-to-door solution — including, but not limited to, using our own company license.\n\nWe worked with Zodiac Milpro, based in Spain and Canada, to send their 15-metre boat from Spain to Langkawi for an exhibition, mobilised back to Singapore for another sea trial presentation, then sent to the UK for a third sea trial before heading back to the country of origin, Spain.",
    ],
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
    <div class="container-fluid px-4">
      <div class="cgs-video-band__frame">
        <video
          src="<?php echo url($settings['hero_video']); ?>"
          <?php if (!empty($settings['hero_video_poster'])): ?>
          poster="<?php echo url($settings['hero_video_poster']); ?>"
          <?php endif; ?>
          autoplay muted loop playsinline preload="metadata"></video>
        <p class="cgs-video-band__disclosure">Concept visualization — final operations footage pending</p>
        <div class="cgs-video-band__overlay">
          <h2>From the packing list to the final site</h2>
        </div>
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

        <div class="cgs-services-pin__track" aria-hidden="true">
          <span class="cgs-services-pin__fill" data-services-fill></span>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- 3b ── PARTNERS ────────────────────────────────────────
       Continuous auto-scroll logo marquee. Logos pulled from the client's
       old staging site (docs/PROJECT-BRIEF.md open question 13) — still
       needs the client to confirm these relationships carry over before
       this ships live. -->
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
    ?>
    <?php /* aria-hidden: the section's own aria-label already names the
       purpose; without this a screen reader would read out each partner
       name 3x now that the list is tripled for the loop-mode fix above. */ ?>
    <div class="swiper cgs-partners__swiper" data-partners-swiper aria-hidden="true">
      <div class="swiper-wrapper">
        <?php foreach ($partnersLoop as $partner): ?>
        <div class="swiper-slide cgs-partners__slide">
          <span class="cgs-partners__tile">
            <img src="<?php echo url($partner['logo']); ?>"
                 alt="<?php echo e($partner['name']); ?>"
                 loading="lazy" width="160" height="60">
          </span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

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

  <!-- 4c ── CORE VALUES ─────────────────────────────────────
       Verbatim from the old site (docs/CONTENT.md). Five-item grid,
       numbered like the approach list above it but in the page's light
       palette. -->
  <section class="cgs-section cgs-values">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>What we stand for</h2>
      </header>
      <ul class="cgs-values__grid">
        <?php foreach ($coreValues as $n => $value): ?>
        <li data-reveal style="--reveal-delay: <?php echo $n * 60; ?>ms">
          <span class="cgs-values__num"><?php echo str_pad((string) ($n + 1), 2, '0', STR_PAD_LEFT); ?></span>
          <strong><?php echo e($value['title']); ?></strong>
          <p><?php echo e($value['body']); ?></p>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <!-- 4d ── SPECIAL SERVICES ────────────────────────────────
       Old-site ancillary services (docs/CONTENT.md, docs/PROJECT-BRIEF.md
       open question 9) — not among the five service pages, shown here as a
       capabilities strip pending the client's call on whether they get full
       pages of their own. No "read more" links: the old site's pointed to
       pages we don't have.

       Card interaction modelled on cargokite.com's "Why us" cards: a corner
       icon badge that tucks away (scale down, transform-origin at its own
       corner) and the card lifting on a soft shadow when hovered — adapted
       here onto a photo card instead of their flat tint, so the icon shrink
       also reveals more of the image underneath it. -->
  <section class="cgs-section cgs-section--tint cgs-special">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>Special services</h2>
      </header>
      <div class="cgs-special__grid">
        <?php foreach ($specialServices as $n => $svc): ?>
        <div class="cgs-special__card" data-reveal style="--reveal-delay: <?php echo $n * 70; ?>ms">
          <div class="cgs-special__media">
            <img src="<?php echo url($svc['image']); ?>"
                 alt="<?php echo e($svc['alt']); ?>"
                 loading="lazy" width="600" height="600">
          </div>
          <span class="cgs-special__icon"><i class="fa-solid <?php echo e($svc['icon']); ?>" aria-hidden="true"></i></span>
          <div class="cgs-special__body">
            <h3><?php echo e($svc['title']); ?></h3>
            <p><?php echo e($svc['body']); ?></p>
          </div>
        </div>
        <?php endforeach; ?>
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
       Contained carousel: dedicated prev/next arrows flank the frame and
       autoplay steps through the photos on its own, same autoplay etiquette
       as the hero carousel (see cgs.js) — pauses on hover/focus and while
       off-screen, never starts under reduced motion. -->
  <section class="cgs-section cgs-gallery-section">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>Recent operations</h2>
      </header>
    </div>

    <?php if ($galleryRows): ?>
    <div class="cgs-gallery-carousel container-fluid px-4">
      <button class="cgs-gallery__arrow cgs-gallery__arrow--prev" data-gallery-prev type="button" aria-label="Previous photo">
        <i class="fa-solid fa-angle-left" aria-hidden="true"></i>
      </button>

      <div class="swiper cgs-gallery" data-gallery-swiper>
        <div class="swiper-wrapper">
          <?php foreach ($galleryRows as $shot): ?>
          <div class="swiper-slide">
            <img src="<?php echo url($shot['image_path']); ?>"
                 alt="<?php echo e($shot['alt_text'] ?: 'Carriage Global project cargo operation'); ?>"
                 loading="lazy" width="720" height="540">
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <button class="cgs-gallery__arrow cgs-gallery__arrow--next" data-gallery-next type="button" aria-label="Next photo">
        <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
      </button>
    </div>
    <?php endif; ?>
  </section>

  <!-- 7 ── FAQ ──────────────────────────────────────────────
       Native <details>/<summary> accordion — no JS needed, accessible by
       default. Verbatim Q&A from the old site (docs/CONTENT.md), pulled
       from its live DOM since the source page only renders answer text
       once a question is expanded. -->
  <?php if ($faqs): ?>
  <section class="cgs-section cgs-faq">
    <div class="container-fluid px-4">
      <header class="cgs-section-head">
        <h2>Frequently asked questions</h2>
      </header>
      <div class="cgs-faq__list">
        <?php foreach ($faqs as $n => $faq): ?>
        <details class="cgs-faq__item"<?php echo $n === 0 ? ' open' : ''; ?>>
          <summary class="cgs-faq__question">
            <span><?php echo e($faq['q']); ?></span>
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
          </summary>
          <div class="cgs-faq__answer">
            <?php foreach (explode("\n\n", $faq['a']) as $para): ?>
            <p><?php echo e($para); ?></p>
            <?php endforeach; ?>
          </div>
        </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 8 ── CTA ──────────────────────────────────────────────
       One CTA intent on this page: "Get a Quote". Same label in the
       nav, the hero and here. Rounded sea-blue card inset on a white
       section, with a dotted halftone texture behind the copy — not
       full-bleed navy anymore. Last section on the page, right above the
       footer. -->
  <section class="cgs-cta">
    <div class="container-fluid px-4">
      <div class="cgs-cta__card">
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
    </div>
  </section>

</main>

<?php
require __DIR__ . '/includes/footer.php';
require __DIR__ . '/includes/scripts.php';

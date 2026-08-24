<?php
/*
|--------------------------------------------------------------------------
| PAST PROJECTS — filterable photo gallery of completed moves
|--------------------------------------------------------------------------
| Rebuilt 2026-08-24 (client, Angeline Tilokani, "HOME PAGE and VIDEO
| REVIESD 1" thread: "f) Past projects (upload all the pictures given via
| email, zip, w/app avoid repetition)" and, separately: "I suggest keeping
| all the project pictures under the 'Past Projects' category and linking
| our YouTube videos"). No dedicated Past Projects photo set or YouTube
| channel URL has actually been supplied in mail yet (checked all threads
| from angeline@carriageglobal.com and contact@webowebsg.com,
| 2026-08-24) — the `gallery` table itself was still empty, so this reuses
| the 17 real, distinct client photos already vetted and captioned across
| index.php / resources.php / contact.php (checksummed against each other
| to confirm none are accidental duplicates, per the client's own "avoid
| repetition" instruction) and seeds them into `gallery` (see
| database/schema.sql). The YouTube link below reuses the same
| settings.youtube_url placeholder convention already live in the header
| and footer, rather than inventing a channel URL nobody sent.
|
| `category` on each row reuses the real `services.title` strings instead
| of an invented taxonomy, so the filter bar below is generated from
| whatever categories actually have photos — not a fixed list that could
| show an empty "Roll On and Off" pill with nothing behind it.
|
| Same .cgs-page-banner pattern as resources.php/contact.php (title matches
| the nav label exactly, kept consistent across pages), and the same
| .cgs-gallery__* masonry + Magnific Popup lightbox (includes/scripts.php)
| already built for the homepage's hidden gallery section. The filter
| interaction is the one new piece: GSAP, loaded from a CDN only on this
| page, animates the outgoing/incoming tile sets as a group on filter
| change — a deliberate, page-scoped exception to this site's otherwise
| self-hosted-only plugin convention (see CLAUDE.md Stack), used because a
| filtered masonry re-layout is exactly the kind of grouped state
| transition plain CSS transitions do not choreograph well. Degrades to an
| instant, unanimated swap if the CDN fails to load or the visitor has
| requested reduced motion — never a hard dependency for the content itself.
*/

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Past Projects | ' . $settings['company_name'];
$pageDesc  = 'Project cargo, heavy lift and break bulk moves handled by Carriage Global — in the field, from packing to final delivery.';
$pageCanon = 'past-projects.php';
$bodyClass = 'page-past-projects';

$pastProjects = db_all(
    $pdo,
    "SELECT * FROM gallery WHERE status = 'active' ORDER BY sort_order ASC, id DESC"
);

/** Lowercase, hyphenated key for a category name — used as the filter's data-attribute value. */
function slugify(string $value): string
{
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim($value, '-');
}

/* Category list is derived from the photos actually present, in the order
   they first appear (sort_order), not a fixed list — see file header. */
$categorySlugs = [];
foreach ($pastProjects as $shot) {
    $cat = $shot['category'] ?: 'General';
    if (!isset($categorySlugs[$cat])) {
        $categorySlugs[$cat] = slugify($cat);
    }
}

/* Video section scaffold — client (Angeline, "HOME PAGE and VIDEO REVIESD 1")
   asked to link "our YouTube videos" (plural) but, checking every thread
   from angeline@carriageglobal.com and contact@webowebsg.com again for this
   change, no actual video links or channel URL have been sent yet (only
   settings.youtube_url's existing '#' placeholder, shared with the header/
   footer social icons — see docs/PROJECT-BRIEF.md open question #15). Built
   as a real, multi-item grid rather than one placeholder box so it needs no
   rework later: fill in `youtube_id` (the id from a youtube.com/watch?v=...
   URL) per entry and the card starts playing that video in an in-page
   lightbox (Magnific Popup's iframe/YouTube support, wired in
   includes/scripts.php on .cgs-video-trigger) instead of leaving the site.
   Until then each card is an honest "coming soon" tile using a real
   operations photo, one per category actually present in the gallery above,
   and clicks fall back to the youtube_url placeholder like the rest of the
   site's unconfigured social links. */
/* Confirmed video IDs, keyed by the exact category title above. Client
   sent https://www.youtube.com/watch?v=1EcNNqCrtwc 2026-08-24 — its own
   on-screen title is "398 TON Knuckle Crane Installation", a heavy-lift
   crane job, so it maps to "Chartering Heavy Lift and Semi-Geared
   Vessels" here rather than being dropped into an arbitrary slot. */
$knownVideoIds = [
    'Chartering Heavy Lift and Semi-Geared Vessels' => '1EcNNqCrtwc',
];

/* Poster for a confirmed video swaps to a real frame from the gallery
   section right above that actually matches what plays: the "398 TON
   Knuckle Crane Installation" video is shot on a MacGregor-branded ship
   crane, and ops-08.jpg ("MacGregor ship crane hoisting cargo over the
   water at a Singapore port") is that same crane — a closer, honest
   preview of the footage than the generic category poster below. */
$knownVideoPosters = [
    'Chartering Heavy Lift and Semi-Geared Vessels' => 'ops-08.jpg',
];

$pastVideos = [];
$videoPosters = ['ops-13.jpg', 'ops-05.jpg', 'ops-04.jpg'];
$vi = 0;
foreach ($categorySlugs as $catTitle => $catSlug) {
    if (isset($knownVideoPosters[$catTitle])) {
        $poster = $knownVideoPosters[$catTitle];
    } else {
        $poster = $videoPosters[$vi % count($videoPosters)];
        $vi++;
    }
    $pastVideos[] = [
        'title'      => $catTitle,
        'poster'     => 'assets/img/gallery/' . $poster,
        'youtube_id' => $knownVideoIds[$catTitle] ?? null, // set once the client sends a real youtube.com/watch?v=... link
    ];
}

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <!-- 1 ── BANNER ───────────────────────────────────────────
       Same .cgs-page-banner pattern as resources.php/contact.php: photo +
       navy scrim, breadcrumb + title only, title matching the nav label. -->
  <section class="cgs-page-banner">
    <div class="cgs-page-banner__media">
      <img src="<?php echo url('assets/img/gallery/ops-12.jpg'); ?>"
           width="1600" height="1200" alt=""
           fetchpriority="high">
    </div>
    <div class="container-fluid px-4">
      <nav class="cgs-page-banner__crumbs" aria-label="Breadcrumb">
        <ol>
          <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
          <li aria-current="page">Past Projects</li>
        </ol>
      </nav>
      <h1>Past Projects</h1>
    </div>
  </section>

  <!-- 2 ── FILTERABLE GALLERY ─────────────────────────────── -->
  <section class="cgs-section cgs-gallery" aria-label="Past project photography">
    <div class="container-fluid px-4">

      <div class="cgs-projects__intro" data-reveal>
        <h2>A working record of the moves we have handled</h2>
        <p>
          Real photography from our own operations, from packing and lashing
          through to final delivery. Filter by service to see a specific
          type of move.
        </p>
        <?php if ($pastProjects): ?>
        <p class="cgs-projects__count">
          <i class="fa-solid fa-camera" aria-hidden="true"></i>
          <?php echo count($pastProjects); ?> photographed operations across <?php echo count($categorySlugs); ?> service<?php echo count($categorySlugs) === 1 ? '' : 's'; ?>
        </p>
        <?php endif; ?>
      </div>

      <?php if ($pastProjects): ?>
      <div class="cgs-filter-bar" role="group" aria-label="Filter projects by service" data-projects-filters data-reveal>
        <button type="button" class="cgs-filter is-active" data-filter="all" aria-pressed="true">All Work</button>
        <?php foreach ($categorySlugs as $catTitle => $catSlug): ?>
        <button type="button" class="cgs-filter" data-filter="<?php echo e($catSlug); ?>" aria-pressed="false">
          <?php echo e($catTitle); ?>
        </button>
        <?php endforeach; ?>
      </div>

      <div class="cgs-gallery__grid" data-projects-grid>
        <?php foreach ($pastProjects as $g => $shot):
          $shotPath = ltrim((string) $shot['image_path'], '/');
          $shotDims = @getimagesize(__DIR__ . '/' . $shotPath);
          $shotW    = $shotDims[0] ?? 800;
          $shotH    = $shotDims[1] ?? 600;
          $shotAlt  = $shot['alt_text'] ?: ($shot['title'] ?: 'Carriage Global project cargo operation');
          $shotCat  = $categorySlugs[$shot['category'] ?: 'General'];
        ?>
        <a class="cgs-gallery__tile cgs-gallery-trigger" href="<?php echo url($shotPath); ?>"
           data-category="<?php echo e($shotCat); ?>"
           data-reveal style="--reveal-delay: <?php echo ($g % 6) * 60; ?>ms">
          <img src="<?php echo url($shotPath); ?>"
               alt="<?php echo e($shotAlt); ?>"
               width="<?php echo (int) $shotW; ?>" height="<?php echo (int) $shotH; ?>">
          <span class="cgs-gallery__caption" aria-hidden="true">
            <?php echo e($shotAlt); ?>
            <i class="fa-solid fa-expand" aria-hidden="true"></i>
          </span>
        </a>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <p class="text-center text-muted py-5">
        [Placeholder — project photography is being added from the admin. Check back soon.]
      </p>
      <?php endif; ?>

    </div>
  </section>

  <!-- 3 ── VIDEO ───────────────────────────────────────────────
       Dedicated YouTube section (client, Angeline: "I suggest ... linking
       our YouTube videos" — see file header, and the $pastVideos comment
       above, for the full mail research). A real multi-video grid, not one
       teaser box: every card plays in an in-page lightbox via Magnific
       Popup's iframe/YouTube support (.cgs-video-trigger, wired in
       includes/scripts.php) the moment a card has a real youtube_id.
       Sits between the gallery and the CTA so the CTA stays the last
       section in <main> (its footer-overlap treatment below only fires
       for main:has(> .cgs-cta:last-child)). -->
  <?php if ($pastVideos): ?>
  <section class="cgs-section cgs-section--dark cgs-projects__video" aria-label="Video footage">
    <div class="container-fluid px-4">
      <header class="cgs-projects__video-head" data-reveal>
        <h2>See it in motion</h2>
        <p>
          Photos tell half the story. For the full lift sequences and
          load-outs, our YouTube channel carries the footage.
        </p>
      </header>
      <div class="cgs-projects__video-grid" data-reveal>
        <?php foreach ($pastVideos as $v => $video):
          $hasVideo = !empty($video['youtube_id']);
          $videoHref = $hasVideo
              ? 'https://www.youtube.com/watch?v=' . rawurlencode($video['youtube_id'])
              : $settings['youtube_url'];
        ?>
        <a href="<?php echo e($videoHref); ?>"
           class="cgs-projects__video-card<?php echo $hasVideo ? ' cgs-video-trigger' : ''; ?>"
           <?php echo $hasVideo ? '' : 'target="_blank" rel="noopener"'; ?>
           aria-label="<?php echo $hasVideo ? 'Play: ' . e($video['title']) : 'Coming soon: ' . e($video['title']) . ' — visit our YouTube channel'; ?>">
          <img src="<?php echo url($video['poster']); ?>" alt="" width="800" height="533" loading="lazy">
          <span class="cgs-projects__video-play" aria-hidden="true"><i class="fa-solid fa-play"></i></span>
          <?php if (!$hasVideo): ?>
          <span class="cgs-projects__video-badge">Coming Soon</span>
          <?php endif; ?>
          <span class="cgs-projects__video-title"><?php echo e($video['title']); ?></span>
        </a>
        <?php endforeach; ?>
      </div>
      <a href="<?php echo e($settings['youtube_url']); ?>" class="cgs-btn cgs-btn--on-dark" target="_blank" rel="noopener">
        <i class="fa-brands fa-youtube" aria-hidden="true"></i> Visit our YouTube channel
      </a>
    </div>
  </section>
  <?php endif; ?>

  <!-- 4 ── CTA ─────────────────────────────────────────────────
       Same content as index.php/resources.php's closing CTA — one CTA
       intent site-wide, kept consistent rather than a page-specific pitch. -->
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
?>

<?php if ($pastProjects): ?>
<!-- GSAP (CDN, this page only) — see the file header for why. Guarded
     entirely: if the CDN fails to load, window.gsap is undefined and the
     filter script below falls back to an instant class-toggle swap.
     Placed after footer.php/scripts.php (not before, like a typical
     page-specific script would sit) so <footer> stays <main>'s immediate
     next sibling — the CTA's footer-overlap treatment in cgs.css keys off
     main:has(> .cgs-cta:last-child) + .cgs-footer, an adjacent-sibling
     selector that a script tag in between would silently break. -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
  (function () {
    var grid = document.querySelector('[data-projects-grid]');
    var filterBar = document.querySelector('[data-projects-filters]');
    if (!grid || !filterBar) { return; }

    var tiles = Array.prototype.slice.call(grid.querySelectorAll('[data-category]'));
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    var hasGsap = !!(window.gsap && typeof window.gsap.to === 'function');

    function applyFilter(filter) {
      var toHide = [];
      var toShow = [];
      tiles.forEach(function (tile) {
        var matches = filter === 'all' || tile.getAttribute('data-category') === filter;
        var isHidden = tile.classList.contains('is-filtered-out');
        if (matches && isHidden) { toShow.push(tile); }
        if (!matches && !isHidden) { toHide.push(tile); }
      });

      if (!hasGsap || reduceMotion.matches) {
        toHide.forEach(function (t) { t.classList.add('is-filtered-out'); });
        toShow.forEach(function (t) { t.classList.remove('is-filtered-out'); });
        return;
      }

      if (toHide.length) {
        gsap.to(toHide, {
          opacity: 0, scale: 0.92, duration: 0.25, ease: 'power1.in', stagger: 0.02,
          onComplete: function () {
            toHide.forEach(function (t) { t.classList.add('is-filtered-out'); });
          }
        });
      }
      if (toShow.length) {
        toShow.forEach(function (t) { t.classList.remove('is-filtered-out'); });
        gsap.fromTo(toShow,
          { opacity: 0, scale: 0.92, y: 14 },
          {
            opacity: 1, scale: 1, y: 0, duration: 0.4, ease: 'power2.out', stagger: 0.035,
            delay: toHide.length ? 0.18 : 0
          }
        );
      }
    }

    filterBar.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-filter]');
      if (!btn || btn.classList.contains('is-active')) { return; }

      filterBar.querySelectorAll('[data-filter]').forEach(function (b) {
        b.classList.remove('is-active');
        b.setAttribute('aria-pressed', 'false');
      });
      btn.classList.add('is-active');
      btn.setAttribute('aria-pressed', 'true');

      applyFilter(btn.getAttribute('data-filter'));
    });
  })();
</script>
<?php endif; ?>

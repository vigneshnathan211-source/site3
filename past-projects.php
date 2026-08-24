<?php
/*
|--------------------------------------------------------------------------
| PAST PROJECTS — photo gallery of completed moves
|--------------------------------------------------------------------------
| Added to the primary nav 2026-08-24 (client, "HOME PAGE and VIDEO REVIESD 1"
| thread: "f) Past projects (upload all the pictures given via email, zip,
| w/app avoid repetition)"). Resolves docs/PROJECT-BRIEF.md open question 12
| in favour of reading #1: a photo gallery of past moves, not written case
| studies — so this reuses the existing `gallery` table rather than a new
| `projects` table.
|
| Reuses the .cgs-gallery__* masonry grid + Magnific Popup lightbox already
| built for the (currently hidden) homepage gallery section — see the
| section 5c comment in index.php and the `.cgs-gallery-trigger` init in
| includes/scripts.php.
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

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <section class="cgs-section cgs-section--dark">
    <div class="container-fluid px-4">
      <p class="cgs-eyebrow cgs-eyebrow--on-dark">Past Projects</p>
      <h1>Cargo we have moved</h1>
      <p style="max-width: 60ch; color: rgba(255,255,255,.72);">
        A working record of project cargo, heavy lift and break bulk operations
        handled by Carriage Global, from packing and lashing through to final
        delivery.
      </p>
    </div>
  </section>

  <section class="cgs-section cgs-gallery" aria-label="Past project photography">
    <div class="container-fluid px-4">
      <?php if ($pastProjects): ?>
      <div class="cgs-gallery__grid">
        <?php foreach ($pastProjects as $g => $shot):
          $shotPath = ltrim((string) $shot['image_path'], '/');
          $shotDims = @getimagesize(__DIR__ . '/' . $shotPath);
          $shotW    = $shotDims[0] ?? 800;
          $shotH    = $shotDims[1] ?? 600;
          $shotAlt  = $shot['alt_text'] ?: ($shot['title'] ?: 'Carriage Global project cargo operation');
        ?>
        <a class="cgs-gallery__tile cgs-gallery-trigger" href="<?php echo url($shotPath); ?>"
           data-reveal style="--reveal-delay: <?php echo ($g % 6) * 60; ?>ms">
          <img src="<?php echo url($shotPath); ?>"
               alt="<?php echo e($shotAlt); ?>"
               loading="lazy" width="<?php echo (int) $shotW; ?>" height="<?php echo (int) $shotH; ?>">
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

</main>

<?php
require __DIR__ . '/includes/footer.php';
require __DIR__ . '/includes/scripts.php';

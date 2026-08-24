<?php
/*
|--------------------------------------------------------------------------
| 404 — PAGE NOT FOUND
|--------------------------------------------------------------------------
| Served by .htaccess (ErrorDocument 404 /404.php) for any missing file or
| bad URL, and directly reachable at /404.php for testing. Sends a real
| 404 status so search engines do not index a dead page as content.
*/

require_once __DIR__ . '/includes/bootstrap.php';

http_response_code(404);

$pageTitle = 'Page Not Found | ' . $settings['company_name'];
$pageDesc  = 'The page you are looking for has moved or no longer exists.';
$bodyClass = 'page-404';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">
  <section class="cgs-404">
    <div class="container-fluid px-4">
      <div class="cgs-404__inner">
        <p class="cgs-eyebrow cgs-eyebrow--on-dark">Error 404</p>
        <h1>This page has shipped somewhere else.</h1>
        <p>
          The link you followed is broken, or the page has moved. Check the
          address, or pick up from one of the pages below.
        </p>

        <div class="cgs-404__actions">
          <a href="<?php echo url('index.php'); ?>" class="cgs-btn cgs-btn--on-dark">
            Back to home <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
          </a>
          <a href="<?php echo url('contact.php'); ?>" class="cgs-btn cgs-btn--outline-light">
            Contact us
          </a>
        </div>

        <ul class="cgs-404__links">
          <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
          <li><a href="<?php echo url('our-fleet.php'); ?>">Our Fleet</a></li>
          <li><a href="<?php echo url('services.php'); ?>">Services</a></li>
          <li><a href="<?php echo url('resources.php'); ?>">Resources</a></li>
          <li><a href="<?php echo url('past-projects.php'); ?>">Past Projects</a></li>
          <li><a href="<?php echo url('contact.php'); ?>">Contact Us</a></li>
        </ul>
      </div>
    </div>
  </section>
</main>

<?php
require __DIR__ . '/includes/footer.php';
require __DIR__ . '/includes/scripts.php';

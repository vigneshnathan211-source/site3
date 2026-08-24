<?php
/*
|--------------------------------------------------------------------------
| THANK YOU — form success page
|--------------------------------------------------------------------------
| Where submit-lead.php redirects after a successful insert. Also the
| Google Ads conversion URL once ads are running (CLAUDE.md file layout) —
| kept as its own page rather than an inline banner on contact.php so that
| conversion tracking has a clean, dedicated URL to fire on.
*/

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Thank You | ' . $settings['company_name'];
$pageDesc  = 'Thank you for contacting Carriage Global. Our team will be in touch shortly.';
$pageCanon = 'thankyou.php';
$bodyClass = 'page-thankyou';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <section class="cgs-404">
    <div class="container-fluid px-4">
      <div class="cgs-404__inner">
        <p class="cgs-eyebrow cgs-eyebrow--on-dark"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Message Sent</p>
        <h1>Thank you, we've received your enquiry.</h1>
        <p>A member of our project logistics team will review the details and
           get back to you, usually within one business day. For anything
           urgent, call us directly on
           <a href="tel:<?php echo e($phoneTel); ?>" class="cgs-404__inline-link"><?php echo e($settings['phone']); ?></a>.</p>
        <div class="cgs-404__actions">
          <a href="<?php echo url('index.php'); ?>" class="cgs-btn cgs-btn--on-dark">
            Back To Home <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
          </a>
          <a href="<?php echo url('services.php'); ?>" class="cgs-btn cgs-btn--outline-light">
            Explore Our Services
          </a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php
require __DIR__ . '/includes/footer.php';
require __DIR__ . '/includes/scripts.php';

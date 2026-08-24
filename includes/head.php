<?php
/*
|--------------------------------------------------------------------------
| <head>
|--------------------------------------------------------------------------
| Set these before including, in the page itself:
|
|   $pageTitle   string  full <title>
|   $pageDesc    string  meta description
|   $pageCanon   string  canonical path, e.g. 'our-fleet.php' (optional)
|   $pageOgImage string  path to the share image (optional)
|   $bodyClass   string  extra class on <body> (optional)
*/

$pageTitle   = $pageTitle   ?? $settings['company_name'];
$pageDesc    = $pageDesc    ?? 'Project logistics, heavy lift and break bulk freight forwarding from Singapore.';
$pageCanon   = $pageCanon   ?? current_page();
$pageOgImage = $pageOgImage ?? $settings['logo'];
$bodyClass   = $bodyClass   ?? '';
?>
<!DOCTYPE html>
<html lang="en-SG">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Flags that JS is available, before first paint. The scroll-reveal styles
     are scoped to html.js, so a visitor without JS gets the content plainly
     visible instead of a permanently blank page, and a visitor with JS never
     sees it render then hide. Must stay inline and stay here. -->
<script>document.documentElement.classList.add('js');</script>

<title><?php echo e($pageTitle); ?></title>
<meta name="description" content="<?php echo e($pageDesc); ?>">
<meta name="robots" content="index, follow, max-image-preview:large">
<meta name="geo.region" content="SG">
<meta name="geo.placename" content="Singapore">
<link rel="canonical" href="<?php echo url($pageCanon === 'index.php' ? '' : $pageCanon); ?>">

<meta property="og:locale" content="en_SG">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php echo e($settings['company_name']); ?>">
<meta property="og:title" content="<?php echo e($pageTitle); ?>">
<meta property="og:description" content="<?php echo e($pageDesc); ?>">
<meta property="og:image" content="<?php echo url($pageOgImage); ?>">

<link rel="shortcut icon" href="<?php echo url($settings['favicon']); ?>" type="image/x-icon">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php /* IBM Plex Mono: instrument-panel chrome only (cgs-waypoint's
         coordinate-style labels) — Plus Jakarta Sans stays the body/heading
         face everywhere else. */ ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?php echo url('assets/css/plugins/bootstrap.min.css'); ?>">
<link rel="stylesheet" href="<?php echo url('assets/css/plugins/fontawesome.css'); ?>">
<link rel="stylesheet" href="<?php echo url('assets/css/plugins/aos.css'); ?>">
<link rel="stylesheet" href="<?php echo url('assets/css/plugins/owlcarousel.min.css'); ?>">
<link rel="stylesheet" href="<?php echo url('assets/css/plugins/magnific-popup.css'); ?>">
<link rel="stylesheet" href="<?php echo url('assets/css/plugins/swiper-bundle.min.css'); ?>">
<!-- Brand layer — always last, so it wins over the plugin defaults -->
<link rel="stylesheet" href="<?php echo asset_url('assets/css/cgs.css'); ?>">

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": <?php echo json_encode($settings['company_name']); ?>,
  "url": <?php echo json_encode(BASE_URL); ?>,
  "logo": <?php echo json_encode(BASE_URL . $settings['logo']); ?>,
  "telephone": <?php echo json_encode($settings['phone']); ?>,
  "faxNumber": <?php echo json_encode($settings['fax']); ?>,
  "email": <?php echo json_encode($settings['email']); ?>,
  "address": [
    {
      "@type": "PostalAddress",
      "streetAddress": "21 Bukit Batok Crescent, WCEGA Tower #17-82",
      "addressLocality": "Singapore",
      "postalCode": "658065",
      "addressCountry": "SG"
    },
    {
      "@type": "PostalAddress",
      "streetAddress": "Suite 28.02, 28th Floor, Menara Zurich No.15, Jalan Dato Abdullah Tahir",
      "addressLocality": "Johor Bahru",
      "addressRegion": "Johor",
      "addressCountry": "MY"
    }
  ],
  "hasCredential": "ISO 9001:2015"
}
</script>
</head>
<body class="<?php echo e($bodyClass); ?>">
<a class="cgs-skip-link" href="#main-content">Skip to main content</a>

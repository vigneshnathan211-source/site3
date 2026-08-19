<?php
/*
|--------------------------------------------------------------------------
| SITE HEADER — top info bar + sticky navbar + mobile dropdown menu
|--------------------------------------------------------------------------
| Every value comes from $settings / $services (see includes/bootstrap.php),
| so the client can change a phone number or reorder the services dropdown
| from the admin without anyone touching this file.
|
| The primary nav is defined once, in $navItems below, and rendered three
| times (desktop, mobile, footer-adjacent). Adding a page means adding one
| array entry — not editing three lists that drift apart.
|
| The mobile menu (#cgsMobileMenu) drops down from the navbar itself, the
| same interaction language as the desktop Services hover dropdown, rather
| than a side-drawer/offcanvas panel — see cgs.js for the toggle logic.
*/

$servicesLinks = array_map(
    static fn(array $s): array => ['label' => $s['title'], 'href' => $s['link']],
    $services
);

$navItems = [
    ['label' => 'Home',       'href' => 'index.php',     'match' => ['index.php']],
    ['label' => 'Our Fleet',  'href' => 'our-fleet.php', 'match' => ['our-fleet.php']],
    [
        'label'    => 'Services',
        'href'     => 'services.php',
        'match'    => array_merge(['services.php'], array_column($services, 'link')),
        'children' => $servicesLinks,
    ],
    ['label' => 'Projects',   'href' => 'projects.php',  'match' => ['projects.php', 'project-single.php']],
    ['label' => 'Contact Us', 'href' => 'contact.php',   'match' => ['contact.php']],
];
?>

<?php
// Only render a social icon when that URL is actually set, so an
// unconfigured site shows no dead placeholders.
$activeSocials = array_filter(
    $socialPlatforms,
    static fn($meta, $col): bool => !empty($settings[$col]),
    ARRAY_FILTER_USE_BOTH
);
?>
<!-- ==========================================================
     TOP INFO BAR
     Phone and email, plus the social icons. The 24/7 line and the
     address still live only in the footer and the mobile menu —
     crowding all four in here made the row unreadable and gave the
     eye nothing to land on.
     Hidden below lg, where the mobile menu carries the same details.
     ========================================================== -->
<div class="cgs-topbar d-none d-lg-block">
  <div class="container-fluid px-4">
    <div class="cgs-topbar__inner">

      <div class="cgs-topbar__contacts">
        <a class="cgs-topbar__contact" href="tel:<?php echo e($phoneTel); ?>">
          <i class="fa-solid fa-phone" aria-hidden="true"></i>
          <span><?php echo e($settings['phone']); ?></span>
        </a>
        <?php if (!empty($settings['email'])): ?>
        <a class="cgs-topbar__contact" href="mailto:<?php echo e($settings['email']); ?>">
          <i class="fa-solid fa-envelope" aria-hidden="true"></i>
          <span><?php echo e($settings['email']); ?></span>
        </a>
        <?php endif; ?>
      </div>

      <div class="cgs-topbar__right">
        <?php if ($activeSocials): ?>
        <ul class="cgs-topbar__socials">
          <?php foreach ($activeSocials as $col => $meta): ?>
          <li>
            <a href="<?php echo e($settings[$col]); ?>" target="_blank" rel="noopener"
               aria-label="<?php echo e($meta[1]); ?>">
              <i class="<?php echo e($meta[0]); ?>" aria-hidden="true"></i>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <a href="<?php echo url('admin/index.php'); ?>" class="cgs-topbar__login" aria-label="Admin login">
          <i class="fa-solid fa-lock" aria-hidden="true"></i>
        </a>
      </div>

    </div>
  </div>
</div>

<!-- ==========================================================
     MAIN NAVBAR — becomes sticky once the top bar scrolls away
     ========================================================== -->
<header class="cgs-header" id="cgs-header">
  <div class="container-fluid px-4">
    <div class="cgs-header__inner">

      <a class="cgs-logo" href="<?php echo url('index.php'); ?>">
        <img src="<?php echo url($settings['logo']); ?>"
             alt="<?php echo e($settings['company_name']); ?>"
             width="84" height="84">
        <span class="cgs-logo__text">
          <span class="cgs-logo__line">Carriage Global (S) Pte Ltd &middot; UEN 200714170K</span>
          <span class="cgs-logo__line">Carriage Global Sdn Bhd &middot; Reg. No. 201701022532</span>
        </span>
      </a>

      <nav class="cgs-nav d-none d-xl-block" aria-label="Primary">
        <ul>
          <?php foreach ($navItems as $item): ?>
            <?php $isActive = nav_active($item['match']); ?>
            <li class="<?php echo !empty($item['children']) ? 'has-dropdown' : ''; ?>">
              <a href="<?php echo url($item['href']); ?>" class="<?php echo $isActive; ?>"
                 <?php echo $isActive ? 'aria-current="page"' : ''; ?>>
                <?php echo e($item['label']); ?>
                <?php if (!empty($item['children'])): ?>
                  <i class="fa-solid fa-angle-down" aria-hidden="true"></i>
                <?php endif; ?>
              </a>
              <?php if (!empty($item['children'])): ?>
              <ul class="cgs-submenu">
                <?php foreach ($item['children'] as $child): ?>
                <li>
                  <a href="<?php echo url($child['href']); ?>"
                     class="<?php echo nav_active($child['href']); ?>">
                    <?php echo e($child['label']); ?>
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>

      <div class="cgs-header__actions">
        <a href="tel:<?php echo e($phoneTel); ?>" class="cgs-header__call d-none d-xl-flex">
          <span class="cgs-header__call-icon"><i class="fa-solid fa-phone" aria-hidden="true"></i></span>
          <span class="cgs-header__call-text">
            <small>Speak to our team</small>
            <strong><?php echo e($settings['phone']); ?></strong>
          </span>
        </a>

        <a href="<?php echo url('contact.php'); ?>" class="cgs-btn cgs-btn--primary d-none d-md-inline-flex">
          Get a Quote <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
        </a>

        <button class="cgs-burger d-xl-none" type="button"
                data-cgs-menu-toggle aria-expanded="false" aria-controls="cgsMobileMenu"
                aria-label="Open menu">
          <i class="fa-solid fa-bars-staggered cgs-burger__open" aria-hidden="true"></i>
          <i class="fa-solid fa-xmark cgs-burger__close" aria-hidden="true"></i>
        </button>
      </div>

    </div>

    <!-- ==========================================================
         MOBILE MENU
         Drops down from the navbar itself, like the desktop Services
         hover dropdown just above — not a drawer sliding in from a screen
         edge. A child of <header> (already position:sticky, so it is a
         positioning context) so it always sits at the navbar's own
         bottom edge, top-bar showing or not, with no JS height math.
         `inert` in the markup is the no-JS-yet baseline: hidden and
         unreachable by keyboard until cgs.js takes over.
         ========================================================== -->
    <div class="cgs-mobile-panel" id="cgsMobileMenu" data-cgs-menu inert>
      <div class="cgs-mobile-panel__head">
        <h2>Menu</h2>
        <button type="button" class="cgs-mobile-panel__close" data-cgs-menu-close aria-label="Close menu">
          <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
      </div>

      <div class="cgs-mobile-panel__body">
    <ul class="cgs-mobile-nav">
      <?php foreach ($navItems as $i => $item): ?>
        <?php if (empty($item['children'])): ?>
          <li>
            <a href="<?php echo url($item['href']); ?>" class="<?php echo nav_active($item['match']); ?>">
              <?php echo e($item['label']); ?>
            </a>
          </li>
        <?php else: ?>
          <?php $collapseId = 'cgsMobileSub' . $i; ?>
          <li>
            <a class="cgs-mobile-nav__toggle <?php echo nav_active($item['match']); ?>"
               data-bs-toggle="collapse" href="#<?php echo $collapseId; ?>" role="button"
               aria-expanded="<?php echo nav_active($item['match']) ? 'true' : 'false'; ?>"
               aria-controls="<?php echo $collapseId; ?>">
              <?php echo e($item['label']); ?>
              <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
            </a>
            <div class="collapse <?php echo nav_active($item['match'], 'show'); ?>" id="<?php echo $collapseId; ?>">
              <ul class="cgs-mobile-nav__sub">
                <li>
                  <a href="<?php echo url($item['href']); ?>">All <?php echo e($item['label']); ?></a>
                </li>
                <?php foreach ($item['children'] as $child): ?>
                <li>
                  <a href="<?php echo url($child['href']); ?>" class="<?php echo nav_active($child['href']); ?>">
                    <?php echo e($child['label']); ?>
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>

    <div class="cgs-mobile-cta">
      <a href="<?php echo url('contact.php'); ?>" class="cgs-btn cgs-btn--primary w-100 justify-content-center">
        Get a Quote
      </a>
      <a href="tel:<?php echo e($phoneTel); ?>" class="cgs-btn cgs-btn--ghost w-100 justify-content-center">
        <i class="fa-solid fa-phone" aria-hidden="true"></i> <?php echo e($settings['phone']); ?>
      </a>
      <?php if (!empty($settings['phone_247'])): ?>
      <a href="tel:<?php echo e($phone247Tel); ?>" class="cgs-btn cgs-btn--ghost w-100 justify-content-center">
        <i class="fa-solid fa-headset" aria-hidden="true"></i> 24/7 <?php echo e($settings['phone_247']); ?>
      </a>
      <?php endif; ?>
      <a href="mailto:<?php echo e($settings['email']); ?>" class="cgs-mobile-cta__email">
        <i class="fa-solid fa-envelope" aria-hidden="true"></i> <?php echo e($settings['email']); ?>
      </a>
    </div>

    <div class="cgs-mobile-foot">
      <p class="cgs-mobile-foot__addr">
        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
        <?php echo e($settings['address']); ?>
      </p>
      <?php if (!empty($settings['iso_statement'])): ?>
        <p class="cgs-mobile-foot__iso"><?php echo e($settings['iso_statement']); ?></p>
      <?php endif; ?>
      <?php if ($activeSocials): ?>
      <ul class="cgs-mobile-foot__socials">
        <?php foreach ($activeSocials as $col => $meta): ?>
        <li>
          <a href="<?php echo e($settings[$col]); ?>" target="_blank" rel="noopener"
             aria-label="<?php echo e($meta[1]); ?>">
            <i class="<?php echo e($meta[0]); ?>" aria-hidden="true"></i>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>
      </div>
    </div>

    <!-- Dims the page behind the panel; also closes it on click. Fixed
         (not absolute) so it covers the full viewport regardless of where
         in the document the header currently sits. -->
    <div class="cgs-mobile-scrim" data-cgs-menu-scrim aria-hidden="true"></div>

</header>

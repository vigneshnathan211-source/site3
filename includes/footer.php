<?php
/*
|--------------------------------------------------------------------------
| SITE FOOTER
|--------------------------------------------------------------------------
| Placeholder build — enough to close every page correctly and carry the
| contact details. The full four-column footer from the brief comes with
| the rest of the homepage sections.
|
| The logo shown here is the white-background JPEG, which cannot sit on the
| navy panel, so the footer uses a type-only lockup until the client sends a
| knockout version (settings.logo_light). See docs/ASSET-INVENTORY.md.
*/
?>
<footer class="cgs-footer">
  <div class="container-fluid px-4">
    <div class="row g-4 py-5">

      <div class="col-lg-4">
        <?php if (!empty($settings['logo_light'])): ?>
          <img src="<?php echo url($settings['logo_light']); ?>"
               alt="<?php echo e($settings['company_name']); ?>"
               class="cgs-footer__logo" width="150" height="56">
        <?php else: ?>
          <p class="cgs-footer__wordmark">
            <strong>Carriage Global</strong><small>(S) Pte Ltd</small>
          </p>
        <?php endif; ?>

        <p class="cgs-footer__blurb">
          Integrated customized logistics services for the oil and gas, offshore,
          heavy lift, energy, construction and mining sectors, managing and
          optimizing supply chains from origin to final site.
        </p>

        <?php if (!empty($settings['iso_statement'])): ?>
          <p class="cgs-footer__iso">
            <i class="fa-solid fa-certificate" aria-hidden="true"></i>
            <?php echo e($settings['iso_statement']); ?>
          </p>
        <?php endif; ?>

        <?php if (!empty($activeSocials)): ?>
        <ul class="cgs-footer__socials">
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

      <div class="col-lg-2 col-md-4">
        <h3>Company</h3>
        <ul class="cgs-footer__links">
          <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
          <li><a href="<?php echo url('our-fleet.php'); ?>">Our Fleet</a></li>
          <li><a href="<?php echo url('services.php'); ?>">Services</a></li>
          <li><a href="<?php echo url('resources.php'); ?>">Resources</a></li>
          <li><a href="<?php echo url('contact.php'); ?>">Contact Us</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-4">
        <h3>Services</h3>
        <ul class="cgs-footer__links">
          <?php foreach ($services as $svc): ?>
            <li><a href="<?php echo url($svc['link']); ?>"><?php echo e($svc['title']); ?></a></li>
          <?php endforeach; ?>
          <?php if (empty($services)): ?>
            <li><a href="<?php echo url('services.php'); ?>">All services</a></li>
          <?php endif; ?>
        </ul>
      </div>

      <div class="col-lg-3 col-md-4">
        <h3>Singapore</h3>
        <ul class="cgs-footer__contact">
          <li>
            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
            <span><?php echo e($settings['address']); ?></span>
          </li>
          <li>
            <i class="fa-solid fa-phone" aria-hidden="true"></i>
            <a href="tel:<?php echo e($phoneTel); ?>"><?php echo e($settings['phone']); ?></a>
          </li>
          <?php if (!empty($settings['phone_247'])): ?>
          <li>
            <i class="fa-solid fa-headset" aria-hidden="true"></i>
            <a href="tel:<?php echo e($phone247Tel); ?>">24/7 <?php echo e($settings['phone_247']); ?></a>
          </li>
          <?php endif; ?>
          <li>
            <i class="fa-solid fa-envelope" aria-hidden="true"></i>
            <a href="mailto:<?php echo e($settings['email']); ?>"><?php echo e($settings['email']); ?></a>
          </li>
          <?php if (!empty($settings['uen'])): ?>
          <li>
            <i class="fa-solid fa-building" aria-hidden="true"></i>
            <span>UEN <?php echo e($settings['uen']); ?></span>
          </li>
          <?php endif; ?>
        </ul>
      </div>

    </div>

    <div class="cgs-footer__bar">
      <p>&copy; <?php echo date('Y'); ?> <?php echo e($settings['company_name']); ?>. All rights reserved.</p>
      <p><a href="https://www.webowebsingapore.com" target="_blank" rel="noopener">Developed by <b>Weboweb Singapore</b></a></p>
    </div>
  </div>
</footer>

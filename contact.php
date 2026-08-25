<?php
/*
|--------------------------------------------------------------------------
| CONTACT US — enquiry form, SG + MY offices, other ways to reach us
|--------------------------------------------------------------------------
| docs/PROJECT-BRIEF.md sitemap row 6: "Enquiry form, SG + MY offices, map."
| Client brief ("Let's design contact page... form with image at left side,
| company address location map section, whatsapp and other ways to
| contact"), 2026-08-24.
|
| Real vs. placeholder, per that same brief ("keep if you have it use it
| otherwise do a placeholder"):
|   - SG office name/address/phone/phone_247/email: real (settings table).
|   - MY office name/reg no/address: real. my_phone / my_email are unset in
|     settings, so those two lines are simply omitted (existing !empty()
|     convention), not invented.
|   - whatsapp_number is unset, so the WhatsApp tile below renders as a
|     visibly-marked "coming soon" cell instead of a dead wa.me link.
|   - map_embed_url / my_map_embed_url: real Google Maps embed URLs the
|     client sent 2026-08-24, one per office, each rendered in its own map
|     card in the .cgs-map-stack. Either one falls back independently to a
|     placeholder (pin + copy, real "Get Directions" search link) if its
|     embed URL is ever unset again.
|
| Layout: two asymmetric splits (DESIGN.md's convention, never 1fr/1fr) —
| photo/form, then office-cards/map — plus a hairline "other ways to reach
| us" grid, the same 1px-gap-grid technique as the homepage's Project Desk
| section but built for contact-method tiles instead of named people.
|
| 2026-08-25: the floating "call us now" phone badge over the form photo
| was removed per client feedback (the phone number is already covered in
| the "Other Ways To Reach Us" grid below). The form photo itself was
| swapped the same day to ops-18.jpg — a new client photo supplied
| directly for this spot (copied in from client_assets/pic/contact_image.jpeg,
| which stays untouched per the read-only rule below).
*/

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Contact Us | ' . $settings['company_name'];
$pageDesc  = 'Get in touch with Carriage Global for project freight forwarding, heavy lift chartering and air freight enquiries. Singapore and Johor Bahru offices.';
$pageCanon = 'contact.php';
$bodyClass = 'page-contact';

$mapsQuery = rawurlencode($settings['address']);
$mapsDirectionsUrl = 'https://www.google.com/maps/search/?api=1&query=' . $mapsQuery;

$myMapsQuery = rawurlencode($settings['my_address']);
$myMapsDirectionsUrl = 'https://www.google.com/maps/search/?api=1&query=' . $myMapsQuery;

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>

<main id="main-content">

  <!-- 1 ── BANNER ─────────────────────────────────────────────
       Same .cgs-page-banner pattern as resources.php: photo + navy scrim,
       breadcrumb + title only, kept consistent across every inner page. -->
  <section class="cgs-page-banner">
    <div class="cgs-page-banner__media">
      <img src="<?php echo url('assets/img/gallery/ops-10.jpg'); ?>"
           width="1600" height="1200" alt=""
           fetchpriority="high">
    </div>
    <div class="container-fluid px-4">
      <nav class="cgs-page-banner__crumbs" aria-label="Breadcrumb">
        <ol>
          <li><a href="<?php echo url('index.php'); ?>">Home</a></li>
          <li aria-current="page">Contact Us</li>
        </ol>
      </nav>
      <h1>Contact Us</h1>
    </div>
  </section>

  <!-- 2 ── ENQUIRY FORM ───────────────────────────────────────
       Asymmetric 5fr/7fr split: a real ops photo left, the form right. -->
  <section class="cgs-section" id="contact-form" aria-labelledby="contact-form-heading">
    <div class="container-fluid px-4">
      <div class="row">
        <div class="col-12">
          <div class="cgs-reach">

            <div class="cgs-reach__media" data-reveal>
              <div class="cgs-reach__photo">
                <img src="<?php echo url('assets/img/gallery/ops-18.jpg'); ?>"
                     width="2048" height="1536" loading="lazy"
                     alt="Ship crane lowering large pipe sections onto an SPMT trailer at a project cargo berth">
              </div>
            </div>

            <div class="cgs-reach__form">
              <div class="cgs-form__head">
                <p class="cgs-eyebrow"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Get In Touch</p>
                <h2 id="contact-form-heading">Send us your shipment details</h2>
                <p>Tell us what needs to move, from where and to where. A project
                   manager will size up the job and come back to you, usually
                   within one business day.</p>
              </div>

              <?php if ($leadError): ?>
              <div class="cgs-form__alert" role="alert">
                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                <span>
                  <?php if ($leadError === 'invalid'): ?>
                    Please fill in your name, email, phone and a short message before sending.
                  <?php elseif ($leadError === 'email'): ?>
                    That email address does not look right. Please check it and try again.
                  <?php else: ?>
                    Something went wrong sending your enquiry. Please try again, or call us directly.
                  <?php endif; ?>
                </span>
              </div>
              <?php endif; ?>

              <form action="<?php echo url('submit-lead.php'); ?>" method="post" novalidate>
                <input type="hidden" name="source_page" value="Contact">

                <!-- Honeypot: hidden from sighted/keyboard users, real bots
                     that fill every field trip it. Checked in submit-lead.php. -->
                <div class="cgs-field cgs-field--trap" aria-hidden="true">
                  <label for="website">Website</label>
                  <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="cgs-field-grid">
                  <div class="cgs-field">
                    <label for="cf-name">Full name<span class="req">*</span></label>
                    <input type="text" id="cf-name" name="name" required autocomplete="name">
                  </div>
                  <div class="cgs-field">
                    <label for="cf-company">Company</label>
                    <input type="text" id="cf-company" name="company" autocomplete="organization">
                  </div>
                  <div class="cgs-field">
                    <label for="cf-email">Email<span class="req">*</span></label>
                    <input type="email" id="cf-email" name="email" required autocomplete="email">
                  </div>
                  <div class="cgs-field">
                    <label for="cf-phone">Phone<span class="req">*</span></label>
                    <input type="tel" id="cf-phone" name="phone" required autocomplete="tel">
                  </div>
                  <div class="cgs-field cgs-field--full">
                    <label for="cf-service">What can we help with?</label>
                    <select id="cf-service" name="service">
                      <option value="General Enquiry" selected>General enquiry</option>
                      <?php foreach ($services as $svc): ?>
                      <option value="<?php echo e($svc['title']); ?>"><?php echo e($svc['title']); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="cgs-field cgs-field--full">
                    <label for="cf-message">Message<span class="req">*</span></label>
                    <textarea id="cf-message" name="message" required
                              placeholder="Cargo type, dimensions and weight, origin, destination, and your target dates."></textarea>
                  </div>
                </div>

                <div class="cgs-form__foot">
                  <button type="submit" class="cgs-btn cgs-btn--primary">
                    Send Enquiry <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
                  </button>
                  <span class="cgs-form__note">
                    <i class="fa-solid fa-lock" aria-hidden="true"></i>
                    Your details go straight to our team, never shared.
                  </span>
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 3 ── OFFICES + MAP ──────────────────────────────────────
       Asymmetric 7fr/5fr: real SG + MY office cards left, a map right.
       map_embed_url is unset, so the map renders as a placeholder card with
       a real "Get Directions" link built from the real address, instead of
       a fabricated embed. -->
  <section class="cgs-section cgs-section--tint cgs-offices-band" aria-labelledby="offices-heading">
    <div class="container-fluid px-4">
      <div class="row">
        <div class="col-12">
          <p class="cgs-eyebrow"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Our Offices</p>
          <h2 id="offices-heading" class="cgs-offices-band__heading">Where to find us</h2>
        </div>
      </div>
      <div class="cgs-offices" data-reveal>

        <div class="cgs-office-list">

          <div class="cgs-office-card">
            <p class="cgs-office-card__flag"><i class="fa-solid fa-flag" aria-hidden="true"></i> Singapore (Head Office)</p>
            <h3><?php echo e($settings['company_name']); ?></h3>
            <p class="cgs-office-card__reg">UEN <?php echo e($settings['uen']); ?></p>
            <ul class="cgs-office-card__meta">
              <li><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <span><?php echo e($settings['address']); ?></span></li>
              <li><i class="fa-solid fa-phone" aria-hidden="true"></i> <a href="tel:<?php echo e($phoneTel); ?>"><?php echo e($settings['phone']); ?></a></li>
              <?php if (!empty($settings['phone_247'])): ?>
              <li><i class="fa-solid fa-headset" aria-hidden="true"></i> <a href="tel:<?php echo e($phone247Tel); ?>">24/7 <?php echo e($settings['phone_247']); ?></a></li>
              <?php endif; ?>
              <?php if (!empty($settings['email'])): ?>
              <li><i class="fa-solid fa-envelope" aria-hidden="true"></i> <a href="mailto:<?php echo e($settings['email']); ?>"><?php echo e($settings['email']); ?></a></li>
              <?php endif; ?>
            </ul>
            <a href="<?php echo e($mapsDirectionsUrl); ?>" class="cgs-office-card__directions" target="_blank" rel="noopener">
              Get Directions <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
          </div>

          <?php if (!empty($settings['my_address'])): ?>
          <div class="cgs-office-card">
            <p class="cgs-office-card__flag"><i class="fa-solid fa-flag" aria-hidden="true"></i> Johor Bahru, Malaysia</p>
            <h3><?php echo e($settings['my_office_name']); ?></h3>
            <?php if (!empty($settings['my_reg_no'])): ?>
            <p class="cgs-office-card__reg">Reg. No. <?php echo e($settings['my_reg_no']); ?></p>
            <?php endif; ?>
            <ul class="cgs-office-card__meta">
              <li><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <span><?php echo e($settings['my_address']); ?></span></li>
              <?php if (!empty($settings['my_phone'])): ?>
              <li><i class="fa-solid fa-phone" aria-hidden="true"></i> <a href="tel:<?php echo e(tel_link($settings['my_phone'])); ?>"><?php echo e($settings['my_phone']); ?></a></li>
              <?php else: ?>
              <li class="cgs-office-card__soon"><i class="fa-solid fa-phone" aria-hidden="true"></i> <span>Phone line to be confirmed</span></li>
              <?php endif; ?>
              <?php if (!empty($settings['my_email'])): ?>
              <li><i class="fa-solid fa-envelope" aria-hidden="true"></i> <a href="mailto:<?php echo e($settings['my_email']); ?>"><?php echo e($settings['my_email']); ?></a></li>
              <?php else: ?>
              <li class="cgs-office-card__soon"><i class="fa-solid fa-envelope" aria-hidden="true"></i> <span>Email to be confirmed</span></li>
              <?php endif; ?>
            </ul>
            <a href="<?php echo e($myMapsDirectionsUrl); ?>" class="cgs-office-card__directions" target="_blank" rel="noopener">
              Get Directions <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
          <?php endif; ?>

        </div>

        <div class="cgs-map-stack">

          <?php if (!empty($settings['map_embed_url'])): ?>
          <div class="cgs-map-card cgs-map-card--embed">
            <p class="cgs-map-card__label">Singapore (Head Office)</p>
            <iframe src="<?php echo e($settings['map_embed_url']); ?>" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Map to <?php echo e($settings['company_name']); ?>"></iframe>
          </div>
          <?php else: ?>
          <div class="cgs-map-card">
            <div class="cgs-map-card__placeholder">
              <span class="cgs-map-card__pin"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></span>
              <p><strong>Map embed on the way.</strong><br>
                 Use "Get Directions" on the Singapore office card for
                 turn-by-turn directions in the meantime.</p>
            </div>
          </div>
          <?php endif; ?>

          <?php if (!empty($settings['my_address'])): ?>
            <?php if (!empty($settings['my_map_embed_url'])): ?>
            <div class="cgs-map-card cgs-map-card--embed">
              <p class="cgs-map-card__label">Johor Bahru, Malaysia</p>
              <iframe src="<?php echo e($settings['my_map_embed_url']); ?>" loading="lazy"
                      referrerpolicy="no-referrer-when-downgrade"
                      title="Map to <?php echo e($settings['my_office_name']); ?>"></iframe>
            </div>
            <?php else: ?>
            <div class="cgs-map-card">
              <div class="cgs-map-card__placeholder">
                <span class="cgs-map-card__pin"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></span>
                <p><strong>Map embed on the way.</strong><br>
                   Use "Get Directions" on the Johor Bahru office card for
                   turn-by-turn directions in the meantime.</p>
              </div>
            </div>
            <?php endif; ?>
          <?php endif; ?>

        </div>

      </div>
    </div>
  </section>

  <!-- 4 ── OTHER WAYS TO REACH US ─────────────────────────────
       Hairline 1px-gap grid, the same technique as the homepage's Project
       Desk directory, built for contact channels instead of named people.
       WhatsApp has no number configured yet, so its tile is a visibly
       marked "coming soon" cell rather than a dead wa.me link. -->
  <section class="cgs-section cgs-touch" aria-labelledby="touch-heading">
    <div class="container-fluid px-4">
      <p class="cgs-eyebrow"><i class="fa-solid fa-comments" aria-hidden="true"></i> Other Ways To Reach Us</p>
      <h2 id="touch-heading" class="cgs-touch__heading">However you'd rather talk</h2>

      <div class="cgs-touch__grid" data-reveal>

        <div class="cgs-touch__item">
          <span class="cgs-touch__icon"><i class="fa-solid fa-phone" aria-hidden="true"></i></span>
          <span class="cgs-touch__label">Call Us</span>
          <a href="tel:<?php echo e($phoneTel); ?>" class="cgs-touch__value"><?php echo e($settings['phone']); ?></a>
        </div>

        <?php if (!empty($settings['phone_247'])): ?>
        <div class="cgs-touch__item">
          <span class="cgs-touch__icon"><i class="fa-solid fa-headset" aria-hidden="true"></i></span>
          <span class="cgs-touch__label">24/7 Support</span>
          <a href="tel:<?php echo e($phone247Tel); ?>" class="cgs-touch__value"><?php echo e($settings['phone_247']); ?></a>
        </div>
        <?php endif; ?>

        <div class="cgs-touch__item">
          <span class="cgs-touch__icon"><i class="fa-solid fa-envelope" aria-hidden="true"></i></span>
          <span class="cgs-touch__label">Email</span>
          <a href="mailto:<?php echo e($settings['email']); ?>" class="cgs-touch__value"><?php echo e($settings['email']); ?></a>
        </div>

        <?php if ($whatsappLink): ?>
        <div class="cgs-touch__item">
          <span class="cgs-touch__icon"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></span>
          <span class="cgs-touch__label">WhatsApp</span>
          <a href="<?php echo e($whatsappLink); ?>" class="cgs-touch__value" target="_blank" rel="noopener">Message Us</a>
        </div>
        <?php else: ?>
        <div class="cgs-touch__item cgs-touch__item--muted">
          <span class="cgs-touch__icon"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></span>
          <span class="cgs-touch__label">WhatsApp</span>
          <span class="cgs-touch__soon">Number coming soon</span>
        </div>
        <?php endif; ?>

        <?php if ($activeSocials): ?>
        <div class="cgs-touch__item">
          <span class="cgs-touch__icon"><i class="fa-solid fa-share-nodes" aria-hidden="true"></i></span>
          <span class="cgs-touch__label">Follow Us</span>
          <div class="cgs-touch__socials">
            <?php foreach ($activeSocials as $col => $meta): ?>
            <a href="<?php echo e($settings[$col]); ?>" target="_blank" rel="noopener" aria-label="<?php echo e($meta[1]); ?>">
              <i class="<?php echo e($meta[0]); ?>" aria-hidden="true"></i>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </section>

</main>

<?php
require __DIR__ . '/includes/footer.php';
require __DIR__ . '/includes/scripts.php';

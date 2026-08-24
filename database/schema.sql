-- =============================================================================
-- Carriage Global (S) Pte Ltd — CMS schema
-- MySQL / MariaDB 10.4+  ·  utf8mb4
--
-- Import order matters only for the seed INSERTs at the bottom.
-- Re-runnable: every CREATE uses IF NOT EXISTS, seeds use INSERT IGNORE.
-- =============================================================================

SET NAMES utf8mb4;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- -----------------------------------------------------------------------------
-- users — admin logins (email + password, then emailed OTP as second factor)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `full_name`  VARCHAR(255) NOT NULL,
  `email`      VARCHAR(255) NOT NULL,
  `password`   VARCHAR(255) NOT NULL,          -- password_hash(), PASSWORD_DEFAULT
  `role`       VARCHAR(50)  DEFAULT 'Admin',   -- 'Super Admin' | 'Admin' | 'Editor'
  `status`     VARCHAR(50)  DEFAULT 'Active',
  `otp_code`   VARCHAR(10)  DEFAULT NULL,
  `otp_expiry` DATETIME     DEFAULT NULL,
  `last_login` DATETIME     DEFAULT NULL,
  `created_at` TIMESTAMP    NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- settings — one row. Everything global: branding, both offices, socials.
-- Read once per request in includes/bootstrap.php as $settings.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `id`               INT(11) NOT NULL AUTO_INCREMENT,

  -- branding
  `logo`             VARCHAR(255) DEFAULT 'assets/img/logo/cgs-mark.png',
  `logo_light`       VARCHAR(255) DEFAULT NULL,   -- white/knockout version for the dark footer
  `favicon`          VARCHAR(255) DEFAULT 'assets/img/logo/cgs-favicon.jpg',

  -- homepage hero + video band
  `hero_bg_image`    VARCHAR(255) DEFAULT 'assets/img/bg/hero-bg.jpg',
  `hero_heading`     VARCHAR(255) DEFAULT 'Integrated Project Logistics, Engineered End to End',
  `hero_subheading`  TEXT         DEFAULT NULL,
  `video_band_src`   VARCHAR(255) DEFAULT 'assets/video/hero_video.mp4', -- also plays as the Hero's own background video (index.php) since the client repurposed this section's footage there
  `video_band_poster` VARCHAR(255) DEFAULT NULL,
  `hero_bg_video`    VARCHAR(255) DEFAULT NULL, -- optional: plays behind the first hero slide instead of its image

  -- company identity
  `company_name`     VARCHAR(150) DEFAULT 'Carriage Global (S) Pte Ltd',
  `company_short`    VARCHAR(50)  DEFAULT 'CGS',
  `tagline`          VARCHAR(150) DEFAULT 'We make ends meet.', -- footer slogan, next to the logo/wordmark
  `uen`              VARCHAR(50)  DEFAULT '200714170K',
  `iso_statement`    VARCHAR(150) DEFAULT 'An ISO 9001:2015 Certified Company',
  `years_experience` INT(11)      DEFAULT 18,
  `about_summary`    TEXT         DEFAULT NULL,

  -- Singapore office (primary). Main number corrected 2026-08-22 (client:
  -- "Contact number at the left hand side should be +65 6515 6106") — swapped
  -- with what was the 24/7 line, rather than duplicating one number in both
  -- fields.
  `phone`            VARCHAR(50)  DEFAULT '+65 6515 6106',
  `phone_247`        VARCHAR(50)  DEFAULT '+65 6899 8251',
  `fax`              VARCHAR(50)  DEFAULT '+65 6472 5443',
  `whatsapp_number`  VARCHAR(50)  DEFAULT NULL,
  `email`            VARCHAR(150) DEFAULT 'admin@carriageglobal.com',
  `address`          VARCHAR(255) DEFAULT '21 Bukit Batok Crescent, WCEGA Tower #17-82, Singapore 658065',
  `map_embed_url`    TEXT         DEFAULT NULL,

  -- Malaysia office (secondary)
  `my_office_name`   VARCHAR(150) DEFAULT 'Carriage Global (M) Sdn Bhd',
  `my_reg_no`        VARCHAR(50)  DEFAULT '1236698-V',
  `my_address`       VARCHAR(255) DEFAULT 'Suite 28.02, 28th Floor, Menara Zurich No.15, Jalan Dato Abdullah Tahir, Johor Bahru, Johor',
  `my_phone`         VARCHAR(50)  DEFAULT NULL,
  `my_email`         VARCHAR(150) DEFAULT NULL,
  `my_map_embed_url` TEXT         DEFAULT NULL,

  -- socials (rendered only when non-empty). facebook/linkedin/youtube are
  -- placeholder '#' links as of 2026-08-22 (client asked for the icons to
  -- link out to CGS's channels but hasn't sent the actual page URLs yet —
  -- see docs/PROJECT-BRIEF.md open questions) — swap for the real URLs the
  -- moment the client sends them; instagram/twitter stay unset since the
  -- client never asked for those.
  `facebook_url`     VARCHAR(255) DEFAULT '#',
  `instagram_url`    VARCHAR(255) DEFAULT NULL,
  `linkedin_url`     VARCHAR(255) DEFAULT '#',
  `twitter_url`      VARCHAR(255) DEFAULT NULL,
  `youtube_url`      VARCHAR(255) DEFAULT '#',

  -- lead routing
  `lead_notify_email` VARCHAR(150) DEFAULT NULL, -- falls back to `email` when NULL

  `updated_at`       TIMESTAMP NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- hero_slides — the homepage hero carousel. One row per slide, so the client
-- can reorder, retire or add slides from the admin without a developer.
-- If the table is empty the hero falls back to the single `settings` hero,
-- which keeps the homepage working during setup.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `hero_slides` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `eyebrow`     VARCHAR(150) DEFAULT NULL,
  `heading`     VARCHAR(255) NOT NULL,
  `subheading`  TEXT         DEFAULT NULL,
  `image`       VARCHAR(255) NOT NULL,
  `alt_text`    VARCHAR(255) DEFAULT NULL,
  `cta_label`   VARCHAR(100) DEFAULT NULL,
  `cta_link`    VARCHAR(255) DEFAULT NULL,
  `sort_order`  INT(11)      DEFAULT 0,
  `status`      ENUM('active','inactive') DEFAULT 'active',
  `created_at`  TIMESTAMP NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `status_sort` (`status`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- services — the 5 service detail pages. Drives the Services dropdown,
-- the homepage service grid, the footer service list and services.php.
-- `link` is a flat root-level filename, matching how the pages are deployed.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `services` (
  `id`           INT(11) NOT NULL AUTO_INCREMENT,
  `title`        VARCHAR(150) NOT NULL,
  `slug`         VARCHAR(150) NOT NULL,
  `link`         VARCHAR(255) NOT NULL,
  `short_desc`   TEXT         DEFAULT NULL,   -- card blurb on home/services
  `intro`        TEXT         DEFAULT NULL,   -- lead paragraph on the detail page
  `image`        VARCHAR(255) DEFAULT NULL,   -- card / hero image
  `cta_image`    VARCHAR(255) DEFAULT NULL,   -- home bento "Read more" cell background; falls back to `image` when empty
  `icon`         VARCHAR(255) DEFAULT NULL,   -- svg in assets/img/icons
  `alt_text`     VARCHAR(255) DEFAULT NULL,
  `meta_title`   VARCHAR(255) DEFAULT NULL,
  `meta_desc`    VARCHAR(320) DEFAULT NULL,
  `sort_order`   INT(11)      DEFAULT 0,
  `status`       ENUM('active','inactive') DEFAULT 'active',
  `created_at`   TIMESTAMP NULL DEFAULT current_timestamp(),
  `updated_at`   TIMESTAMP NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- service_sections — repeatable content blocks inside one service page, so the
-- client can restructure a page from the admin without a developer.
-- `layout` picks the front-end template for the block.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `service_sections` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `service_id` INT(11) NOT NULL,
  `heading`    VARCHAR(255) DEFAULT NULL,
  `body`       LONGTEXT     DEFAULT NULL,      -- HTML from the admin rich-text editor
  `image`      VARCHAR(255) DEFAULT NULL,
  `layout`     ENUM('text','text-image','image-text','list','cards','table') DEFAULT 'text',
  `sort_order` INT(11)      DEFAULT 0,
  `status`     ENUM('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `fk_service_sections_service`
    FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- fleet_items — the "Our Fleet" page. `category` splits the page into its three
-- client-specified blocks: the fleet itself, in-house lashing, and the open yard.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `fleet_items` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `category`    ENUM('Fleet','Lashing','Open Yard') NOT NULL DEFAULT 'Fleet',
  `title`       VARCHAR(200) NOT NULL,
  `description` TEXT         DEFAULT NULL,
  `specs`       TEXT         DEFAULT NULL,     -- one "Label: value" per line
  `quantity`    VARCHAR(50)  DEFAULT NULL,     -- e.g. "6 units"
  `image`       VARCHAR(255) DEFAULT NULL,
  `sort_order`  INT(11)      DEFAULT 0,
  `status`      ENUM('active','inactive') DEFAULT 'active',
  `created_at`  TIMESTAMP NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- resources — the Resources page. One row per topic; the page renders them
-- grouped by `category` as an accordion, each with its own #slug anchor.
-- `file_path` is set when the topic is (or ships with) a downloadable PDF.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `resources` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `category`   VARCHAR(100) NOT NULL,          -- see seeds: Cargo Measurement, Incoterms, Insurance, Certificates, Terms & Conditions
  `title`      VARCHAR(255) NOT NULL,
  `slug`       VARCHAR(255) NOT NULL,
  `summary`    TEXT         DEFAULT NULL,
  `content`    LONGTEXT     DEFAULT NULL,      -- HTML from the admin rich-text editor
  `image`      VARCHAR(255) DEFAULT NULL,
  `file_path`  VARCHAR(255) DEFAULT NULL,      -- downloadable PDF in uploads/
  `sort_order` INT(11)      DEFAULT 0,
  `status`     ENUM('active','inactive') DEFAULT 'active',
  `updated_at` TIMESTAMP NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- certificates — ISO 9001:2015 and any other accreditations. Shown on the
-- Resources page ("Our Certificates") and as a trust strip on the homepage.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificates` (
  `id`             INT(11) NOT NULL AUTO_INCREMENT,
  `title`          VARCHAR(200) NOT NULL,
  `issuer`         VARCHAR(200) DEFAULT NULL,
  `cert_no`        VARCHAR(100) DEFAULT NULL,
  `valid_until`    DATE         DEFAULT NULL,
  `summary`        TEXT         DEFAULT NULL,     -- what the certificate actually covers, read off the real document
  `image`          VARCHAR(255) DEFAULT NULL,     -- small round seal/badge (homepage credentials strip)
  `preview_image`  VARCHAR(255) DEFAULT NULL,     -- full certificate scan (Resources page certificate cards)
  `file_path`      VARCHAR(255) DEFAULT NULL,     -- full PDF
  `sort_order`     INT(11)      DEFAULT 0,
  `status`         ENUM('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- gallery — operations photography. Tagged by category so a service page can
-- pull only its own shots, and the homepage can pull a mixed selection.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `gallery` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255) DEFAULT NULL,
  `category`    VARCHAR(100) DEFAULT 'General',
  `service_id`  INT(11)      DEFAULT NULL,     -- optional: pin a photo to one service page
  `image_path`  VARCHAR(255) NOT NULL,
  `alt_text`    VARCHAR(255) DEFAULT NULL,
  `featured`    TINYINT(1)   DEFAULT 0,        -- 1 = eligible for the homepage strip
  `sort_order`  INT(11)      DEFAULT 0,
  `status`      ENUM('active','inactive') DEFAULT 'active',
  `uploaded_at` TIMESTAMP NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- page_blocks — editable copy for the fixed pages (home, our-fleet, contact…)
-- that isn't worth its own table. Looked up by (page_key, block_key).
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `page_blocks` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `page_key`    VARCHAR(60)  NOT NULL,         -- 'home' | 'our-fleet' | 'services' | 'resources' | 'contact'
  `block_key`   VARCHAR(60)  NOT NULL,         -- 'intro' | 'video' | 'why-us' | 'cta' …
  `eyebrow`     VARCHAR(150) DEFAULT NULL,
  `heading`     VARCHAR(255) DEFAULT NULL,
  `subheading`  VARCHAR(255) DEFAULT NULL,
  `body`        LONGTEXT     DEFAULT NULL,
  `image`       VARCHAR(255) DEFAULT NULL,
  `cta_label`   VARCHAR(100) DEFAULT NULL,
  `cta_link`    VARCHAR(255) DEFAULT NULL,
  `sort_order`  INT(11)      DEFAULT 0,
  `status`      ENUM('active','inactive') DEFAULT 'active',
  `updated_at`  TIMESTAMP NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_block` (`page_key`, `block_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- faqs — optional accordion, reusable on home / services / a service page.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `faqs` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `question`   VARCHAR(255) NOT NULL,
  `answer`     TEXT NOT NULL,
  `page_key`   VARCHAR(60) DEFAULT 'home',
  `service_id` INT(11)     DEFAULT NULL,
  `sort_order` INT(11)     DEFAULT 0,
  `status`     ENUM('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `page_key` (`page_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- -----------------------------------------------------------------------------
-- leads — every enquiry form on the site posts here via submit-lead.php.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leads` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(150) NOT NULL,
  `company`     VARCHAR(150) DEFAULT NULL,
  `email`       VARCHAR(150) NOT NULL,
  `phone`       VARCHAR(30)  NOT NULL,
  `service`     VARCHAR(150) DEFAULT NULL,     -- which service the enquiry is about
  `message`     TEXT         DEFAULT NULL,
  `source_page` VARCHAR(100) DEFAULT 'Website',
  `ip_address`  VARCHAR(45)  DEFAULT NULL,
  `status`      ENUM('New','Contacted','Quoted','Converted','Closed') DEFAULT 'New',
  `notes`       TEXT         DEFAULT NULL,     -- internal follow-up notes
  `created_at`  TIMESTAMP NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


-- =============================================================================
-- SEED DATA
-- Contact details below are transcribed from the old site
-- (https://zvv.cra.mybluehost.me/) and must be confirmed with the client.
-- =============================================================================

INSERT IGNORE INTO `settings` (`id`) VALUES (1);

-- Hero slides. Headings and subheadings are PLACEHOLDER: the client has
-- supplied no homepage copy. The capability each slide describes is real.
INSERT IGNORE INTO `hero_slides`
  (`id`, `eyebrow`, `heading`, `subheading`, `image`, `alt_text`, `cta_label`, `cta_link`, `sort_order`, `status`) VALUES
(1, 'Project cargo, heavy lift, break bulk',
    'Cargo that does not fit a container, moved anyway',
    'Heavy lift, break bulk and project cargo by sea, air and road, planned from your packing list.',
    'assets/img/bg/hero-bg.jpg',
    'Project cargo lifted onto a barge alongside a geared vessel in Singapore',
    'Get a Quote', 'contact.php', 1, 'active'),
(2, 'Chartering',
    'Self-geared and semi-geared vessels',
    'Feeder ships, barges, landing craft and deep-sea mother vessels, matched to weight, volume and route.',
    'assets/img/services/heavy-lift-chartering.jpg',
    'Crane lifting a large fabricated module at a Singapore port',
    'Get a Quote', 'contact.php', 2, 'active'),
(3, 'Our fleet',
    'Our own trailers, our own lashing crew',
    'Fleet, in-house lashing and an open yard for storage and re-working, under one operation.',
    'assets/img/services/project-freight-forwarding.jpg',
    'Oversized vessel section secured on a Carriage Global low-bed trailer',
    -- Hidden 2026-08-19 (client: "hide the Our own trailers section") — kept
    -- as a row, not deleted, so it can be reactivated from the admin later.
    'Get a Quote', 'contact.php', 3, 'inactive');

-- Titles are the exact menu wording from the client's 2026-08-22 reply
-- (emails.txt, point 7: "under this, services category sub category will
-- be 1) project freight forwarding 2) chartering heavy lift and semi
-- geared vessels 3) chartering of tug and barges 4) roll on and off
-- (ro-ro) 5) air freight") — slugs/filenames are unchanged, only the
-- label shown in the nav dropdown, footer and homepage Services section.
--
-- Air Freight set to 'inactive' 2026-08-24 (client, "HOME PAGE and VIDEO
-- REVIESD 1" thread: the "finalized service list for menu" Angeline
-- confirmed that day lists only 4 services under Services, dropping Air
-- Freight — Mahesh replied "Noted"). Kept as a row and the page/copy left
-- in place rather than deleted, since the client was still forwarding Air
-- Freight page content that same morning — this reads as the service
-- coming out of the *menu*, not the content being retired. Flip back to
-- 'active' if the client confirms it should stay linked.
INSERT IGNORE INTO `services` (`id`, `title`, `slug`, `link`, `short_desc`, `icon`, `sort_order`, `status`) VALUES
(1, 'Project Freight Forwarding',                    'project-freight-forwarding', 'project-freight-forwarding.php', 'Mode selection driven by packing-list analysis — the practical, cost-effective route rather than the most expensive charter.', 'assets/img/icons/s-icons1.svg', 1, 'active'),
(2, 'Chartering Heavy Lift and Semi-Geared Vessels', 'heavy-lift-chartering', 'heavy-lift-chartering.php', 'Break bulk and project cargo on self-geared and semi-geared vessels, matched to cargo weight, volume and route.', 'assets/img/icons/s-icons2.svg', 2, 'active'),
(3, 'Chartering of Tug and Barges',                  'tug-and-barge-chartering',   'tug-and-barge-chartering.php', 'Shallow-draft and remote-site delivery where deep-water port infrastructure is not available.', 'assets/img/icons/s-icons3.svg', 3, 'active'),
(4, 'Roll On and Off (Ro-Ro)',                       'roll-on-roll-off',           'roll-on-roll-off.php', 'Mafi trailers and ramp operations for rolling stock and awkward breakbulk units.', 'assets/img/icons/s-icons4.svg', 4, 'active'),
(5, 'Air Freight',                                   'air-freight',                'air-freight.php', 'Optimized cargo packing and multi-modal routing to major air hubs, instead of booking a costly full charter.', 'assets/img/icons/s-icons5.svg', 5, 'inactive');

-- Resources page seeds. category groups the topics into the four sections
-- resources.php actually renders (see docs/CONTENT.md's Resources page
-- section for the full sourcing story per row): Cargo Measurement (CBM/
-- freight ton, client-verbatim), Shipping Essentials (the client's own
-- "big tabs" instruction — Incoterms, Insurance, Chargeable Weight —
-- original copy grounded in, not copied from, the reference links she
-- sent), Certificates (rendered from the `certificates` table below; this
-- row is just the section title/intro), and Terms & Conditions (the
-- General T&Cs plus three policy PDFs attached on the same email).
INSERT IGNORE INTO `resources` (`id`, `category`, `title`, `slug`, `summary`, `content`, `file_path`, `sort_order`, `status`) VALUES
(1, 'Cargo Measurement',   'CBM versus Freight Ton', 'cbm-versus-freight-ton', NULL,
 '<p>Understanding <strong>CBM (Cubic Meter)</strong> and <strong>Freight Ton</strong> is essential in international shipping and logistics, especially for breakbulk and project cargo that does not move in a standard container. These two measurements are what carriers use to calculate shipping costs, make the best use of available cargo space, and plan transportation accurately.</p><h3>What is CBM (Cubic Meter)?</h3><p>CBM is the standard unit shipping and freight companies use to measure how much space a shipment takes up. It is calculated by multiplying a shipment''s three dimensions.</p><p><strong>CBM = Length (m) &times; Width (m) &times; Height (m)</strong></p><p>For example, a crate measuring 2 m long, 1.5 m wide and 1 m high:</p><p>CBM = 2 m &times; 1.5 m &times; 1 m = <strong>3 cubic meters</strong></p><p>All dimensions must be converted to meters before calculating CBM. If your measurements are in centimeters or feet, convert them to meters first.</p><h3>Understanding Freight Ton</h3><p>A freight ton is a unit used to calculate cargo weight for shipping prices and load limits. Two common tonnage measurements apply:</p><ul><li><strong>Gross Weight Ton (Short Ton):</strong> 2,000 lbs, widely used in the United States. Gross Weight Ton = Weight (lbs) &divide; 2,000.</li><li><strong>Metric Weight Ton:</strong> 2,204.62 lbs (1,000 kg), used in most other parts of the world. Metric Weight Ton = Weight (kg) &divide; 1,000.</li></ul><p>For a shipment weighing 5,000 lbs (2,268 kg):</p><ul><li>Gross Weight Ton = 5,000 lbs &divide; 2,000 = <strong>2.5 short tons</strong></li><li>Metric Weight Ton = 2,268 kg &divide; 1,000 = <strong>2.268 metric tons</strong></li></ul><h3>How CBM and Freight Ton Work Together</h3><p>Freight Ton measures weight, while CBM measures volume, and carriers weigh both to make the best use of available space and stay within weight limits. For breakbulk cargo, the shipping cost is usually based on whichever produces the higher revenue for the carrier: the cargo''s actual weight, or its volume converted to a ton-equivalent. Knowing both figures in advance lets you plan a shipment''s cost with confidence before it reaches the port.</p>',
 NULL, 1, 'active'),
(2, 'Shipping Essentials',  'Incoterms Explained', 'incoterms', NULL,
 '<p>Incoterms (International Commercial Terms) are a set of eleven standardized trade terms, published by the International Chamber of Commerce and last updated in 2020, that define exactly where the seller''s responsibility for cost, risk and delivery ends and the buyer''s begins. For project cargo and breakbulk shipments, where freight can represent a large share of total contract value, choosing the wrong Incoterm can quietly shift thousands of dollars of cost, or an entire cargo insurance liability, onto the wrong party.</p><p>Seven of the eleven rules apply to any mode of transport, including multimodal moves. The remaining four are written specifically for sea and inland waterway transport, where risk transfers at a named port rather than at a warehouse or terminal.</p><table><caption>Incoterms 2020</caption><thead><tr><th>Term</th><th>Full name</th><th>Risk transfers</th></tr></thead><tbody><tr><td>EXW</td><td>Ex Works</td><td>At the seller''s premises, before loading</td></tr><tr><td>FCA</td><td>Free Carrier</td><td>Once goods are handed to the buyer''s carrier</td></tr><tr><td>CPT</td><td>Carriage Paid To</td><td>At the first carrier, though seller pays freight to destination</td></tr><tr><td>CIP</td><td>Carriage and Insurance Paid To</td><td>At the first carrier; seller also insures to destination</td></tr><tr><td>DAP</td><td>Delivered at Place</td><td>On arrival, ready for unloading</td></tr><tr><td>DPU</td><td>Delivered at Place Unloaded</td><td>On arrival, after unloading</td></tr><tr><td>DDP</td><td>Delivered Duty Paid</td><td>On arrival, duty and taxes cleared by seller</td></tr><tr><td>FAS</td><td>Free Alongside Ship</td><td>Once goods are placed alongside the vessel</td></tr><tr><td>FOB</td><td>Free on Board</td><td>Once goods are loaded onto the vessel</td></tr><tr><td>CFR</td><td>Cost and Freight</td><td>Once loaded, though seller pays freight to destination port</td></tr><tr><td>CIF</td><td>Cost, Insurance and Freight</td><td>Once loaded; seller also insures to destination port</td></tr></tbody></table><p>For heavy lift and project cargo specifically, FCA or CPT are usually a safer starting point than FOB or CIF: they hand off risk once the cargo is with a nominated carrier rather than leaving ambiguity around exactly when it crosses a vessel''s rail. Confirm the Incoterm on your quote before booking, since it decides who arranges transport insurance and who absorbs a delay at origin.</p>',
 NULL, 2, 'active'),
(3, 'Shipping Essentials',  'Freight Service Liability Insurance versus Cargo Insurance', 'liability-versus-cargo-insurance', NULL,
 '<p>These two covers are often confused, but they protect different things and different parties. <strong>Freight service liability insurance</strong> covers the carrier or freight forwarder''s own, legally limited liability for cargo lost or damaged while in their care. That liability is capped, often by weight or by a fixed amount per package, under international conventions such as the Hague-Visby Rules for sea freight or the CMR Convention for road freight, not by the cargo''s actual value.</p><p><strong>Cargo insurance</strong> (also called marine cargo insurance) is a separate policy the cargo owner takes out to cover the full declared value of the goods against loss or damage in transit, including risks the carrier is not liable for at all, such as piracy, general average, or damage caused by circumstances outside the carrier''s control.</p><p>For high-value or project cargo, relying on a carrier''s liability cover alone almost always leaves a gap between what the cargo is worth and what the carrier is obligated to pay out. Arranging your own cargo insurance closes that gap.</p>',
 NULL, 3, 'active'),
(6, 'Shipping Essentials',  'Chargeable Weight Calculation', 'chargeable-weight-calculation', NULL,
 '<p>International air freight is charged on whichever is greater: the shipment''s actual gross weight, or its volumetric (dimensional) weight. Air cargo space is limited and priced by volume as much as by weight, so a large, light shipment can cost more to fly than its scale suggests once volumetric weight is applied.</p><p>The standard international air freight formula is:</p><p><strong>Volumetric Weight (kg) = (Length &times; Width &times; Height, in cm) &divide; 6,000</strong></p><p>For example, a crate measuring 120 cm &times; 80 cm &times; 100 cm with an actual weight of 150 kg:</p><p>Volumetric weight = (120 &times; 80 &times; 100) &divide; 6,000 = 960,000 &divide; 6,000 = <strong>160 kg</strong></p><p>Since 160 kg is greater than the actual 150 kg, the shipment is charged at <strong>160 kg</strong>, its volumetric weight, not its actual weight.</p><p>This calculation applies specifically to international air freight. Ocean and breakbulk cargo is measured differently, using CBM and freight ton (see Cargo Measurement above).</p>',
 NULL, 4, 'active'),
(4, 'Certificates',        'Our Certificates', 'our-certificates',
 'View and download our current ISO 9001:2015, bizSAFE Level 4 and WCA Project Network certificates below.',
 NULL, NULL, 5, 'active'),
(5, 'Terms & Conditions',  'CGS General Terms and Conditions', 'general-terms-and-conditions',
 'The standard terms and conditions governing our freight and logistics services.',
 NULL, 'assets/documents/cgs-terms-and-conditions.pdf', 6, 'active'),
(7, 'Terms & Conditions',  'Code of Conduct', 'code-of-conduct',
 'Our standards for ethical, professional conduct in daily operations.',
 NULL, 'assets/documents/cgs-code-of-conduct.pdf', 7, 'active'),
(8, 'Terms & Conditions',  'Alcohol and Drug Policy', 'alcohol-and-drug-policy',
 'Our workplace policy on alcohol and drug use.',
 NULL, 'assets/documents/cgs-alcohol-and-drug-policy.pdf', 8, 'active'),
(9, 'Terms & Conditions',  'Environmental Policy Statement', 'environmental-policy-statement',
 'Our commitment to environmentally responsible operations.',
 NULL, 'assets/documents/cgs-environmental-policy-statement.pdf', 9, 'active');

-- Issuer, cert_no, valid_until and summary are read directly off the real
-- certificate scans (assets/img/certificates/iso9001.jpg, bizsafe.jpg,
-- wca-project-membership.jpg — the full documents, not the small seal
-- artwork), so the Resources page can show more than a logo (2026-08-24
-- client feedback: "I want certifications to be shown not just logo").
-- `image` stays the small round seal used on the homepage credentials
-- strip; `preview_image` is the full scan the Resources page cards use.
INSERT IGNORE INTO `certificates` (`id`, `title`, `issuer`, `cert_no`, `valid_until`, `summary`, `image`, `preview_image`, `file_path`, `sort_order`, `status`) VALUES
(1, 'ISO 9001:2015', 'United Registrar of Systems (URS)', '123915/A/0001/UK/En', '2028-09-11',
 'Quality Management System certification, covering project freight forwarding, chartering of heavy lift and semi-geared vessels, tug and barge, and cross-border trucking for general and oversize cargo, including local transportation and lifting services.',
 'assets/img/certificates/badge-iso9001.jpg', 'assets/img/certificates/iso9001.jpg', 'assets/certificates/iso-9001-2015.pdf', 1, 'active'),
(2, 'bizSAFE Level 4', 'Workplace Safety and Health Council', 'E12697', '2028-07-30',
 'Confirms a company-wide workplace safety and health management system verified to bizSAFE Level 4, the risk management tier for organizations managing higher-risk operations.',
 'assets/img/certificates/badge-bizsafe.jpg', 'assets/img/certificates/bizsafe.jpg', 'assets/certificates/bizsafe-4.pdf', 2, 'active'),
(3, 'WCA Project, 15 yrs', 'WCA World (WCA Projects)', NULL, '2026-02-09',
 'Membership in the WCA Projects network, connecting Carriage Global with vetted project-cargo partners worldwide for coordinated heavy lift and break bulk moves.',
 'assets/img/certificates/badge-wca.png', 'assets/img/certificates/wca-project-membership.jpg', 'assets/certificates/wca-project-membership.pdf', 3, 'active');

INSERT IGNORE INTO `page_blocks` (`page_key`, `block_key`, `heading`, `sort_order`) VALUES
('home',      'video',     'See How We Move Project Cargo',       1),
('home',      'intro',     'Integrated Customized Logistics',     2),
('home',      'why-us',    'Why Carriage Global',                 3),
('home',      'cta',       'Send Us Your Packing List',           4),
('our-fleet', 'intro',     'Our Fleet',                           1),
('our-fleet', 'lashing',   'In-House Lashing',                    2),
('our-fleet', 'open-yard', 'Open Yard for Cargo Storage & Re-Working', 3);

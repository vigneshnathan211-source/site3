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
  -- client-supplied 2026-08-26 ("W/app number as per blank write +65 91700300")
  `whatsapp_number`  VARCHAR(50)  DEFAULT '+65 9170 0300',
  `email`            VARCHAR(150) DEFAULT 'admin@carriageglobal.com',
  `address`          VARCHAR(255) DEFAULT '21 Bukit Batok Crescent, WCEGA Tower #17-82, Singapore 658065',
  `map_embed_url`    TEXT         DEFAULT NULL,

  -- Malaysia office (secondary)
  `my_office_name`   VARCHAR(150) DEFAULT 'Carriage Global (M) Sdn Bhd',
  `my_reg_no`        VARCHAR(50)  DEFAULT '1236698-V',
  `my_address`       VARCHAR(255) DEFAULT 'Suite 28.02, 28th Floor, Menara Zurich No.15, Jalan Dato Abdullah Tahir, Johor Bahru, Johor',
  -- client, 2026-08-26: "Do not write phone number" for the Malaysia office —
  -- stays NULL on purpose (contact.php omits the phone line entirely when
  -- unset, rather than showing a "to be confirmed" placeholder).
  `my_phone`         VARCHAR(50)  DEFAULT NULL,
  -- client-supplied 2026-08-26 ("Malaysia email write:- ops@carriageglobal.com")
  `my_email`         VARCHAR(150) DEFAULT 'ops@carriageglobal.com',
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
-- fleet_items — the "Our Fleet" page. `category` splits the page into the four
-- blocks the client's corrected copy actually uses (Angeline Tilokani,
-- "Our Fleet.docx", 25 Aug 2026, superseding an earlier plain-text draft —
-- see our-fleet.php's header comment): the equipment itself, port and
-- terminal operations, regional transhipment, and in-house lashing. The open
-- yard (14 Penjuru Road) is one Fleet item in that copy, not its own
-- category, which folds the earlier 'Open Yard' enum value into 'Fleet'.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `fleet_items` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `category`    ENUM('Fleet','Port & Terminal','Transhipment','Lashing') NOT NULL DEFAULT 'Fleet',
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
 '<p>Incoterms (International Commercial Terms) are a set of eleven standardized trade terms, published by the International Chamber of Commerce and last updated in 2020, that define exactly where the seller''s responsibility for cost, risk and delivery ends and the buyer''s begins. For project cargo and breakbulk shipments, where freight can represent a large share of total contract value, choosing the wrong Incoterm can quietly shift thousands of dollars of cost, or an entire cargo insurance liability, onto the wrong party.</p><p>Seven of the eleven rules apply to any mode of transport, including multimodal moves. The remaining four are written specifically for sea and inland waterway transport, where risk transfers at a named port rather than at a warehouse or terminal.</p><table><caption>Incoterms 2020</caption><thead><tr><th>Term</th><th>Full name</th><th>Risk transfers</th></tr></thead><tbody><tr><td data-label="Term">EXW</td><td data-label="Full name">Ex Works</td><td data-label="Risk transfers">At the seller''s premises, before loading</td></tr><tr><td data-label="Term">FCA</td><td data-label="Full name">Free Carrier</td><td data-label="Risk transfers">Once goods are handed to the buyer''s carrier</td></tr><tr><td data-label="Term">CPT</td><td data-label="Full name">Carriage Paid To</td><td data-label="Risk transfers">At the first carrier, though seller pays freight to destination</td></tr><tr><td data-label="Term">CIP</td><td data-label="Full name">Carriage and Insurance Paid To</td><td data-label="Risk transfers">At the first carrier; seller also insures to destination</td></tr><tr><td data-label="Term">DAP</td><td data-label="Full name">Delivered at Place</td><td data-label="Risk transfers">On arrival, ready for unloading</td></tr><tr><td data-label="Term">DPU</td><td data-label="Full name">Delivered at Place Unloaded</td><td data-label="Risk transfers">On arrival, after unloading</td></tr><tr><td data-label="Term">DDP</td><td data-label="Full name">Delivered Duty Paid</td><td data-label="Risk transfers">On arrival, duty and taxes cleared by seller</td></tr><tr><td data-label="Term">FAS</td><td data-label="Full name">Free Alongside Ship</td><td data-label="Risk transfers">Once goods are placed alongside the vessel</td></tr><tr><td data-label="Term">FOB</td><td data-label="Full name">Free on Board</td><td data-label="Risk transfers">Once goods are loaded onto the vessel</td></tr><tr><td data-label="Term">CFR</td><td data-label="Full name">Cost and Freight</td><td data-label="Risk transfers">Once loaded, though seller pays freight to destination port</td></tr><tr><td data-label="Term">CIF</td><td data-label="Full name">Cost, Insurance and Freight</td><td data-label="Risk transfers">Once loaded; seller also insures to destination port</td></tr></tbody></table><p>For heavy lift and project cargo specifically, FCA or CPT are usually a safer starting point than FOB or CIF: they hand off risk once the cargo is with a nominated carrier rather than leaving ambiguity around exactly when it crosses a vessel''s rail. Confirm the Incoterm on your quote before booking, since it decides who arranges transport insurance and who absorbs a delay at origin.</p>',
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
(3, 'WCA Project, 15 yrs', 'WCA World (WCA Projects)', '71622', '2027-02-09',
 'Membership in the WCA Projects network, connecting Carriage Global with vetted project-cargo partners worldwide for coordinated heavy lift and break bulk moves.',
 'assets/img/certificates/badge-wca.png', 'assets/img/certificates/wca-project-membership.jpg', 'assets/certificates/wca-project-membership.pdf', 3, 'active');

-- Past Projects gallery. All 17 rows are the same real, distinct client
-- photography already vetted and captioned across index.php / resources.php
-- / contact.php (checksummed against each other 2026-08-24 to confirm none
-- are accidental duplicates, per the client's own "avoid repetition"
-- instruction). `category` reuses the real `services.title` strings rather
-- than an invented taxonomy, so the Past Projects filter reflects the
-- site's actual service list instead of ad-hoc labels — a photo with no
-- clear RoRo shot in the current set just means that filter pill won't
-- appear yet, not that one was invented to fill a gap.
INSERT IGNORE INTO `gallery` (`id`, `category`, `image_path`, `alt_text`, `sort_order`, `status`) VALUES
(1,  'Chartering Heavy Lift and Semi-Geared Vessels', 'assets/img/gallery/ops-01.jpg', 'Project cargo lifted aboard a geared vessel', 1, 'active'),
(2,  'Chartering Heavy Lift and Semi-Geared Vessels', 'assets/img/gallery/ops-02.jpg', 'Break bulk unit slung under a ship crane', 2, 'active'),
(3,  'Chartering Heavy Lift and Semi-Geared Vessels', 'assets/img/gallery/ops-03.jpg', 'Wrapped tank hoisted by crane onto a vessel', 3, 'active'),
(4,  'Chartering of Tug and Barges', 'assets/img/gallery/ops-04.jpg', 'Cargo transferred to a barge alongside', 4, 'active'),
(5,  'Project Freight Forwarding', 'assets/img/gallery/ops-05.jpg', 'Large cable reel secured on a Carriage Global low-bed trailer at a container terminal at night', 5, 'active'),
(6,  'Chartering of Tug and Barges', 'assets/img/gallery/ops-06.jpg', 'Barge operation in Singapore waters', 6, 'active'),
(7,  'Project Freight Forwarding', 'assets/img/gallery/ops-07.jpg', 'Carriage Global trailer loaded with a large cable reel and crated cargo at a yard', 7, 'active'),
(8,  'Chartering Heavy Lift and Semi-Geared Vessels', 'assets/img/gallery/ops-08.jpg', 'MacGregor ship crane hoisting cargo over the water at a Singapore port', 8, 'active'),
(9,  'Project Freight Forwarding', 'assets/img/gallery/ops-09.jpg', 'Green mobile crane lowering equipment onto a trailer at a Singapore port, seen from a Carriage Global vehicle', 9, 'active'),
(10, 'Chartering Heavy Lift and Semi-Geared Vessels', 'assets/img/gallery/ops-10.jpg', 'Green tarpaulin-wrapped cargo lifted by gantry crane at a Singapore container terminal', 10, 'active'),
(11, 'Chartering Heavy Lift and Semi-Geared Vessels', 'assets/img/gallery/ops-11.jpg', 'Support vessel lifted clear of the water by twin shipyard cranes', 11, 'active'),
(12, 'Chartering Heavy Lift and Semi-Geared Vessels', 'assets/img/gallery/ops-12.jpg', 'Large cylindrical pressure vessel lifted aboard a geared vessel at sea', 12, 'active'),
(13, 'Chartering Heavy Lift and Semi-Geared Vessels', 'assets/img/gallery/ops-13.jpg', 'Wrapped process vessel lowered into a ship''s cargo hold', 13, 'active'),
(14, 'Chartering Heavy Lift and Semi-Geared Vessels', 'assets/img/gallery/ops-14.jpg', 'Winch and crane equipment secured on a vessel deck alongside shipping containers', 14, 'active'),
(15, 'Chartering Heavy Lift and Semi-Geared Vessels', 'assets/img/gallery/ops-15.jpg', 'Twin deck cranes lifting cylindrical tanks aboard a vessel', 15, 'active'),
(16, 'Project Freight Forwarding', 'assets/img/gallery/ops-16.jpg', 'Large IQIP pressure vessel rigged for lifting on a Carriage Global low-bed trailer at a yard', 16, 'active'),
(17, 'Project Freight Forwarding', 'assets/img/gallery/ops-17.jpg', 'Mobile crane lowering specialized equipment onto a low-bed trailer at a Singapore port, container cranes in the background', 17, 'active');

INSERT IGNORE INTO `page_blocks` (`page_key`, `block_key`, `heading`, `subheading`, `body`, `sort_order`) VALUES
('home',      'video',        'See How We Move Project Cargo',    NULL, NULL, 1),
('home',      'intro',        'Integrated Customized Logistics',  NULL, NULL, 2),
('home',      'why-us',       'Why Carriage Global',              NULL, NULL, 3),
('home',      'cta',          'Send Us Your Packing List',        NULL, NULL, 4),
('our-fleet', 'intro',        'Our Fleet & Capabilities', NULL,
  '<p>Welcome to CGS. We own, operate, and manage a complete range of heavy transport equipment, serving as Singapore''s premier asset-based partner for global freight forwarders, MNCs, and direct cargo owners alike.</p><p>Whether you are an international freight forwarder seeking a reliable, neutral local partner or a direct client requiring specialized logistics, we manage every step of your oversize cargo journey using our own skilled crew and specialized fleet.</p>', 1),
('our-fleet', 'fleet',        'Specialized Fleet & Infrastructure',
  'We provide heavy-duty transport vehicles built for oversize, general, and heavy cargo, offering MNC forwarders the asset capacity they need to scale.', NULL, 2),
('our-fleet', 'port-terminal','Port & Terminal Operations',
  'We work directly inside Singapore''s major ports to streamline operations, lower costs, and reduce transit times for your supply chain.', NULL, 3),
('our-fleet', 'transhipment', 'Regional Transhipment Services',
  'We extend your reach across Southeast Asia, moving cargo seamlessly across borders using our own low bed fleet and dedicated manpower.', NULL, 4),
('our-fleet', 'lashing',      'In-House Lashing & Lifting', 'Certified Trust',
  '<p>Safety and neutrality are our top priorities. Our highly experienced in-house team handles all lifting and lashing directly, giving forwarders sub-contracting peace of mind.</p>', 5);

-- -----------------------------------------------------------------------------
-- fleet_items seeds — Angeline Tilokani's corrected "Our Fleet.docx"
-- (25 Aug 2026), the client's final copy for this page (see our-fleet.php's
-- header comment for the full email chain). `specs` carries the one real
-- dimension the client gave (Low Bed / Super Low Bed deck length and
-- height); nothing else is invented. Images are matched to real Carriage
-- Global operations photography where the equipment is an honest match
-- (assets/img/fleet/). Forklifts and the Open Storage Yard have no
-- dedicated photo in the asset set — a full scan of both raw client
-- WhatsApp drops (client_assets/pic/ and client_assets/latest/latest/,
-- ~34 unique images between them, 2026-08-14/20) turned up no forklift and
-- no open-air storage yard shot, so these two use the closest available
-- real CGS photography as an atmospheric stand-in rather than a literal
-- equipment match: ops-09.jpg (a MAFI terminal tractor at a Singapore
-- port apron — ground handling equipment, not a forklift) and ops-16.jpg
-- (a CGS low-bed trailer under a loading-bay roof, captioned "at a yard"
-- in the gallery table — not an open-air storage yard). Swap both for
-- real forklift/yard photos the moment the client supplies them.
-- `sort_order` here is the page's own display order (Modular Trailers
-- leads, as the widest showcase tile), not the order the docx lists them in.
--
-- 2026-08-26: Modular Trailers' photo swapped from assets/img/fleet/spmt-
-- trailer.jpg (had a hand-drawn orange circle annotation baked into the
-- image) to assets/img/gallery/ops-18.jpg — the same SPMT/ship-crane photo
-- already used on contact.php's form panel, reused here rather than
-- duplicated as a second file.
-- -----------------------------------------------------------------------------
INSERT IGNORE INTO `fleet_items` (`id`, `category`, `title`, `description`, `specs`, `image`, `sort_order`, `status`) VALUES
(1, 'Fleet', 'Skeleton Chassis', 'Reliable frames for standard container transport and specialized moves.', NULL, 'assets/img/fleet/fleet-trailer.jpg', 2, 'active'),
(2, 'Fleet', 'Low Bed & Super Low Bed Trailers', 'Heavy-capacity units engineered to clear low Singapore road height limits.', 'Length: 12m\nHeight: 0.8m', 'assets/img/fleet/tank-transport.jpg', 3, 'active'),
(3, 'Fleet', 'Modular Trailers', 'Advanced multi-axle trailers configured for massive, ultra-heavy lifts.', NULL, 'assets/img/gallery/ops-18.jpg', 1, 'active'),
(4, 'Fleet', 'Forklifts', 'A wide variety of lift trucks ready for heavy industrial loading and cross-docking.', NULL, 'assets/img/gallery/ops-09.jpg', 4, 'active'),
(5, 'Fleet', 'Open Storage Yard', 'A secure space dedicated to storing oversize cargo and performing cargo re-working at 14 Penjuru Road.', NULL, 'assets/img/gallery/ops-16.jpg', 5, 'active'),
(6, 'Port & Terminal', 'Pasir Panjang Auto Terminal', 'Direct lift of cargo from Mafi trailers straight onto low bed trailers for efficient delivery.', NULL, NULL, 1, 'active'),
(7, 'Port & Terminal', 'PSA Container Terminal', 'In-port flat rack stripping to lower overall cargo height and meet Singapore road regulations. This enables seamless transhipment within the port without the need for external trucking.', NULL, NULL, 2, 'active'),
(8, 'Port & Terminal', 'Barge Operations', 'Execution of complex roll-on/roll-off (RoRo), roll-up, and jack-down operations for barges, concrete blocks, and heavy infrastructure components.', NULL, NULL, 3, 'active'),
(9, 'Transhipment', 'The Batam Connection', 'Smooth transhipment from global origins to Batam via Singapore (and vice versa). We offer full door-to-door delivery in Batam under DAP and DDP terms, acting as a trusted extended arm for global forwarders.', NULL, NULL, 1, 'active'),
(10, 'Transhipment', 'Malaysia & Thailand Cross-Border', 'Reliable transport of oversize cargo from Singapore through West Malaysia and up to Thailand, ensuring timely offshore vessel connections.', NULL, NULL, 2, 'active'),
(11, 'Lashing', 'Trusted by major shipping lines', 'Many major global shipping lines trust our crew completely.', NULL, NULL, 1, 'active'),
(12, 'Lashing', 'Survey certificates often waived', 'Because of our strict adherence to international safety standards, liners often waive the requirement for external lashing survey certificates when CGS crews secure cargo across Singapore, Malaysia, and Batam.', NULL, NULL, 2, 'active');

-- -----------------------------------------------------------------------------
-- heavy-lift-chartering.php seeds — the client's "Breakbulk chartering -1
-- pics" email (Angeline Tilokani, 14 Aug 2026), transcribed verbatim in
-- docs/CONTENT.md ("Service page 2 — Chartering Heavy Lift & Semi-Geared
-- Vessels"). page_blocks carries the intro/statement prose and the two
-- section headings; service_sections (service_id 2, matching the
-- `services` row above) carries the two repeatable lists the same prose
-- breaks into: the seven-step "How Tailored Vessel Solutions Work"
-- sequence (`layout` = 'list') and the four discrete capabilities the
-- closing paragraph names (`layout` = 'cards' — see docs/CONTENT.md's own
-- note that this prose "lists four discrete capabilities... which is how
-- it should be laid out on the page"). Images are the client's own
-- photography from three separate "Breakbulk pics" email attachments in
-- Thunderbird (18 photos total, client_assets/email-assets/Fw_ Breakbulk
-- pics_/), copied into assets/img/services/heavy-lift/ — see
-- heavy-lift-chartering.php's header comment for which photo maps to
-- which slot and why.
-- -----------------------------------------------------------------------------
INSERT IGNORE INTO `page_blocks` (`page_key`, `block_key`, `eyebrow`, `heading`, `body`, `sort_order`) VALUES
('heavy-lift-chartering', 'intro', NULL, 'Vessel Chartering for Break Bulk & Project Cargo',
  '<p>Vessel chartering for break bulk and project cargo provides flexible, efficient transport for oversized, heavy, or irregular goods that cannot fit into standard containers. CGS specializes in custom vessel chartering to ensure complex industrial shipments reach their destinations safely, on time, and according to exact project schedules.</p>', 1),
('heavy-lift-chartering', 'statement', NULL, 'Our Vessel Chartering Services',
  '<p>Tailored vessel solutions match your cargo size with the right ship. Options range from small feeder ships to large ocean-crossing vessels, often combining barges, landing craft transport (LCT), and deep-sea mother vessels to move cargo from local ports to global destinations.</p>', 2),
('heavy-lift-chartering', 'process', 'How It Works', 'How Tailored Vessel Solutions Work', NULL, 3),
('heavy-lift-chartering', 'capabilities', 'Why Choose CGS', 'Comprehensive Cargo Handling and Marine Logistics Solutions',
  '<p>Managing heavy or specialized cargo requires an end-to-end approach that accounts for every technical and logistical hurdle from origin to destination.</p>', 4);

INSERT IGNORE INTO `service_sections` (`service_id`, `heading`, `body`, `image`, `layout`, `sort_order`, `status`) VALUES
(2, 'Cargo Assessment', '<p>Matching weight, volume, and type to the correct ship size.</p>', 'assets/img/services/heavy-lift/step-cargo-assessment.jpg', 'list', 1, 'active'),
(2, 'Barge and LCT Operations', '<p>Using shallow-draft vessels or landing craft to load cargo where large ships cannot dock.</p>', 'assets/img/services/heavy-lift/gallery-03.jpg', 'list', 2, 'active'),
(2, 'Mother Vessel Connection', '<p>Transferring goods from local barges to large deep-sea vessels for long-distance travel.</p>', 'assets/img/services/heavy-lift/step-mother-vessel.jpg', 'list', 3, 'active'),
(2, 'Port-to-Port Delivery', '<p>Managing the full route from initial commercial ports to final international destinations.</p>', 'assets/img/services/heavy-lift/gallery-04.jpg', 'list', 4, 'active'),
(2, 'Route Selection', '<p>Experts study routes and use trusted partners to ensure smooth port transit and lower costs.</p>', 'assets/img/services/heavy-lift/step-route-selection.jpg', 'list', 5, 'active'),
(2, 'Onboard Cargo Handling', '<p>Crews use specialized gear and skills to load, store, and unload fragile or massive items safely.</p>', 'assets/img/services/heavy-lift/step-onboard-handling.jpg', 'list', 6, 'active'),
(2, 'Compliance and Documentation', '<p>The team manages all global shipping rules, customs papers, and port permits.</p>', 'assets/img/services/heavy-lift/gallery-05.jpg', 'list', 7, 'active'),
(2, 'Fabrication & Sea-Worthy Packing', '<p>The process begins with the initial fabrication and custom packaging of boxes designed to withstand harsh transit conditions. Securing cargo for ocean travel demands sea-worthy packing standards that protect assets against moisture, shifting, and environmental wear during long voyages.</p>', 'assets/img/services/heavy-lift/cap-packing.jpg', 'cards', 1, 'active'),
(2, 'Lifting & Lashing Calculations', '<p>Specialists perform detailed lifting and lashing calculations to verify that weight distributions and anchor points can endure dynamic ocean forces.</p>', 'assets/img/services/heavy-lift/cap-lifting.jpg', 'cards', 2, 'active'),
(2, 'Stowage Plan & Certified Gear', '<p>Teams coordinate pre-approval stages for the stowage plan while ensuring certified lifting gears are fully available and ready for deployment.</p>', 'assets/img/services/heavy-lift/cap-stowage.jpg', 'cards', 3, 'active'),
(2, 'Contractual Review & Delivery', '<p>Coordinators review every clause and requirement alongside ship owners and charterers to eliminate operational friction. The service oversees the entire transit phase down to the final drop-off location, ensuring a secure and seamless delivery.</p>', NULL, 'cards', 4, 'active');

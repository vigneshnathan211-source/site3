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
  `logo`             VARCHAR(255) DEFAULT 'assets/img/logo/cgs-logo.jpg',
  `logo_light`       VARCHAR(255) DEFAULT NULL,   -- white/knockout version for the dark footer
  `favicon`          VARCHAR(255) DEFAULT 'assets/img/logo/cgs-favicon.jpg',

  -- homepage hero + video band
  `hero_bg_image`    VARCHAR(255) DEFAULT 'assets/img/bg/hero-bg.jpg',
  `hero_heading`     VARCHAR(255) DEFAULT 'Integrated Project Logistics, Engineered End to End',
  `hero_subheading`  TEXT         DEFAULT NULL,
  `video_band_src`   VARCHAR(255) DEFAULT 'assets/video/cgs-video-band.mp4', -- the Video Band section below the hero, not the hero itself
  `video_band_poster` VARCHAR(255) DEFAULT NULL,
  `hero_bg_video`    VARCHAR(255) DEFAULT NULL, -- optional: plays behind the first hero slide instead of its image

  -- company identity
  `company_name`     VARCHAR(150) DEFAULT 'Carriage Global (S) Pte Ltd',
  `company_short`    VARCHAR(50)  DEFAULT 'CGS',
  `uen`              VARCHAR(50)  DEFAULT '200714170K',
  `iso_statement`    VARCHAR(150) DEFAULT 'An ISO 9001:2015 Certified Company',
  `years_experience` INT(11)      DEFAULT 18,
  `about_summary`    TEXT         DEFAULT NULL,

  -- Singapore office (primary)
  `phone`            VARCHAR(50)  DEFAULT '+65 6899 8251',
  `phone_247`        VARCHAR(50)  DEFAULT '+65 6515 6106',
  `fax`              VARCHAR(50)  DEFAULT '+65 6472 5443',
  `whatsapp_number`  VARCHAR(50)  DEFAULT NULL,
  `email`            VARCHAR(150) DEFAULT 'angeline@carriageglobal.com',
  `address`          VARCHAR(255) DEFAULT '21 Bukit Batok Crescent, WCEGA Tower #17-82, Singapore 658065',
  `map_embed_url`    TEXT         DEFAULT NULL,

  -- Malaysia office (secondary)
  `my_office_name`   VARCHAR(150) DEFAULT 'Carriage Global (M) Sdn Bhd',
  `my_reg_no`        VARCHAR(50)  DEFAULT '1236698-V',
  `my_address`       VARCHAR(255) DEFAULT 'Suite 28.02, 28th Floor, Menara Zurich No.15, Jalan Dato Abdullah Tahir, Johor Bahru, Johor',
  `my_phone`         VARCHAR(50)  DEFAULT NULL,
  `my_email`         VARCHAR(150) DEFAULT NULL,
  `my_map_embed_url` TEXT         DEFAULT NULL,

  -- socials (rendered only when non-empty)
  `facebook_url`     VARCHAR(255) DEFAULT NULL,
  `instagram_url`    VARCHAR(255) DEFAULT NULL,
  `linkedin_url`     VARCHAR(255) DEFAULT NULL,
  `twitter_url`      VARCHAR(255) DEFAULT NULL,
  `youtube_url`      VARCHAR(255) DEFAULT NULL,

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
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(200) NOT NULL,
  `issuer`      VARCHAR(200) DEFAULT NULL,
  `cert_no`     VARCHAR(100) DEFAULT NULL,
  `valid_until` DATE         DEFAULT NULL,
  `image`       VARCHAR(255) DEFAULT NULL,     -- thumbnail / scan
  `file_path`   VARCHAR(255) DEFAULT NULL,     -- full PDF
  `sort_order`  INT(11)      DEFAULT 0,
  `status`      ENUM('active','inactive') DEFAULT 'active',
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
    'Get a Quote', 'contact.php', 3, 'active');

INSERT IGNORE INTO `services` (`id`, `title`, `slug`, `link`, `short_desc`, `icon`, `sort_order`, `status`) VALUES
(1, 'Project Freight Forwarding',            'project-freight-forwarding', 'project-freight-forwarding.php', 'Mode selection driven by packing-list analysis — the practical, cost-effective route rather than the most expensive charter.', 'assets/img/icons/s-icons1.svg', 1, 'active'),
(2, 'Chartering Heavy Lift & Semi-Geared Vessels', 'heavy-lift-chartering', 'heavy-lift-chartering.php', 'Break bulk and project cargo on self-geared and semi-geared vessels, matched to cargo weight, volume and route.', 'assets/img/icons/s-icons2.svg', 2, 'active'),
(3, 'Chartering Tug & Barge',                 'tug-and-barge-chartering',   'tug-and-barge-chartering.php', 'Shallow-draft and remote-site delivery where deep-water port infrastructure is not available.', 'assets/img/icons/s-icons3.svg', 3, 'active'),
(4, 'Roll On / Roll Off',                     'roll-on-roll-off',           'roll-on-roll-off.php', 'Mafi trailers and ramp operations for rolling stock and awkward breakbulk units.', 'assets/img/icons/s-icons4.svg', 4, 'active'),
(5, 'Air Freight',                            'air-freight',                'air-freight.php', 'Time-critical and AOG shipments, including oversize and dangerous goods handling.', 'assets/img/icons/s-icons5.svg', 5, 'active');

INSERT IGNORE INTO `resources` (`id`, `category`, `title`, `slug`, `sort_order`, `status`) VALUES
(1, 'Cargo Measurement',   'CBM versus Freight Ton',                              'cbm-versus-freight-ton',        1, 'active'),
(2, 'Incoterms',           'Incoterms Explained',                                 'incoterms',                     2, 'active'),
(3, 'Insurance',           'Freight Service Liability versus Cargo Insurance',    'liability-versus-cargo-insurance', 3, 'active'),
(4, 'Certificates',        'Our Certificates',                                    'our-certificates',              4, 'active'),
(5, 'Terms & Conditions',  'CGS General Terms and Conditions',                    'general-terms-and-conditions',  5, 'active');

INSERT IGNORE INTO `certificates` (`id`, `title`, `issuer`, `sort_order`, `status`) VALUES
(1, 'ISO 9001:2015 Quality Management System', NULL, 1, 'active');

INSERT IGNORE INTO `page_blocks` (`page_key`, `block_key`, `heading`, `sort_order`) VALUES
('home',      'video',     'See How We Move Project Cargo',       1),
('home',      'intro',     'Integrated Customized Logistics',     2),
('home',      'why-us',    'Why Carriage Global',                 3),
('home',      'cta',       'Send Us Your Packing List',           4),
('our-fleet', 'intro',     'Our Fleet',                           1),
('our-fleet', 'lashing',   'In-House Lashing',                    2),
('our-fleet', 'open-yard', 'Open Yard for Cargo Storage & Re-Working', 3);

-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Aug 22, 2026 at 04:13 AM
-- Server version: 8.0.44
-- PHP Version: 8.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `weboweb_site3`
--

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `issuer` varchar(200) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `cert_no` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_520_ci DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `title`, `issuer`, `cert_no`, `valid_until`, `image`, `file_path`, `sort_order`, `status`) VALUES
(1, 'ISO 9001:2015 Quality Management System', NULL, NULL, NULL, NULL, NULL, 1, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `page_key` varchar(60) COLLATE utf8mb4_unicode_520_ci DEFAULT 'home',
  `service_id` int DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_520_ci DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fleet_items`
--

CREATE TABLE `fleet_items` (
  `id` int NOT NULL,
  `category` enum('Fleet','Lashing','Open Yard') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'Fleet',
  `title` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci,
  `specs` text COLLATE utf8mb4_unicode_520_ci,
  `quantity` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_520_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT 'General',
  `service_id` int DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `featured` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_520_ci DEFAULT 'active',
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_slides`
--

CREATE TABLE `hero_slides` (
  `id` int NOT NULL,
  `eyebrow` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `heading` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subheading` text COLLATE utf8mb4_unicode_520_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `cta_label` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `cta_link` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_520_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `hero_slides`
--

INSERT INTO `hero_slides` (`id`, `eyebrow`, `heading`, `subheading`, `image`, `alt_text`, `cta_label`, `cta_link`, `sort_order`, `status`, `created_at`) VALUES
(1, 'Project cargo, heavy lift, break bulk', 'Cargo that does not fit a container, moved anyway', 'Heavy lift, break bulk and project cargo by sea, air and road, planned from your packing list.', 'assets/img/bg/hero-bg.jpg', 'Project cargo lifted onto a barge alongside a geared vessel in Singapore', 'Get a Quote', 'contact.php', 1, 'active', '2026-08-17 08:20:22'),
(2, 'Chartering', 'Self-geared and semi-geared vessels', 'Feeder ships, barges, landing craft and deep-sea mother vessels, matched to weight, volume and route.', 'assets/img/hero/self-geared-crane.jpg', 'MacGregor self-geared ship crane against the sky', 'Get a Quote', 'contact.php', 2, 'active', '2026-08-17 08:20:22'),
(3, 'Our fleet', 'Our own trailers, our own lashing crew', 'Fleet, in-house lashing and an open yard for storage and re-working, under one operation.', 'assets/img/services/project-freight-forwarding.jpg', 'Oversized vessel section secured on a Carriage Global low-bed trailer', 'Get a Quote', 'contact.php', 3, 'inactive', '2026-08-17 08:20:22');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `company` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `service` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_520_ci,
  `source_page` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT 'Website',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `status` enum('New','Contacted','Quoted','Converted','Closed') COLLATE utf8mb4_unicode_520_ci DEFAULT 'New',
  `notes` text COLLATE utf8mb4_unicode_520_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_blocks`
--

CREATE TABLE `page_blocks` (
  `id` int NOT NULL,
  `page_key` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `block_key` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `eyebrow` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `heading` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `subheading` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `body` longtext COLLATE utf8mb4_unicode_520_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `cta_label` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `cta_link` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_520_ci DEFAULT 'active',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `page_blocks`
--

INSERT INTO `page_blocks` (`id`, `page_key`, `block_key`, `eyebrow`, `heading`, `subheading`, `body`, `image`, `cta_label`, `cta_link`, `sort_order`, `status`, `updated_at`) VALUES
(1, 'home', 'video', NULL, 'See How We Move Project Cargo', NULL, NULL, NULL, NULL, NULL, 1, 'active', '2026-08-17 07:10:11'),
(2, 'home', 'intro', NULL, 'Integrated Customized Logistics', NULL, NULL, NULL, NULL, NULL, 2, 'active', '2026-08-17 07:10:11'),
(3, 'home', 'why-us', NULL, 'Why Carriage Global', NULL, NULL, NULL, NULL, NULL, 3, 'active', '2026-08-17 07:10:11'),
(4, 'home', 'cta', NULL, 'Send Us Your Packing List', NULL, NULL, NULL, NULL, NULL, 4, 'active', '2026-08-17 07:10:11'),
(5, 'our-fleet', 'intro', NULL, 'Our Fleet', NULL, NULL, NULL, NULL, NULL, 1, 'active', '2026-08-17 07:10:11'),
(6, 'our-fleet', 'lashing', NULL, 'In-House Lashing', NULL, NULL, NULL, NULL, NULL, 2, 'active', '2026-08-17 07:10:11'),
(7, 'our-fleet', 'open-yard', NULL, 'Open Yard for Cargo Storage & Re-Working', NULL, NULL, NULL, NULL, NULL, 3, 'active', '2026-08-17 07:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_520_ci,
  `content` longtext COLLATE utf8mb4_unicode_520_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_520_ci DEFAULT 'active',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `category`, `title`, `slug`, `summary`, `content`, `image`, `file_path`, `sort_order`, `status`, `updated_at`) VALUES
(1, 'Cargo Measurement', 'CBM versus Freight Ton', 'cbm-versus-freight-ton', NULL, NULL, NULL, NULL, 1, 'active', '2026-08-17 07:10:11'),
(2, 'Incoterms', 'Incoterms Explained', 'incoterms', NULL, NULL, NULL, NULL, 2, 'active', '2026-08-17 07:10:11'),
(3, 'Insurance', 'Freight Service Liability versus Cargo Insurance', 'liability-versus-cargo-insurance', NULL, NULL, NULL, NULL, 3, 'active', '2026-08-17 07:10:11'),
(4, 'Certificates', 'Our Certificates', 'our-certificates', NULL, NULL, NULL, NULL, 4, 'active', '2026-08-17 07:10:11'),
(5, 'Terms & Conditions', 'CGS General Terms and Conditions', 'general-terms-and-conditions', NULL, NULL, NULL, NULL, 5, 'active', '2026-08-17 07:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `short_desc` text COLLATE utf8mb4_unicode_520_ci,
  `intro` text COLLATE utf8mb4_unicode_520_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `cta_image` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_desc` varchar(320) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_520_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `slug`, `link`, `short_desc`, `intro`, `image`, `cta_image`, `icon`, `alt_text`, `meta_title`, `meta_desc`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Project Freight Forwarding', 'project-freight-forwarding', 'project-freight-forwarding.php', 'Mode chosen from your packing list, not from a price list. The practical route, costed against the urgency that actually applies.', NULL, 'assets/img/services/project-freight-forwarding.jpg', 'assets/img/services/project-freight-forwarding-cta.jpg', 'assets/img/icons/s-icons1.svg', 'Carriage Global low-bed trailer hauling a project cargo module under a warehouse canopy', NULL, NULL, 1, 'active', '2026-08-17 07:10:11', '2026-08-20 08:16:25'),
(2, 'Chartering Heavy Lift & Semi-Geared Vessels', 'heavy-lift-chartering', 'heavy-lift-chartering.php', 'Break bulk and project cargo on self-geared and semi-geared vessels, matched to cargo weight, volume and route.', NULL, 'assets/img/services/heavy-lift-chartering.jpg', 'assets/img/services/heavy-lift-chartering-cta.jpg', 'assets/img/icons/s-icons2.svg', 'Heat exchanger unit lifted by a self-geared vessel crane at night', NULL, NULL, 2, 'active', '2026-08-17 07:10:11', '2026-08-20 08:23:37'),
(3, 'Chartering Tug & Barge', 'tug-and-barge-chartering', 'tug-and-barge-chartering.php', 'Shallow-draft and remote-site delivery where deep-water port infrastructure does not reach.', NULL, 'assets/img/services/tug-and-barge-chartering.jpg', 'assets/img/services/tug-and-barge-chartering-cta.jpg', 'assets/img/icons/s-icons3.svg', 'Steel cargo module strapped on a barge deck alongside port cranes', NULL, NULL, 3, 'active', '2026-08-17 07:10:11', '2026-08-20 08:29:08'),
(4, 'Roll On / Roll Off', 'roll-on-roll-off', 'roll-on-roll-off.php', 'Mafi trailers and ramp operations for rolling stock and awkward breakbulk units.', NULL, 'assets/img/services/roll-on-roll-off.jpg', 'assets/img/services/roll-on-roll-off-cta.jpg', 'assets/img/icons/s-icons4.svg', 'CAT crawler tractor craned onto a low-bed trailer alongside a geared vessel', NULL, NULL, 4, 'active', '2026-08-17 07:10:11', '2026-08-20 08:16:25'),
(5, 'Air Freight', 'air-freight', 'air-freight.php', 'Optimized cargo packing and multi-modal routing to major air hubs, instead of booking a costly full charter.', NULL, 'assets/img/services/air-freight.jpg', 'assets/img/services/air-freight-cta.jpg', 'assets/img/icons/s-icons5.svg', 'Air freight pallet netted and strapped for airline cargo transport', NULL, NULL, 5, 'active', '2026-08-17 07:10:11', '2026-08-20 08:23:37');

-- --------------------------------------------------------

--
-- Table structure for table `service_sections`
--

CREATE TABLE `service_sections` (
  `id` int NOT NULL,
  `service_id` int NOT NULL,
  `heading` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `body` longtext COLLATE utf8mb4_unicode_520_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `layout` enum('text','text-image','image-text','list','cards','table') COLLATE utf8mb4_unicode_520_ci DEFAULT 'text',
  `sort_order` int DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_520_ci DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT 'assets/img/logo/cgs-logo.jpg',
  `logo_light` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT 'assets/img/logo/cgs-favicon.jpg',
  `hero_bg_image` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT 'assets/img/bg/hero-bg.jpg',
  `hero_heading` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT 'Integrated Project Logistics, Engineered End to End',
  `hero_subheading` text COLLATE utf8mb4_unicode_520_ci,
  `video_band_src` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT 'assets/video/cgs-video-band.mp4',
  `video_band_poster` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `company_name` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT 'Carriage Global (S) Pte Ltd',
  `company_short` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT 'CGS',
  `uen` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT '200714170K',
  `iso_statement` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT 'An ISO 9001:2015 Certified Company',
  `years_experience` int DEFAULT '18',
  `about_summary` text COLLATE utf8mb4_unicode_520_ci,
  `phone` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT '+65 6899 8251',
  `phone_247` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT '+65 6515 6106',
  `fax` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT '+65 6472 5443',
  `whatsapp_number` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT 'angeline@carriageglobal.com',
  `address` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT '21 Bukit Batok Crescent, WCEGA Tower #17-82, Singapore 658065',
  `map_embed_url` text COLLATE utf8mb4_unicode_520_ci,
  `my_office_name` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT 'Carriage Global (M) Sdn Bhd',
  `my_reg_no` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT '1236698-V',
  `my_address` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT 'Suite 28.02, 28th Floor, Menara Zurich No.15, Jalan Dato Abdullah Tahir, Johor Bahru, Johor',
  `my_phone` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `my_email` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `my_map_embed_url` text COLLATE utf8mb4_unicode_520_ci,
  `facebook_url` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `instagram_url` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `twitter_url` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `youtube_url` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `lead_notify_email` varchar(150) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `logo`, `logo_light`, `favicon`, `hero_bg_image`, `hero_heading`, `hero_subheading`, `video_band_src`, `video_band_poster`, `company_name`, `company_short`, `uen`, `iso_statement`, `years_experience`, `about_summary`, `phone`, `phone_247`, `fax`, `whatsapp_number`, `email`, `address`, `map_embed_url`, `my_office_name`, `my_reg_no`, `my_address`, `my_phone`, `my_email`, `my_map_embed_url`, `facebook_url`, `instagram_url`, `linkedin_url`, `twitter_url`, `youtube_url`, `lead_notify_email`, `updated_at`) VALUES
(1, 'assets/img/logo/cgs-logo.jpg', NULL, 'assets/img/logo/cgs-favicon.jpg', 'assets/img/bg/hero-bg.jpg', 'Cargo that does not fit a container, moved anyway', 'Heavy lift, break bulk and project cargo by sea, air and road, planned from your packing list.', 'assets/video/hero_video.mp4', NULL, 'Carriage Global (S) Pte Ltd', 'CGS', '200714170K', 'An ISO 9001:2015 Certified Company', 18, 'Carriage Global provides integrated customized logistics for the oil and gas, offshore, heavy lift, energy, construction and mining sectors, managing and optimizing supply chains from origin to final site.', '+65 6515 6106', '+65 6899 8251', '+65 6472 5443', NULL, 'admin@carriageglobal.com', '21 Bukit Batok Crescent, WCEGA Tower #17-82, Singapore 658065', NULL, 'Carriage Global (M) Sdn Bhd', '1236698-V', 'Suite 28.02, 28th Floor, Menara Zurich No.15, Jalan Dato Abdullah Tahir, Johor Bahru, Johor', NULL, NULL, NULL, '#', 'https://www.instagram.com/carriageglobal', '#', NULL, '#', NULL, '2026-08-22 03:58:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT 'Admin',
  `status` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT 'Active',
  `otp_code` varchar(10) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_key` (`page_key`);

--
-- Indexes for table `fleet_items`
--
ALTER TABLE `fleet_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_sort` (`status`,`sort_order`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `page_blocks`
--
ALTER TABLE `page_blocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_block` (`page_key`,`block_key`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `service_sections`
--
ALTER TABLE `service_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fleet_items`
--
ALTER TABLE `fleet_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `page_blocks`
--
ALTER TABLE `page_blocks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_sections`
--
ALTER TABLE `service_sections`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `service_sections`
--
ALTER TABLE `service_sections`
  ADD CONSTRAINT `fk_service_sections_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

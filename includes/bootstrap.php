<?php
/*
|--------------------------------------------------------------------------
| BOOTSTRAP
|--------------------------------------------------------------------------
| Every public page starts with:
|
|     require_once __DIR__ . '/includes/bootstrap.php';
|
| It opens the database, loads the data that the header and footer need on
| every single page ($settings, $services), and defines the small helper
| set the templates use. Page-specific queries belong in the page itself,
| not here.
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../admin/config/db.php'; // provides $pdo

/*
|--------------------------------------------------------------------------
| BASE_URL
|--------------------------------------------------------------------------
| Root-relative prefix for every asset and link, worked out from where the
| front controller actually lives. Works at a domain root and in a
| subfolder (http://localhost/site3/) without editing anything.
*/
if (!defined('BASE_URL')) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $scriptDir = rtrim($scriptDir, '/');
    define('BASE_URL', $scriptDir === '' ? '/' : $scriptDir . '/');
}

/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/

/** Escape for HTML output. Used on every value that reaches the page. */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Root-relative URL for an asset or page, already escaped. */
function url(string $path = ''): string
{
    return e(BASE_URL . ltrim($path, '/'));
}

/**
 * Same as url(), plus a ?v=<mtime> cache-buster so edits to cgs.css/cgs.js
 * during active development don't get served stale out of a visitor's
 * browser cache — those two files have no version in the filename, unlike
 * the vendor plugin bundles which don't change.
 */
function asset_url(string $path): string
{
    $version = @filemtime(__DIR__ . '/../' . ltrim($path, '/')) ?: time();
    return url($path) . '?v=' . $version;
}

/** Filename of the page currently being served, e.g. "index.php". */
function current_page(): string
{
    return basename($_SERVER['PHP_SELF'] ?? 'index.php');
}

/**
 * "is-active" when $file is the current page, for nav highlighting.
 * $file may be a single filename or an array (a parent item that should
 * stay lit while any of its children are open).
 */
function nav_active($file, string $class = 'is-active'): string
{
    $files = (array) $file;
    return in_array(current_page(), $files, true) ? $class : '';
}

/** Strip a phone number down to what tel: accepts. */
function tel_link(string $phone): string
{
    return preg_replace('/[^0-9+]/', '', $phone);
}

/** Truncate on a word boundary, for card blurbs. */
function excerpt(?string $text, int $limit = 140): string
{
    $text = trim(strip_tags((string) $text));
    if ($text === '' || mb_strlen($text) <= $limit) {
        return $text;
    }
    $cut = mb_substr($text, 0, $limit);
    $sp  = mb_strrpos($cut, ' ');
    return rtrim($sp ? mb_substr($cut, 0, $sp) : $cut, ' ,.;:') . '…';
}

/**
 * Run a query, returning [] if the table isn't there yet.
 * Lets the front end render during setup, before schema.sql has been
 * imported, instead of dying on a missing table.
 */
function db_all(PDO $pdo, string $sql, array $params = []): array
{
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('Query failed: ' . $e->getMessage() . ' — ' . $sql);
        return [];
    }
}

/*
|--------------------------------------------------------------------------
| SETTINGS
|--------------------------------------------------------------------------
| Single row. The fallbacks below mirror the column defaults in
| database/schema.sql, so the header and footer still render correctly
| before the client has been through the admin Settings screen.
*/
$settingsRows = db_all($pdo, 'SELECT * FROM settings ORDER BY id DESC LIMIT 1');
$settings     = $settingsRows[0] ?? [];

$settingDefaults = [
    'logo'              => 'assets/img/logo/cgs-logo.jpg',
    'logo_light'        => null,
    'favicon'           => 'assets/img/logo/cgs-favicon.jpg',
    'hero_bg_image'     => 'assets/img/bg/hero-bg.jpg',
    'hero_heading'      => 'Cargo that does not fit a container, moved anyway',
    'hero_subheading'   => 'Heavy lift, break bulk and project cargo by sea, air and road, planned from your packing list.',
    'video_band_src'    => 'assets/video/cgs-video-band.mp4',
    'video_band_poster' => null,
    'hero_bg_video'     => null,
    'company_name'      => 'Carriage Global (S) Pte Ltd',
    'company_short'     => 'CGS',
    'uen'               => '200714170K',
    'iso_statement'     => 'An ISO 9001:2015 Certified Company',
    'years_experience'  => 18,
    'about_summary'     => null,
    'phone'             => '+65 6899 8251',
    'phone_247'         => '+65 6515 6106',
    'fax'               => '+65 6472 5443',
    'whatsapp_number'   => null,
    'email'             => 'angeline@carriageglobal.com',
    'address'           => '21 Bukit Batok Crescent, WCEGA Tower #17-82, Singapore 658065',
    'map_embed_url'     => null,
    'my_office_name'    => 'Carriage Global (M) Sdn Bhd',
    'my_reg_no'         => '1236698-V',
    'my_address'        => 'Suite 28.02, 28th Floor, Menara Zurich No.15, Jalan Dato Abdullah Tahir, Johor Bahru, Johor',
    'my_phone'          => null,
    'my_email'          => null,
    'facebook_url'      => null,
    'instagram_url'     => null,
    'linkedin_url'      => null,
    'twitter_url'       => null,
    'youtube_url'       => null,
    'lead_notify_email' => null,
];

/*
 * Apply the fallbacks. Note this is a loop, not `$settings += $defaults` —
 * `+=` only fills keys that are *absent*, and once the settings row exists
 * every column is present, just NULL. That would leave every unfilled field
 * rendering as an empty string with the default never firing.
 */
foreach ($settingDefaults as $key => $default) {
    if (!isset($settings[$key]) || $settings[$key] === '') {
        $settings[$key] = $default;
    }
}

/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
| Drives the Services dropdown, the mobile menu and the footer, so it is
| loaded here rather than per page. If the table is empty the nav falls
| back to a plain link to services.php.
*/
$services = db_all(
    $pdo,
    "SELECT * FROM services WHERE status = 'active' ORDER BY sort_order ASC, id ASC"
);

/*
|--------------------------------------------------------------------------
| DERIVED CONTACT VALUES
|--------------------------------------------------------------------------
*/
$phoneTel    = tel_link($settings['phone']);
$phone247Tel = tel_link((string) $settings['phone_247']);

$whatsappDigits = preg_replace('/[^0-9]/', '', (string) $settings['whatsapp_number']);
$whatsappLink   = $whatsappDigits
    ? 'https://wa.me/' . $whatsappDigits . '?text=' . rawurlencode(
        'Hi CGS, I would like to enquire about your project logistics services.'
      )
    : '';

/**
 * Social columns => [icon class, accessible label].
 * An icon is rendered only when that URL is actually set, so an
 * unconfigured site shows no empty placeholders.
 */
$socialPlatforms = [
    'linkedin_url'  => ['fa-brands fa-linkedin-in', 'LinkedIn'],
    'facebook_url'  => ['fa-brands fa-facebook-f',  'Facebook'],
    'instagram_url' => ['fa-brands fa-instagram',   'Instagram'],
    'youtube_url'   => ['fa-brands fa-youtube',     'YouTube'],
    'twitter_url'   => ['fa-brands fa-x-twitter',   'X'],
];

/** Feedback from submit-lead.php when a form bounced back with an error. */
$leadError = $_GET['lead_error'] ?? '';

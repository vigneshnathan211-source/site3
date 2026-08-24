<?php
/*
|--------------------------------------------------------------------------
| SUBMIT LEAD — shared form handler for every form on the site
|--------------------------------------------------------------------------
| First consumer is contact.php's enquiry form (2026-08-24). Inserts
| straight into the existing `leads` table (database/schema.sql) — no
| SMTP/PHPMailer send here, since admin/config/mail_config.php only ships
| placeholder credentials (CLAUDE.md: real credentials are never committed).
| Wiring a lead-notification email through PHPMailer once real SMTP
| credentials exist on the server is tracked as an open item, not done here.
|
| Honeypot ("website") + server-side required-field validation, per
| CLAUDE.md's Security section: "HTML5 required is not validation."
*/

require_once __DIR__ . '/includes/bootstrap.php';

function back_with_error(string $code): never
{
    header('Location: ' . url('contact.php') . '?lead_error=' . $code . '#contact-form');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('contact.php'));
    exit;
}

// Honeypot: a real visitor never fills this in. Bots that fill every field
// trip it — pretend success so they don't learn the trap worked.
if (trim((string) ($_POST['website'] ?? '')) !== '') {
    header('Location: ' . url('thankyou.php'));
    exit;
}

$name    = trim((string) ($_POST['name'] ?? ''));
$company = trim((string) ($_POST['company'] ?? ''));
$email   = trim((string) ($_POST['email'] ?? ''));
$phone   = trim((string) ($_POST['phone'] ?? ''));
$service = trim((string) ($_POST['service'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));
$source  = trim((string) ($_POST['source_page'] ?? 'Website'));

if ($name === '' || $email === '' || $phone === '' || $message === '') {
    back_with_error('invalid');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    back_with_error('email');
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO leads (name, company, email, phone, service, message, source_page, ip_address)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $name,
        $company !== '' ? $company : null,
        $email,
        $phone,
        $service !== '' ? $service : null,
        $message,
        $source !== '' ? $source : 'Website',
        $_SERVER['REMOTE_ADDR'] ?? null,
    ]);
} catch (PDOException $e) {
    error_log('Lead insert failed: ' . $e->getMessage());
    back_with_error('error');
}

header('Location: ' . url('thankyou.php'));
exit;

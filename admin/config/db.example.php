<?php
/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION — TEMPLATE
|--------------------------------------------------------------------------
| Copy this file to db.php and fill in the real values. db.php is gitignored
| because it holds live credentials; this template is what gets tracked.
|
|     cp admin/config/db.example.php admin/config/db.php
|
| Provides $pdo to everything that requires it.
|
| Local vs production differ only in these five values, so they are detected
| from the hostname rather than kept in two edited copies that drift apart.
*/

/*
 * Anything that isn't a real public hostname counts as local — that covers
 * localhost, Herd/Valet's *.test domains, and the CLI (which has no
 * SERVER_NAME at all).
 */
$serverName = strtolower($_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost');
$serverName = strtok($serverName, ':'); // drop any :port

$isLocal = PHP_SAPI === 'cli'
    || in_array($serverName, ['localhost', '127.0.0.1', '::1', ''], true)
    || (bool) preg_match('/\.(test|local|localhost)$/', $serverName);

if ($isLocal) {
    $host = 'localhost';
    $port = '3306';
    $db   = 'weboweb_site3';
    $user = 'root';
    $pass = '';
} else {
    // From hPanel > Databases
    $host = 'localhost';
    $port = '3306';
    $db   = 'REPLACE_DB_NAME';
    $user = 'REPLACE_DB_USER';
    $pass = 'REPLACE_DB_PASSWORD';
}

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Never echo the driver message to a visitor — it leaks credentials and paths.
    error_log('DB connection failed: ' . $e->getMessage());
    http_response_code(503);
    exit('The site is temporarily unavailable. Please try again shortly.');
}

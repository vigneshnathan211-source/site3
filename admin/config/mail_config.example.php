<?php
/*
|--------------------------------------------------------------------------
| SMTP SETTINGS — TEMPLATE
|--------------------------------------------------------------------------
| Copy this file to mail_config.php and fill in the real values.
| mail_config.php is gitignored because it holds a live mailbox password.
|
|     cp admin/config/mail_config.example.php admin/config/mail_config.php
|
| Used by admin/index.php (login OTP) and submit-lead.php (lead notification).
*/

define('MAIL_HOST',       'smtp.hostinger.com');
define('MAIL_USERNAME',   'REPLACE@example.com');
define('MAIL_PASSWORD',   'REPLACE_MAILBOX_PASSWORD');
define('MAIL_PORT',       465);
define('MAIL_ENCRYPTION', 'ssl');
define('MAIL_FROM_NAME',  'Carriage Global (S) Pte Ltd');

<?php
/**
 * Application Configuration
 * Loads environment variables and provides global config
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}

// Load .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B'\"");
        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

loadEnv(BASE_PATH . '/.env');

// Application Settings
define('APP_ENV', getenv('APP_ENV') ?: 'production');
define('APP_DEBUG', filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN));

// Auto-detect secure scheme and host to prevent mixed-content/insecure form warnings
$isSecure = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1))
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    || (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
    || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
$scheme = $isSecure ? 'https' : 'http';

$envAppUrl = getenv('APP_URL');
if ($envAppUrl && $envAppUrl !== 'http://localhost') {
    if ($isSecure && strpos($envAppUrl, 'http://') === 0) {
        $envAppUrl = 'https://' . substr($envAppUrl, 7);
    }
    $appUrl = $envAppUrl;
} elseif (isset($_SERVER['HTTP_HOST'])) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $subfolder = ($scriptDir === '/' || $scriptDir === '.') ? '' : rtrim($scriptDir, '/');
    $appUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . $subfolder;
} else {
    $appUrl = 'http://localhost';
}

define('APP_URL', rtrim($appUrl, '/'));
define('BASE_URL', APP_URL);
define('APP_CURRENCY', getenv('APP_CURRENCY') ?: 'KSh');
define('APP_CURRENCY_SYMBOL', getenv('APP_CURRENCY_SYMBOL') ?: 'KSh ');

// Google Gemini AI API Configuration
define('GOOGLE_API_KEY', getenv('GOOGLE_API_KEY') ?: (getenv('GEMINI_API_KEY') ?: ''));

// Google OAuth 2.0 (Sign in with Google)
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');
define('GOOGLE_REDIRECT_URI', getenv('GOOGLE_REDIRECT_URI') ?: '');
// Optional: restrict account creation to a single Google Workspace domain (e.g. tektrend.co.ke). Empty = allow any verified email.
define('GOOGLE_ALLOWED_DOMAIN', getenv('GOOGLE_ALLOWED_DOMAIN') ?: '');
define('GOOGLE_OAUTH_AUTH_ENDPOINT', 'https://accounts.google.com/o/oauth2/v2/auth');
define('GOOGLE_OAUTH_TOKEN_ENDPOINT', 'https://oauth2.googleapis.com/token');
define('GOOGLE_OAUTH_USERINFO_ENDPOINT', 'https://www.googleapis.com/oauth2/v3/userinfo');

// GitHub OAuth 2.0 (Sign in with GitHub)
define('GITHUB_CLIENT_ID', getenv('GITHUB_CLIENT_ID') ?: '');
define('GITHUB_CLIENT_SECRET', getenv('GITHUB_CLIENT_SECRET') ?: '');
// Optional: restrict account creation to specific email domains (comma-separated, e.g. tektrend.co.ke,gmail.com). Empty = allow any verified email.
define('GITHUB_ALLOWED_DOMAIN', getenv('GITHUB_ALLOWED_DOMAIN') ?: '');
define('GITHUB_OAUTH_AUTH_ENDPOINT', 'https://github.com/login/oauth/authorize');
define('GITHUB_OAUTH_TOKEN_ENDPOINT', 'https://github.com/login/oauth/access_token');
define('GITHUB_OAUTH_USER_ENDPOINT', 'https://api.github.com/user');
define('GITHUB_OAUTH_EMAILS_ENDPOINT', 'https://api.github.com/user/emails');

// Google Meet (online meetings) - a Google Calendar event is auto-created with a Meet
// conference and its join link (https://meet.google.com/...) is stored per booking/meeting.
// Uses a Google Cloud service account. The private key stays ONLY on the server and must
// never be committed or exposed. Participants open the link in Google Meet (external tab).
define('GOOGLE_MEET_ENABLED', filter_var(getenv('GOOGLE_MEET_ENABLED') ?: 'true', FILTER_VALIDATE_BOOLEAN));
// Easiest: absolute path to the downloaded service-account JSON, kept OUTSIDE the web root.
define('GOOGLE_MEET_KEY_FILE', getenv('GOOGLE_MEET_KEY_FILE') ?: '');
// Alternative to the JSON file: set the client_email and the PEM private key directly.
define('GOOGLE_MEET_CLIENT_EMAIL', getenv('GOOGLE_MEET_CLIENT_EMAIL') ?: '');
define('GOOGLE_MEET_PRIVATE_KEY', getenv('GOOGLE_MEET_PRIVATE_KEY') ?: '');
// Calendar that owns the events: 'primary' = the service account's own calendar.
define('GOOGLE_MEET_CALENDAR_ID', getenv('GOOGLE_MEET_CALENDAR_ID') ?: 'primary');
define('GOOGLE_MEET_TIMEZONE', getenv('GOOGLE_MEET_TIMEZONE') ?: 'Africa/Nairobi');

// Database Settings
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'tektrend');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Session Settings
define('SESSION_LIFETIME', (int)(getenv('SESSION_LIFETIME') ?: 1440));
define('SESSION_SECURE', filter_var(getenv('SESSION_SECURE') ?: $isSecure, FILTER_VALIDATE_BOOLEAN));

// Security
define('SECRET_KEY', getenv('SECRET_KEY') ?: 'default_secret_key_change_me');
define('BCRYPT_ROUNDS', (int)(getenv('BCRYPT_ROUNDS') ?: 12));

// Pagination
define('ITEMS_PER_PAGE', (int)(getenv('ITEMS_PER_PAGE') ?: 20));

// Paths
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('UPLOAD_PATH', BASE_PATH . '/uploads');
define('VIEW_PATH', APP_PATH . '/views');

// Upload settings
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_FILE_TYPES', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'csv']);

// Error reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Lax');
if (defined('SESSION_SECURE') && SESSION_SECURE) {
    ini_set('session.cookie_secure', 1);
}

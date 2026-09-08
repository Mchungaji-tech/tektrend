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
define('APP_URL', rtrim(getenv('APP_URL') ?: 'http://localhost', '/'));
define('BASE_URL', APP_URL);
define('APP_CURRENCY', getenv('APP_CURRENCY') ?: 'KSh');
define('APP_CURRENCY_SYMBOL', getenv('APP_CURRENCY_SYMBOL') ?: 'KSh ');

// Database Settings
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'tektrend');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Session Settings
define('SESSION_LIFETIME', (int)(getenv('SESSION_LIFETIME') ?: 1440));
define('SESSION_SECURE', filter_var(getenv('SESSION_SECURE') ?: (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'), FILTER_VALIDATE_BOOLEAN));

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

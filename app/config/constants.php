<?php
/**
 * Global Constants and Helper Functions
 */

// Role constants
define('ROLE_ADMIN', 'admin');
define('ROLE_MANAGER', 'manager');
define('ROLE_EMPLOYEE', 'employee');
define('ROLE_ACCOUNTANT', 'accountant');
define('ROLE_SALES', 'sales');
define('ROLE_SUPPORT', 'support');

// Role hierarchy for permission checks
define('ROLE_HIERARCHY', [
    'support'   => 1,
    'sales'     => 2,
    'employee'  => 3,
    'accountant'=> 4,
    'manager'   => 5,
    'admin'     => 6
]);

// Permission constants
define('PERM_VIEW', 'view');
define('PERM_CREATE', 'create');
define('PERM_EDIT', 'edit');
define('PERM_DELETE', 'delete');
define('PERM_MANAGE', 'manage');

// Status constants
define('STATUS_ACTIVE', 'active');
define('STATUS_INACTIVE', 'inactive');
define('STATUS_PENDING', 'pending');
define('STATUS_COMPLETED', 'completed');

// Work status
define('WORK_OFFLINE', 'offline');
define('WORK_WORKING', 'working');
define('WORK_AWAY', 'away');
define('WORK_BREAK', 'break');
define('WORK_MEETING', 'meeting');

// Date formats
define('DATE_FORMAT', 'Y-m-d');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');
define('DISPLAY_DATE_FORMAT', 'M j, Y');
define('DISPLAY_DATETIME_FORMAT', 'M j, Y g:i A');

/**
 * Check if user has minimum role
 */
function hasRole($requiredRole, $userRole = null) {
    if ($userRole === null) {
        $userRole = getCurrentUserRole();
    }
    $userLevel = ROLE_HIERARCHY[$userRole] ?? 0;
    $requiredLevel = ROLE_HIERARCHY[$requiredRole] ?? 0;
    return $userLevel >= $requiredLevel;
}

/**
 * Get current user role
 */
function getCurrentUserRole() {
    if (isset($_SESSION['user_role'])) {
        return $_SESSION['user_role'];
    }
    return null;
}

/**
 * Generate CSRF token
 */
function csrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCsrf($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Generate a random string
 */
function randomString($length = 16) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Sanitize input
 */
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    if ($input === null) {
        return '';
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Format currency
 */
function formatCurrency($amount, $symbol = null) {
    if ($symbol === null) {
        $curr = defined('APP_CURRENCY') ? APP_CURRENCY : 'KSh';
        $symbol = rtrim($curr) . ' ';
    }
    return $symbol . number_format((float)$amount, 2);
}

/**
 * Format date
 */
function formatDate($date, $format = DISPLAY_DATE_FORMAT) {
    if (!$date) return '-';
    return date($format, strtotime($date));
}

/**
 * Format datetime
 */
function formatDateTime($datetime, $format = DISPLAY_DATETIME_FORMAT) {
    if (!$datetime) return '-';
    return date($format, strtotime($datetime));
}

/**
 * Time ago helper
 */
function timeAgo($datetime) {
    if (!$datetime) return '-';
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;

    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hr ago';
    if ($diff < 604800) return floor($diff / 86400) . ' day ago';
    return floor($diff / 604800) . ' week ago';
}

/**
 * Generate slug
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = trim($text, '-');
    $text = strtolower($text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    return $text ?: '-';
}

/**
 * Upload file
 */
function uploadFile($file, $directory = 'uploads', $allowedTypes = null) {
    if (!isset($file['error'])) {
        return ['success' => false, 'error' => 'No file uploaded'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
            UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
            UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder on server.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.'
        ];
        return ['success' => false, 'error' => $uploadErrors[$file['error']] ?? ('Upload failed with error code ' . $file['error'])];
    }

    $maxBytes = defined('MAX_UPLOAD_SIZE') ? MAX_UPLOAD_SIZE : (5 * 1024 * 1024);
    if ($file['size'] > $maxBytes) {
        $maxMB = round($maxBytes / (1024 * 1024), 1);
        return ['success' => false, 'error' => "File is too large. Maximum allowed size is {$maxMB}MB."];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($allowedTypes === null) {
        $allowedTypes = defined('ALLOWED_IMAGE_TYPES') ? ALLOWED_IMAGE_TYPES : ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    }

    if (!in_array($ext, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file format (.' . htmlspecialchars($ext) . '). Allowed types: ' . implode(', ', $allowedTypes)];
    }

    $uploadDir = UPLOAD_PATH . '/' . $directory;
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0755, true);
    }

    $filename = uniqid('proj_') . '_' . time() . '.' . $ext;
    $filepath = $uploadDir . '/' . $filename;

    $moved = false;
    if (is_uploaded_file($file['tmp_name'])) {
        $moved = move_uploaded_file($file['tmp_name'], $filepath);
    } else {
        $moved = @copy($file['tmp_name'], $filepath);
    }

    if ($moved) {
        // Also mirror to public/uploads if public exists as a distinct directory
        if (defined('BASE_PATH')) {
            $publicDir = BASE_PATH . '/public/uploads/' . $directory;
            if (is_dir(BASE_PATH . '/public') && realpath(BASE_PATH . '/public') !== realpath(BASE_PATH)) {
                if (!is_dir($publicDir)) {
                    @mkdir($publicDir, 0755, true);
                }
                @copy($filepath, $publicDir . '/' . $filename);
            }
        }
        return ['success' => true, 'filename' => $directory . '/' . $filename];
    }

    return ['success' => false, 'error' => 'Failed to save file to destination directory. Check folder permissions.'];
}

/**
 * Flash message
 */
function flash($key, $message = null) {
    if ($message === null) {
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
    $_SESSION['flash'][$key] = $message;
}

/**
 * Redirect
 */
function redirect($url = '/') {
    $baseUrl = rtrim(BASE_URL, '/');
    if (strpos($url, 'http') === 0 || strpos($url, '//') === 0) {
        header('Location: ' . $url);
    } else {
        $url = '/' . ltrim($url, '/');
        header('Location: ' . $baseUrl . $url);
    }
    exit;
}

/**
 * Generate a URL with base path
 */
function url($path = '/') {
    $baseUrl = rtrim(BASE_URL, '/');
    $isSecure = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1))
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        || (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
        || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

    if (strpos($path, 'http://') === 0) {
        return $isSecure ? ('https://' . substr($path, 7)) : $path;
    }
    if (strpos($path, 'https://') === 0 || strpos($path, '//') === 0) {
        return $path;
    }

    if ($isSecure && strpos($baseUrl, 'http://') === 0) {
        $baseUrl = 'https://' . substr($baseUrl, 7);
    }

    $path = '/' . ltrim($path, '/');
    return $baseUrl . $path;
}

/**
 * Output a URL with base path (escaped for HTML attributes)
 */
function eurl($path = '/') {
    return htmlspecialchars(url($path), ENT_QUOTES, 'UTF-8');
}

/**
 * Get the previous URL (back)
 */
function backUrl($default = '/') {
    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']) {
        $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
        if ($referer && $referer !== '/' && $referer !== $_SERVER['REQUEST_URI']) {
            return $_SERVER['HTTP_REFERER'];
        }
    }
    return $default;
}

/**
 * JSON response
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Log audit action
 */
function auditLog($action, $tableName = null, $recordId = null, $details = null) {
    try {
        $db = Database::getInstance();
        $db->insert(
            "INSERT INTO audit_logs (user_id, action, table_name, record_id, ip_address, user_agent, details) 
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $_SESSION['user_id'] ?? null,
                $action,
                $tableName,
                $recordId,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
                $details
            ]
        );
    } catch (Exception $e) {
        // Silent fail - don't break the app for logging
    }
}

/**
 * CMS Content Helper
 * Retrieve dynamic text or HTML from content table with graceful fallback
 */
function cms($key, $default = '') {
    static $cmsCache = null;
    if ($cmsCache === null) {
        $cmsCache = [];
        try {
            $db = Database::getInstance();
            $rows = $db->fetchAll("SELECT `key`, `content` FROM content WHERE is_active = 1");
            foreach ($rows as $row) {
                $cmsCache[$row['key']] = $row['content'];
            }
        } catch (\Throwable $e) {
            // DB not connected or table not created yet - cache stays empty
        }
    }
    if (isset($cmsCache[$key]) && $cmsCache[$key] !== null && $cmsCache[$key] !== '') {
        return $cmsCache[$key];
    }
    return $default;
}

/**
 * CMS Image Helper
 * Resolves local upload paths, external URLs, or default fallbacks
 */
function cms_img($key, $default = '') {
    $val = cms($key, $default);
    if (!$val) {
        return $default;
    }
    if (strpos($val, 'http://') === 0 || strpos($val, 'https://') === 0 || strpos($val, '//') === 0 || strpos($val, 'data:') === 0) {
        return $val;
    }
    return url('/' . ltrim($val, '/'));
}


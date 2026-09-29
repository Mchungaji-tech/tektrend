<?php
/**
 * Tek Trend Virtual Company Management System
 * Public Entry Point (Root)
 *
 * This is the main entry point for the application.
 * All requests are routed through this file.
 */

// Start timing
$startTime = microtime(true);

// Define base path (root directory)
define('BASE_PATH', __DIR__);

// Support PHP CLI built-in web server for static files & assets
if (php_sapi_name() === 'cli-server') {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($uri !== '/' && is_file(__DIR__ . $uri)) {
        return false;
    }
    $publicPath = __DIR__ . '/public' . $uri;
    if ($uri !== '/' && is_file($publicPath)) {
        $ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
        $mimes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'html' => 'text/html',
            'woff2' => 'font/woff2',
            'woff' => 'font/woff'
        ];
        if (isset($mimes[$ext])) {
            header('Content-Type: ' . $mimes[$ext]);
        }
        readfile($publicPath);
        exit;
    }
}

// Load configuration
require_once BASE_PATH . '/app/config/config.php';
require_once BASE_PATH . '/app/config/constants.php';
require_once BASE_PATH . '/app/config/database.php';

// Load core classes
require_once BASE_PATH . '/app/core/Session.php';
require_once BASE_PATH . '/app/core/Auth.php';
require_once BASE_PATH . '/app/core/View.php';
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/core/Model.php';
require_once BASE_PATH . '/app/core/Router.php';
require_once BASE_PATH . '/app/core/Middleware.php';
require_once BASE_PATH . '/app/App.php';

// Start session
Session::getInstance();

// Initialize and run the application
$app = new App();
$app->bootstrap();

// Log execution time in debug mode
if (APP_DEBUG) {
    $endTime = microtime(true);
    $executionTime = round(($endTime - $startTime) * 1000, 2);
    // Can be used for debugging
}

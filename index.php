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

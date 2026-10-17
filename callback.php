<?php
/**
 * Google OAuth 2.0 Callback Handler
 *
 * This file serves as the direct entry point for Google OAuth redirects
 * when Google Cloud Console is configured with:
 *   Authorized redirect URI: https://yourdomain.com/callback.php
 *                     or e.g. http://localhost/callback.php
 */

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
require_once BASE_PATH . '/app/core/GoogleMeet.php';

// Load GoogleAuthController
require_once BASE_PATH . '/app/controllers/GoogleAuthController.php';

// Start session
Session::getInstance();

// Instantiate and dispatch callback
$controller = new GoogleAuthController();
$controller->callback();

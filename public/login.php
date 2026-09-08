<?php
/**
 * Login redirect - points to the main entry point
 * This file exists for backwards compatibility with the old dashboard.html
 */
require_once dirname(__DIR__) . '/app/config/config.php';
require_once dirname(__DIR__) . '/app/config/constants.php';
redirect('/login');
exit;

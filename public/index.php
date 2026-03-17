<?php
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
require_once '../app/core/init.php';

// Enable error reporting based on DEBUG setting
if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
}

// Check PHP version
if (phpversion() < MIN_VERSION) {
    die("Your PHP Version must be " . MIN_VERSION . " or higher to run this app. Your current version is " . phpversion());
}

// Start session with secure settings
session_start();

// Set secure session cookie settings if HTTPS
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'Strict');
}

// Load the application
$app = new App;
$app->loadController();
 

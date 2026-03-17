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

// HTTPS Enforcement (redirect HTTP to HTTPS in production)
// Skip in development environments or when explicitly disabled
$https_enforce = defined('HTTPS_ENFORCE') ? HTTPS_ENFORCE : false;

// Production-safe HTTPS detection (supports reverse proxies)
$is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
$is_https = $is_https || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

if ($https_enforce && !$is_https && $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Only redirect GET requests (preserve form data)
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    header('Location: https://' . $host . $path, true, 301);
    exit;
}

// Configure secure session settings BEFORE starting session
// These settings protect against session hijacking
ini_set('session.use_strict_mode', 1);          // Don't accept uninitialized session IDs
ini_set('session.use_only_cookies', 1);         // Only use cookies for sessions
ini_set('session.cookie_httponly', 1);          // Prevent JavaScript access to session cookie
ini_set('session.cookie_samesite', 'Strict');   // CSRF protection
ini_set('session.gc_maxlifetime', 1800);        // 30 minute timeout

// Set secure cookie flag if HTTPS
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);        // Only send cookie over HTTPS
}

// Start session with secure configuration
session_start();

// Add security headers to all responses
header('X-Content-Type-Options: nosniff');                              // Prevent MIME sniffing
header('X-Frame-Options: SAMEORIGIN');                                  // Prevent clickjacking
header('X-XSS-Protection: 1; mode=block');                              // XSS protection
header('Referrer-Policy: strict-origin-when-cross-origin');             // Control referrer info
header('Permissions-Policy: geolocation=(), microphone=(), camera=()'); // Restrict APIs

// HSTS (HTTP Strict Transport Security) if HTTPS
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}

// Content Security Policy
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:;");

// Load the application
$app = new App;
$app->loadController();
 

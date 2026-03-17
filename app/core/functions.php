<?php
defined("ROOTPATH") or exit("Access Denied!");

/**
 * Secure logging function - logs errors without exposing them to users
 * Falls back to PHP error_log if logs directory not writable
 * 
 * @param string $message The error message
 * @param array $context Optional context data (user, IP, etc)
 * @param string $level Log level (error, warning, info, debug)
 * @return void
 */
function logError(string $message, array $context = [], string $level = 'error'): void
{
    $logsDir = __DIR__ . '/../../logs';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    $contextJson = !empty($context) ? ' | ' . json_encode($context) : '';
    $logMessage = "[$timestamp] [$ip] $message" . $contextJson . "\n";

    // Try to create and write to logs directory
    try {
        // Create logs directory if it doesn't exist
        if (!is_dir($logsDir)) {
            @mkdir($logsDir, 0777, true);
            @chmod($logsDir, 0777);
        }

        $logFile = $logsDir . '/' . $level . '.log';

        // Make sure file is writable
        if (is_writable($logsDir)) {
            error_log($logMessage, 3, $logFile);
        } else {
            // Fallback: log to PHP error log if directory not writable
            error_log("[LOG_DIR_NOT_WRITABLE] " . $logMessage);
        }
    } catch (Exception $e) {
        // Last resort: log to PHP error log
        error_log($logMessage);
    }

    // Also log to PHP error log if debugging
    if (defined('DEBUG') && DEBUG) {
        error_log("[DEBUG] " . $logMessage);
    }
}

/**
 * Debug dump function - print variable for debugging
 * 
 * @param mixed $stuff The variable to dump
 * @return void
 */
function dd(mixed $stuff): void
{
    echo "<pre>";
    print_r($stuff);
    echo "</pre>";
}

/**
 * Escape HTML special characters for safe output
 * 
 * @param string $str The string to escape
 * @return string The escaped string safe for HTML
 */
function esc(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Generate or retrieve CSRF token
 * Token is stored in session and regenerated periodically (1 hour)
 * 
 * @return string The CSRF token
 */
function generateCSRFToken(): string
{
    // Generate new token if doesn't exist or older than 1 hour
    if (empty($_SESSION['csrf_token']) || (time() - ($_SESSION['csrf_token_time'] ?? 0)) > 3600) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }

    return $_SESSION['csrf_token'];
}

/**
 * Get CSRF token HTML field for use in forms
 * 
 * @return string HTML hidden input element with CSRF token
 */
function csrfField(): string
{
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . esc($token) . '" />';
}

/**
 * Validate CSRF token
 * 
 * @param string $token The token to validate (usually from $_POST)
 * @return bool True if valid, false otherwise
 */
function validateCSRFToken(string $token = ''): bool
{
    // Get token from POST if not provided
    if (empty($token)) {
        $token = $_POST['csrf_token'] ?? '';
    }

    // Check if token exists in session and matches
    if (empty($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        logError("CSRF token validation failed", ['ip' => $_SERVER['REMOTE_ADDR']], 'warning');
        return false;
    }

    return true;
}

/**
 * Require valid CSRF token or die with 403 error
 * Use this in protected POST handlers
 * 
 * @return void
 */
function requireCSRFToken(): void
{
    if (!validateCSRFToken()) {
        http_response_code(403);
        die("Invalid security token. Please try again.");
    }
}

#region Date Functions Format --- HowLongAgo

/**
 * Format a date string to readable format (e.g., "15th March 2026")
 * 
 * @param string $date The date string
 * @return string Formatted date
 */
function format_date(string $date): string
{
    return date('jS F Y', strtotime($date));
}

/**
 * Get human-readable time difference from now
 * Examples: "2 days ago", "just now", "3 hours ago"
 * 
 * @param string $datetime The datetime string
 * @return string Human-readable time ago string
 */
function timeAgo(string $datetime): string
{
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) {
        return $diff->y . " year" . ($diff->y > 1 ? "s" : "") . " ago";
    }
    if ($diff->m > 0) {
        return $diff->m . " month" . ($diff->m > 1 ? "s" : "") . " ago";
    }
    if ($diff->d >= 7) {
        $weeks = floor($diff->d / 7);
        return $weeks . " week" . ($weeks > 1 ? "s" : "") . " ago";
    }
    if ($diff->d > 0) {
        return $diff->d . " day" . ($diff->d > 1 ? "s" : "") . " ago";
    }
    if ($diff->h > 0) {
        return $diff->h . " hour" . ($diff->h > 1 ? "s" : "") . " ago";
    }
    if ($diff->i > 0) {
        return $diff->i . " minute" . ($diff->i > 1 ? "s" : "") . " ago";
    }
    return "just now";
}

#endregion

/**
 * Check for required PHP extensions and die if any are missing
 * 
 * @return void
 */
function check_extensions(): void
{
    $required_extensions = [
        'fileinfo',
        'gd',
        'mbstring',
        'exif',
        'mysqli',
        'pdo_mysql'
    ];
    $not_loaded = [];
    foreach ($required_extensions as $ext) {
        if (!extension_loaded($ext)) {
            $not_loaded[] = $ext;
        }
    }
    if (!empty($not_loaded)) {
        dd("Please load the following extensions in your php.ini file: <br>" . implode('<br>', $not_loaded));
        die();
    }
}

check_extensions();

#endregion

/**
 * Redirect to given URL
 * 
 * @param string $url The URL to redirect to
 * @return void
 */
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}

#region Authentication

/**
 * Check if a user is currently logged in
 * 
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user']);
}

/**
 * Check if the logged-in user is a member
 * 
 * @return bool True if user is logged in as member, false otherwise
 */
function isMember(): bool
{
    return isLoggedIn() && isset($_SESSION['user']->user_role) && $_SESSION['user']->user_role === 'member';
}

#endregion

/**
 * Validate registration form data
 * 
 * @param array $data The registration form data
 * @return array Array of validation errors (empty if valid)
 */
function validateRegistration(array $data): array
{
    $errors = [];

    // Check email
    if (empty($data['user_email'] ?? '')) {
        $errors['email_error'] = 'Email is required.';
    } elseif (!filter_var($data['user_email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email_error'] = 'Invalid email format.';
    }

    if (!empty($errors['email_error'])) {
        Flash::set('danger', $errors['email_error']);
    }

    // Check password
    if (strlen($data['user_password'] ?? '') < 6) {
        $errors['password_error'] = 'Password must be at least 6 characters.';
    } elseif (($data['user_password'] ?? '') !== ($data['confirm_password'] ?? '')) {
        $errors['password_error'] = 'Passwords do not match.';
    }

    if (!empty($errors['password_error'])) {
        Flash::set('danger', $errors['password_error']);
    }

    return $errors;
}

#region Rate Limiting

/**
 * Check if an IP address is rate limited for login attempts
 * Uses exponential backoff: 5min after 3 failures, 15min after 5, 60min after 10
 * 
 * @param string $ip The IP address to check (defaults to current client IP)
 * @return bool True if rate limited, false if allowed to proceed
 */
function isRateLimited(string $ip = ''): bool
{
    if (empty($ip)) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    // Initialize rate limit tracking in session
    if (!isset($_SESSION['_rate_limit'])) {
        $_SESSION['_rate_limit'] = [];
    }

    $key = "login_" . hash('sha256', $ip);
    $now = time();

    // Clean up old entries (older than 1 hour)
    foreach ($_SESSION['_rate_limit'] as $k => $data) {
        if ($now - $data['first_attempt'] > 3600) {
            unset($_SESSION['_rate_limit'][$k]);
        }
    }

    // Check if this IP has exceeded rate limit
    if (isset($_SESSION['_rate_limit'][$key])) {
        $data = $_SESSION['_rate_limit'][$key];
        $attempts = $data['count'];
        $lockout_duration = $data['lockout_until'] ?? 0;

        // Determine lockout period based on failure count
        if ($attempts >= 10) {
            $lockout_duration = 3600; // 60 minutes
        } elseif ($attempts >= 5) {
            $lockout_duration = 900; // 15 minutes
        } elseif ($attempts >= 3) {
            $lockout_duration = 300; // 5 minutes
        }

        // Check if still in lockout period
        if ($now - $data['first_attempt'] < $lockout_duration) {
            // Update lockout timestamp
            $_SESSION['_rate_limit'][$key]['lockout_until'] = $now + $lockout_duration;
            return true; // Rate limited
        }

        // Lockout period expired, reset
        unset($_SESSION['_rate_limit'][$key]);
    }

    return false; // Not rate limited
}

/**
 * Record a failed login attempt for an IP address
 * 
 * @param string $ip The IP address (defaults to current client IP)
 * @return int The total number of failed attempts for this IP
 */
function recordFailedAttempt(string $ip = ''): int
{
    if (empty($ip)) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    // Initialize rate limit tracking in session
    if (!isset($_SESSION['_rate_limit'])) {
        $_SESSION['_rate_limit'] = [];
    }

    $key = "login_" . hash('sha256', $ip);
    $now = time();

    // Create or update rate limit entry
    if (!isset($_SESSION['_rate_limit'][$key])) {
        $_SESSION['_rate_limit'][$key] = [
            'count' => 1,
            'first_attempt' => $now,
            'last_attempt' => $now,
            'lockout_until' => 0
        ];
    } else {
        $_SESSION['_rate_limit'][$key]['count']++;
        $_SESSION['_rate_limit'][$key]['last_attempt'] = $now;
    }

    $attempts = $_SESSION['_rate_limit'][$key]['count'];

    // Log the failed attempt
    logError(
        "Failed login attempt",
        [
            'ip' => $ip,
            'attempts' => $attempts,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ],
        'warning'
    );

    return $attempts;
}

/**
 * Clear failed login attempts for an IP address (called on successful login)
 * 
 * @param string $ip The IP address (defaults to current client IP)
 * @return void
 */
function clearFailedAttempts(string $ip = ''): void
{
    if (empty($ip)) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    if (!isset($_SESSION['_rate_limit'])) {
        return;
    }

    $key = "login_" . hash('sha256', $ip);
    unset($_SESSION['_rate_limit'][$key]);
}

/**
 * Get remaining lockout time in seconds (0 if not locked out)
 * 
 * @param string $ip The IP address (defaults to current client IP)
 * @return int Remaining lockout time in seconds
 */
function getRateLimitRemaining(string $ip = ''): int
{
    if (empty($ip)) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    if (!isset($_SESSION['_rate_limit'])) {
        return 0;
    }

    $key = "login_" . hash('sha256', $ip);

    if (!isset($_SESSION['_rate_limit'][$key])) {
        return 0;
    }

    $data = $_SESSION['_rate_limit'][$key];
    $attempts = $data['count'];
    $now = time();
    $first_attempt = $data['first_attempt'];

    // Determine lockout duration based on attempts
    $lockout_duration = 0;
    if ($attempts >= 10) {
        $lockout_duration = 3600; // 60 minutes
    } elseif ($attempts >= 5) {
        $lockout_duration = 900; // 15 minutes
    } elseif ($attempts >= 3) {
        $lockout_duration = 300; // 5 minutes
    }

    $elapsed = $now - $first_attempt;

    if ($elapsed >= $lockout_duration) {
        return 0; // Not locked out anymore
    }

    return $lockout_duration - $elapsed; // Remaining lockout time
}

#endregion
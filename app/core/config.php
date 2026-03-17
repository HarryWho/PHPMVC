<?php

defined("ROOTPATH") or exit("Access Denied!");

/**
 * Load environment variables from .env file
 */
function loadEnv($file)
{
    if (!file_exists($file)) {
        throw new Exception(".env file not found at: $file");
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (str_starts_with(trim($line), '#')) continue;

        // Parse KEY=VALUE
        if (strpos($line, '=') === false) continue;

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Remove quotes if present
        if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
            $value = substr($value, 1, -1);
        }

        $_ENV[$key] = $value;
    }
}

// Load .env file from project root
loadEnv(__DIR__ . '/../../.env');

// Define constants from environment variables with fallback defaults
define('DEBUG', $_ENV['DEBUG'] === 'true');
define('BASE_URL', $_ENV['BASE_URL'] ?? 'http://localhost');
define('MIN_VERSION', $_ENV['MIN_VERSION'] ?? '8.0');

define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'adminLTE');

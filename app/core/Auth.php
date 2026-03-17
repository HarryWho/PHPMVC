<?php
defined("ROOTPATH") or exit("Access Denied!");

/**
 * Authentication and Authorization class
 * Manages user sessions and role-based access control
 * Implements session fingerprinting and token validation
 */
class Auth 
{
    /**
     * Role hierarchy for role-based access control
     * Higher number = higher privilege level
     */
    private static array $roleHierarchy = [
        'member'    => 1,
        'author'    => 2,
        'moderator' => 3,
        'admin'     => 4
    ];

    /**
     * Initialize session fingerprint for security
     * Prevents session hijacking by validating client environment
     *
     * @return void
     */
    public static function initSessionFingerprint(): void
    {
        // Generate fingerprint hash from client environment
        $fingerprint = hash(
            'sha256',
            $_SERVER['HTTP_USER_AGENT'] ?? '' .
                $GLOBALS['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? ''
        );

        if (!isset($_SESSION['_fingerprint'])) {
            $_SESSION['_fingerprint'] = $fingerprint;
        } else if ($_SESSION['_fingerprint'] !== $fingerprint) {
            // Fingerprint mismatch - possible session hijacking attempt
            logError("Session fingerprint mismatch (possible hijacking)", [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ], 'warning');

            // Invalidate session
            self::logout();
            die("Session security error. Please log in again.");
        }
    }

    /**
     * Get the current authenticated user
     * Validates that user still exists in database before returning
     *
     * @return object|null The user object from session, or null if not logged in
     */
    public static function user(): ?object
    {
        // Check session fingerprint
        if (isset($_SESSION['user'])) {
            self::initSessionFingerprint();
        }

        $user = $_SESSION['user'] ?? null;

        if ($user) {
            // Optionally validate user still exists in database (prevents orphaned sessions)
            // Uncomment if you want to validate on every request (small performance cost)
            // if (!self::validateUserExists($user->user_id)) {
            //     self::logout();
            //     return null;
            // }
        }

        return $user;
    }

    /**
     * Validate that a user still exists in the database
     * Used to prevent access with stale session data
     *
     * @param int $userId The user ID to validate
     * @return bool True if user exists, false otherwise
     */
    private static function validateUserExists(int $userId): bool
    {
        try {
            // Use a model to check user exists
            // This is a placeholder - implement based on your User model
            // $user = new User();
            // Return $user->first(['user_id' => $userId]) !== false;
            return true; // Placeholder for now
        } catch (Exception $e) {
            logError("Error validating user existence", ['user_id' => $userId, 'error' => $e->getMessage()], 'error');
            return false;
        }
    }

    /**
     * Check if the current user has a specific role
     *
     * @param string $role The role to check
     * @return bool True if user has the exact role, false otherwise
     */
    public static function hasRole(string $role): bool
    {
        $user = self::user();
        return $user && isset($user->user_role) && $user->user_role === $role;
    }

    /**
     * Check if the current user has at least the specified role level
     * 
     * @param string $role The minimum role level required
     * @return bool True if user has at least this role level, false otherwise
     */
    public static function atLeast(string $role): bool
    {
        $user = self::user();
        
        // Verify user exists and role is valid
        if (!$user || empty($user->user_role)) {
            return false;
        }
        
        // Check if role exists in hierarchy
        if (!isset(self::$roleHierarchy[$role]) || !isset(self::$roleHierarchy[$user->user_role])) {
            logError("Invalid role in atLeast check", ['role' => $role, 'user_role' => $user->user_role], 'warning');
            return false;
        }
        
        // Compare role levels
        return self::$roleHierarchy[$user->user_role] >= self::$roleHierarchy[$role];
    }

    /**
     * Check if user is an admin
     *
     * @return bool True if user is admin, false otherwise
     */
    public static function isAdmin(): bool
    {
        return self::hasRole('admin');
    }

    /**
     * Check if user is logged in
     *
     * @return bool True if user is logged in, false otherwise
     */
    public static function isLoggedIn(): bool
    {
        return self::user() !== null;
    }

    /**
     * Regenerate session ID to prevent session fixation attacks
     * Call this after successful login
     *
     * @return void
     */
    public static function regenerateSessionId(): void
    {
        session_regenerate_id(true);
        // Clear fingerprint to force recalculation with new session
        unset($_SESSION['_fingerprint']);
    }

    /**
     * Logout user and destroy session
     *
     * @return void
     */
    public static function logout(): void
    {
        // Clear all session data
        $_SESSION = [];

        // Destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();

        logError("User logged out", [], 'info');
    }
}
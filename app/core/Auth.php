<?php
defined("ROOTPATH") or exit("Access Denied!");

/**
 * Authentication and Authorization class
 * Manages user sessions and role-based access control
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
     * Get the current authenticated user
     *
     * @return object|null The user object from session, or null if not logged in
     */
    public static function user(): ?object
    {
        return $_SESSION['user'] ?? null;
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
}
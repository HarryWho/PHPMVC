<?php
defined("ROOTPATH") or exit("Access Denied!");

class Auth {
    private static $roleHierarchy = [
        'member'    => 1,
        'author'    => 2,
        'moderator' => 3,
        'admin'     => 4
    ];

    public static function user() {
        return $_SESSION['user'] ?? null;
    }

    public static function hasRole($role) {
        $user = self::user();
        return $user && $user->user_role === $role;
    }

    public static function atLeast($role) {
        $user = self::user();
        return $user && 
            self::$roleHierarchy[$user->user_role] >= self::$roleHierarchy[$role];
    }
}
<?php
defined("ROOTPATH") or exit("Access Denied!");

class Flash {
    public static function set($type, $message) {
        $_SESSION['flash'][$type] = $message;
    }

    public static function get($type) {
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }

    public static function all() {
        $messages = isset($_SESSION['flash']) ? $_SESSION['flash'] : [];
        unset($_SESSION['flash']);
        return $messages;
    }
}            
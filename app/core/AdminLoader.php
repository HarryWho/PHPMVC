<?php
defined("ROOTPATH") or exit("Access Denied!");

class AdminLoader{
    private static $adminFunctions = [
        'users' => [
            'include' => '../app/models/User.php',
            'class' => 'User'
        ]
    ];

    private static function returnUsers($cls): mixed
    {
        return $cls->findAll();
    }

    public static function loadFunction($func) : mixed
    {
        include_once self::$adminFunctions[$func]['include'];
        $this_class = new self::$adminFunctions[$func]['class'];
        switch($func){
            case 'users':
                return self::returnUsers($this_class);
                break;
            default:
                return [];
        }

    }
}
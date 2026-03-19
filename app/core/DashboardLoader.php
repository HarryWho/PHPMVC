<?php
defined("ROOTPATH") or exit("Access Denied!");

class DashboardLoader
{
    private static $dashboardFunctions = [
        'catagories' => [
            'include'=>'../app/models/Catagory.php',
            'class'=>'Catagory',
            'sql' =>   'SELECT catagories.*, users.user_id, users.user_name, users.user_image
                        FROM catagories
                        JOIN users ON catagories.catagory_authorId = users.user_id
                       '
        ]
    ];

    private static function catagories($cls, $func):array|bool
    {
         
        return $cls->join(self::$dashboardFunctions[$func]['sql']);
    }

    public static function loadDashboardFunction($func) : array|bool
    {
        require_once self::$dashboardFunctions[$func]['include'];
        $cls = new self::$dashboardFunctions[$func]['class'];
        return self::catagories($cls, $func);
    }
}

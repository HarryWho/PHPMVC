<?php
defined("ROOTPATH") or exit("Access Denied!");

class Messaging {
    private static $messageType = [
        'tasks'    => [
            'include' => '../app/models/Task.php',
            'owner_id' => 'task_id',
            'date_created' => 'task_createdAt',
            'class' => 'Task'
            ],
        'messages'    => [
            'include' => '../app/models/Message.php',
            'owner_id' => 'message_id',
            'date_created'=> 'message_createdAt',
            'class' => 'Message'
            ],

        'notifications' =>  [
            'include' => '../app/models/Notification.php',
            'owner_id' => 'notification_id',
            'date_created' => 'notification_createdAt',
            'class' => 'Notification'
            ],
        
    ];

    public static function sqlString($type) {
        return "SELECT * FROM $type WHERE " .self::$messageType[$type]['owner_id'] ." = :userID AND ". self::$messageType[$type]['date_created'] ." > :date_created";
    }

    public static function getMessageType($type, $data=[]) {
        require_once self::$messageType[$type]['include'];
       
        $msgClass = new self::$messageType[$type]['class'];
        $result = $msgClass->where($data);
        return $result;    

        
    }


}
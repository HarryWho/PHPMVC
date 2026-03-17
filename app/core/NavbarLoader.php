<?php
defined("ROOTPATH") or exit("Access Denied!");

class NavbarLoader
{
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
            'date_created' => 'message_createdAt',
            'class' => 'Message'
        ],

        'notifications' =>  [
            'include' => '../app/models/Notification.php',
            'owner_id' => 'notification_id',
            'date_created' => 'notification_createdAt',
            'class' => 'Notification'
        ],
        'users' => [
            'include' => '../app/models/User.php',

        ]

    ];

    private static function getMessagesSQL(): string
    {
        return  "SELECT messages.*, users.user_id, users.user_name, users.user_image
                FROM messages
                INNER JOIN users on messages.message_authorId = users.user_id
                WHERE messages.message_ownerId = :message_ownerId";
    }

    private static function getTaskSQL(): string
    {
        return  "SELECT tasks.*, users.user_id, users.user_name, users.user_image
                FROM tasks
                INNER JOIN users on tasks.task_authorId = users.user_id
                WHERE tasks.task_ownerId = :task_ownerId";
    }

    private static function getNotificationSQL(): string
    {
        return  "SELECT *
                FROM notifications
                WHERE notifications.notification_ownerId = :notification_ownerId";
    }

    public static function getUsers()
    {
        require_once self::$messageType['users']['include'];
        $my_users = new User;
        return $my_users->findAll();
    }

    public static function getMessageType($type, $data = [])
    {
        require_once self::$messageType[$type]['include'];

        $msgClass = new self::$messageType[$type]['class'];
        $sql = '';
        switch ($type) {
            case "tasks":
                $sql = self::getTaskSQL();
                break;
            case "messages":
                $sql = self::getMessagesSQL();
                break;
            case "notifications":
                $sql = self::getNotificationSQL();
                break;
        }

        $result = $msgClass->join($sql, $data);

        return $result;
    }
}

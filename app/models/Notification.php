<?php
defined("ROOTPATH") or exit("Access Denied!");

class Notification
{
    use Model;
  

    protected $table = 'notifications';
    protected $order_column = 'notification_id';
    protected $order_type = 'DESC';
    protected $allowedColumns = [
        'notification_ownerId',
        'notification_message'
    ];
 
}
<?php
defined("ROOTPATH") or exit("Access Denied!");

class Message
{
    use Model;


    protected $table = 'messages';
    protected $order_column = 'message_id';
    protected $order_type = 'DESC';
    protected int $limit = 10;
    protected int $offset = 0;
    protected $allowedColumns = [
        'message_ownerId',
        'message_authorId',
        'message_message',
        'message_createdAt'
    ];
 
}
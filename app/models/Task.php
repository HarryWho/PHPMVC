<?php
defined("ROOTPATH") or exit("Access Denied!");

class Task
{
    use Model;


    protected $table = 'tasks';
    protected $order_column = 'task_id';
    protected $order_type = 'DESC';
    protected int $limit = 10;
    protected int $offset = 0;
    protected $allowedColumns = [
        'task_ownerId',
        'task_authorId',
        'task_message',
        'task_createdAt'
    ];
}

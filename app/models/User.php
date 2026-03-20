<?php
defined("ROOTPATH") or exit("Access Denied!");

class User
{
    use Model;


    protected $table = 'users';
    protected $order_column = 'user_id';
    protected $order_type = 'DESC';
    protected int $limit = 100;
    protected int $offset = 0;
    protected $allowedColumns = [
        'user_name',
        'user_email',
        'user_password',
        'user_joinedAt',
        'user_last_login',
        'user_role'
    ];
}

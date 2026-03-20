<?php
defined("ROOTPATH") or exit("Access Denied!");

class Catagory
{
    use Model;


    protected string $table = 'catagories';
    protected string $order_column = 'catagory_id';
    protected string $order_type = 'DESC';
    protected int $limit = 10;
    protected int $offset = 0;
    protected array $allowedColumns = [

        'catagory_authorId',
        'catagory_title',
        'catagory_description'
    ];
}

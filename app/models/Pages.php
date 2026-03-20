<?php
defined("ROOTPATH") or exit("Access Denied!");

class Page
{
    use Model;


    protected string $table = 'pages';
    protected string $order_column = 'page_id';
    protected string $order_type = 'DESC';
    protected int $limit = 10;
    protected int $offset = 0;
    protected array $allowedColumns = [

        'page_ownerId',
        'page_catagoryId',
        'page_title',
        'page_description'
    ];
}

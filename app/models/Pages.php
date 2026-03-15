<?php
defined("ROOTPATH") or exit("Access Denied!");

class Page
{
    use Model;
  

    protected $table = 'pages';
    protected $order_column = 'page_id';
    protected $order_type = 'DESC';
    protected $allowedColumns = [
   
        'page_ownerId',
        'page_catagoryId',
        'page_title',
        'page_description'
    ];
 
}
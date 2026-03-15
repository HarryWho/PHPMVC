<?php
defined("ROOTPATH") or exit("Access Denied!");

class Catagory
{
    use Model;
  

    protected $table = 'catagories';
    protected $order_column = 'catagory_id';
    protected $order_type = 'DESC';
    protected $allowedColumns = [
   
        'catagory_authorId',
        'catagory_name',
        'catagory_description'
    ];
 
}
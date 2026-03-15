<?php
defined("ROOTPATH") or exit("Access Denied!");

class Blog
{
    use Model;
  

    protected $table = 'blogs';
    protected $order_column = 'blog_id';
    protected $order_type = 'DESC';
    protected $allowedColumns = [
        'blog_pageId',
        'blog_catagoryId',
        'blog_authorId',
        'blog_content',
        'blog_createdAt'
    ];
 
}
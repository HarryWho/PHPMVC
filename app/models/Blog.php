<?php
defined("ROOTPATH") or exit("Access Denied!");

class Blog
{
    use Model;


    protected string $table = 'blogs';
    protected string $order_column = 'blog_id';
    protected string $order_type = 'DESC';
    protected int $limit = 10;
    protected int $offset = 0;
    protected array $allowedColumns = [
        'blog_pageId',
        'blog_catagoryId',
        'blog_authorId',
        'blog_content',
        'blog_createdAt'
    ];
}

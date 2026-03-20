<?php
defined("ROOTPATH") or exit("Access Denied!");

class Notification
{
    use Model;


    protected string $table = 'notifications';
    protected string $order_column = 'notification_createdAt';
    protected string $order_type = 'DESC';
    protected int $limit = 5;
    protected int $offset = 0;

    protected array $allowedColumns = [
        'notification_ownerId',
        'notification_message',
        'notification_createdAt'
    ];
    public function OrderColumn(): string
    {
        return $this->order_column;
    }
    public function OrderType(): string
    {
        return $this->order_type;
    }
    public function Limit(): string
    {
        return $this->limit;
    }
    public function Offset(): string
    {
        return $this->offset;
    }
}
<?php

use Phalcon\Mvc\Model;

class Orders extends Model {
    public $order_id;
    public $order_name;
    public $order_surname;
    public $order_email;
    public $order_address;
    public $order_comment;
    public $order_sum;
    public $order_date;
    public $order_paid;
}

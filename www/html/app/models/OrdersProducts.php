<?php

use Phalcon\Mvc\Model;

class OrdersProducts extends Model {
    public $order_product_id;
    public $order_id;
    public $product_id;
    public $product_count;
}

<?php

use Phalcon\Mvc\Model;

class Products extends Model {
    public $product_id;
    public $product_price;
    public $product_title;
    public $product_description;
    public $product_photo;
    public $product_type;
}

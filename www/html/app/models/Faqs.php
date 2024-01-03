<?php

use Phalcon\Mvc\Model;

class Faqs extends Model {
    public $faq_id;
    public $faq_title;
    public $faq_description;
    public $faq_group_id;
    public $faq_position;
    public $faq_hidden;
}

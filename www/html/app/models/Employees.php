<?php

use Phalcon\Mvc\Model;

class Employees extends Model {
    public $employee_id;
    public $employee_name;
    public $employee_position;
    public $employee_hidden;
    public $employee_photo_location;
    public $employee_post;
    public $department_id;
}

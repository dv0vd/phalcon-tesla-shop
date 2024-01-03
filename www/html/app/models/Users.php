<?php

use Phalcon\Mvc\Model;

class Users extends Model {
    public $user_id;
    public $user_login;
    public $user_password;
    public $user_role;
}

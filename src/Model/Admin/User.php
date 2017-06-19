<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    public $timestamps = false;

    protected $table = "users";

    protected $connection = "admin";

    protected $fillable = [
        "id",
        "name",
        "login",
        "password",
    ];

    public function __construct() {

        parent::__construct();
    }

}

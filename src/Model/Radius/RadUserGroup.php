<?php

namespace App\Model\Radius;

use Illuminate\Database\Eloquent\Model;

class RadUserGroup extends Model {

    public $timestamps = false;

    protected $table = "radusergroup";
    
    protected $connection = "radius";

    public function __construct() {

        parent::__construct();
    }
}

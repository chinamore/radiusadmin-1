<?php

namespace App\Model\Radius;

use Illuminate\Database\Eloquent\Model;

class RadGroupCheck extends Model {

    public $timestamps = false;

    protected $table = "radgroupcheck";
    
    protected $connection = "radius";

    public function __construct() {

        parent::__construct();
    }
}

<?php

namespace App\Model\Radius;

use Illuminate\Database\Eloquent\Model;

class RadPostAuth extends Model {

    public $timestamps = false;

    protected $table = "radpostauth";
    
    protected $connection = "radius";

    public function __construct() {

        parent::__construct();
    }
}

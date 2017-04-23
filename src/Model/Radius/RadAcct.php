<?php

namespace App\Model\Radius;

use Illuminate\Database\Eloquent\Model;

class RadAcct extends Model {

    public $timestamps = false;

    protected $table = "radacct";
    
    protected $connection = "radius";

    public function __construct() {

        parent::__construct();
    }
}

<?php

namespace App\Model\Radius;

use Illuminate\Database\Eloquent\Model;

class RadCheck extends Model {

    public $timestamps = false;

    protected $table = "radcheck";

    protected $connection = "radius";

    public function __construct() {

        parent::__construct();
    }
}

<?php

namespace App\Model\Radius;

use Illuminate\Database\Eloquent\Model;

class RadReply extends Model {

    public $timestamps = false;

    protected $table = "radreply";
    
    protected $connection = "radius";

    public function __construct() {

        parent::__construct();
    }
}

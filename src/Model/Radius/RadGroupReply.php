<?php

namespace App\Model\Radius;

use Illuminate\Database\Eloquent\Model;

class RadGroupReply extends Model {

    public $timestamps = false;

    protected $table = "radgroupreply";
    
    protected $connection = "radius";

    public function __construct() {

        parent::__construct();
    }
}

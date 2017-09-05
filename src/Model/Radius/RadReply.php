<?php

namespace App\Model\Radius;

use Illuminate\Database\Eloquent\Model;

class RadReply extends Model {

    public $timestamps = false;

    protected $table = "radreply";
    
    protected $connection = "radius";

    protected $fillable = [
        "id",
        "username",
        "attribute",
        "op",
        "value"
    ];

    public function __construct() {

        parent::__construct();
    }
}

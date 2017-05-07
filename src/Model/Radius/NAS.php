<?php

namespace App\Model\Radius;

use Illuminate\Database\Eloquent\Model;

class NAS extends Model {

    public $timestamps = false;

    protected $table = "nas";
    
    protected $fillable = [
        "id",
        "nasname",
        "shortname",
        "type",
        "ports",
        "secret",
        "server",
        "community",
        "description"
    ];

    protected $connection = "radius";

    public function __construct() {

        parent::__construct();
    }
}

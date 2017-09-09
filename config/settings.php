<?php

return [
    "settings" => [

        "determineRouteBeforeAppMiddleware" => true,

        "displayErrorDetails" => true,

        "debug"=>true,

        "locale"=>"en_US",

        "encode"=>"UTF-8",

        "db_radius" => [
            "driver" => "mysql",
            "host" => "localhost",
            "database" => "radius",
            "username" => "root",
            "password" => "root",
            "charset"   => "utf8",
            "collation" => "utf8_unicode_ci",
            "prefix"    => "",
        ],

        "db_admin" => [
            "driver" => "mysql",
            "host" => "localhost",
            "database" => "admin",
            "username" => "root",
            "password" => "root",
            "charset"   => "utf8",
            "collation" => "utf8_unicode_ci",
            "prefix"    => "",
        ]
    ],
];

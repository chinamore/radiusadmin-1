<?php

return [
    "settings" => [
        "determineRouteBeforeAppMiddleware" => true,
        "displayErrorDetails" => true,
        "debug"=>true,
        "db" => [
            "driver" => "mysql",
            "host" => "localhost",
            "database" => "radius",
            "username" => "root",
            "password" => "root",
            "charset"   => "utf8",
            "collation" => "utf8_unicode_ci",
            "prefix"    => "",
        ]
    ],
];

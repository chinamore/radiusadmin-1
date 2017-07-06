<?php

if( PHP_SAPI === "cli" ) {

    $_SESSION = array();
}

session_start();

error_reporting(E_ALL);
ini_set("display_errors","On");
ini_set("display_startup_errors","On");

date_default_timezone_set("America/Sao_Paulo");

require "vendor/autoload.php";

$settings = require __DIR__ . "/config/settings.php";

$app = new \Slim\App( $settings );

$app->add( new \Slim\Csrf\Guard );

$app->add( new \Slim\Middleware\Session( [

    "name" => "RID",
    "autorefresh" => true,
    "lifetime" => "1 hour"
]));

require __DIR__ . "/config/dependencies.php";

require __DIR__ . "/config/routes.php";

$app->run();

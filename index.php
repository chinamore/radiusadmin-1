<?php

session_start();

error_reporting(E_ALL);
ini_set("display_errors","On");
ini_set("display_startup_errors","On");


require "vendor/autoload.php";

$settings = require __DIR__ . "/config/settings.php";

date_default_timezone_set( $settings["timezone"] );

putenv("LANGUAGE=" . $settings["locale"] );

setlocale( LC_ALL, $settings["locale"] . "." . $settings["encode"] );

bindtextdomain("radiusadmin", "./i18n/");

bind_textdomain_codeset( "radiusadmin", $settings["encode"] );

textdomain( "radiusadmin" );

$app = new \Slim\App( [ 
    
    "settings" => $settings 
]);

$app->add( new \Slim\Csrf\Guard );

require __DIR__ . "/config/dependencies.php";

require __DIR__ . "/config/routes.php";

$app->run();

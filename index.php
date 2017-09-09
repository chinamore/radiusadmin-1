<?php

session_start();

error_reporting(E_ALL);
ini_set("display_errors","On");
ini_set("display_startup_errors","On");

date_default_timezone_set("America/Sao_Paulo");

setlocale( LC_ALL, "en_US.UTF8" );

require "vendor/autoload.php";

bindtextdomain( "radiusadmin", "i18n" );

bind_textdomain_codeset( "radiusamdin", "UTF-8" );

textdomain( "radiusadmin" );

$settings = require __DIR__ . "/config/settings.php";

$app = new \Slim\App( $settings );

$app->add( new \Slim\Csrf\Guard );

require __DIR__ . "/config/dependencies.php";

require __DIR__ . "/config/routes.php";

$app->run();

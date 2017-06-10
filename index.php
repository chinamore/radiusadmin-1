<?php

session_start();

error_reporting(E_ALL);
ini_set("display_errors","On");
ini_set("display_startup_errors","On");

date_default_timezone_set("America/Sao_Paulo");

require "vendor/autoload.php";

$settings = require __DIR__ . "/config/settings.php";

$app = new \Slim\App( $settings );


$app->add(function ($request, $response, $next) {

    $response->getBody()->write('BEFORE');
    $response = $next($request, $response);
    $response->getBody()->write('AFTER');

    return $response;
});


require __DIR__ . "/config/dependencies.php";

require __DIR__ . "/config/routes.php";

$app->run();

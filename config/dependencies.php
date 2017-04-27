<?php

$container = $app->getContainer();

//twig templates
$container["view"] = function ($container) {

    $view = new \Slim\Views\Twig("src/View/", [
       
        //"cache" => "cache/"
    ]);
    
    $basePath = rtrim( str_ireplace( "index.php", "", $container["request"]->getUri()->getBasePath()), "/");
    
    $view->addExtension( new Slim\Views\TwigExtension($container["router"], $basePath) );

    $env = $view->getEnvironment();

    $env->addGlobal( "dir", "/radiusadmin" );

    return $view;
};

//eloquent orm
$container["db"] = function ($container) {
    
    $capsule = new \Illuminate\Database\Capsule\Manager;
    
    $capsule->addConnection($container["settings"]["db"], "radius");

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container["errorHandler"] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        return $container["response"]->withStatus(500)
            ->withHeader("Content-Type", "text/html")
            ->write( $exception );
    };
};

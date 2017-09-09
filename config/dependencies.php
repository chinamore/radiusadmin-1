<?php

$container = $app->getContainer();

//auth radius
$container["auth"] = function($container) {

    return new App\Auth\Radius\Auth();
};

//eloquent orm
$container["db"] = function ($container) {
    
    $capsule = new \Illuminate\Database\Capsule\Manager;
    
    $capsule->addConnection( $container["settings"]["db_radius"], "radius" );
    $capsule->addConnection( $container["settings"]["db_admin"], "admin" );

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

//init
$db = $container->get( "db" );


//twig templates
$container["view"] = function ($container) {

    $view = new \Slim\Views\Twig("src/View/", [
       
        //"cache" => "cache/"
    ]);
    
    $basePath = rtrim( str_ireplace( "index.php", "", $container["request"]->getUri()->getBasePath()), "/");
    
    $view->addExtension( new Slim\Views\TwigExtension($container["router"], $basePath ) );

    $view->addExtension( new Twig_Extensions_Extension_I18n() );

    $env = $view->getEnvironment();
    
    $env->addGlobal( "auth", [
    
        "check"=>$container->auth->check(),

        "user"=>$container->auth->user()
    ]);

    return $view;
};

$container["errorHandler"] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        return $container["response"]->withStatus(500)
            ->withHeader("Content-Type", "text/html")
            ->write( $exception );
    };
};

<?php

$app->map( ["GET"], "/", App\Controller\Radius\AppController::class . ":actionIndex" )
    ->setName( "index" );

$app->map( ["GET"], "/error", App\Controller\Radius\AppController::class . ":actionError" )
    ->setName( "error" );

$app->group("/users", function () {

    $this->map( ["GET"], "/[list]", App\Controller\Radius\UserController::class . ":actionList" )
        ->setName( "user_list" );

    $this->map( ["GET"], "/view", App\Controller\Radius\UserController::class . ":actionView" )
        ->setName( "user_view" );
    
    $this->map( ["POST"], "/store", App\Controller\Radius\UserController::class . ":actionStore" )
        ->setName( "user_stored" );
    
    $this->map( ["GET", "POST"], "/create", App\Controller\Radius\UserController::class . ":actionCreate" )
        ->setName( "user_create" );

    $this->map( ["GET", "POST"], "/update", App\Controller\Radius\UserController::class . ":actionUpdate" )
        ->setName( "user_update" );

    $this->map( ["GET", "POST"], "/delete", App\Controller\Radius\UserController::class . ":actionDelete" )
        ->setName( "user_delete" );

    $this->map( ["GET", "POST"], "/statistic", App\Controller\Radius\UserController::class . ":actionStatistic" )
        ->setName( "user_statistic" );
});

$app->group("/groups", function () {

    $this->map( ["GET"], "/[list]", App\Controller\Radius\GroupController::class . ":actionList" )
        ->setName( "group_list" );

    $this->map( ["GET"], "/view", App\Controller\Radius\GroupController::class . ":actionView" )
        ->setName( "group_view" );
    
    $this->map( ["POST"], "/store", App\Controller\Radius\GroupController::class . ":actionStore" )
        ->setName( "group_store" );

    $this->map( ["GET", "POST"], "/create", App\Controller\Radius\GroupController::class . ":actionCreate" )
        ->setName( "group_create" );

    $this->map( ["GET", "POST"], "/update", App\Controller\Radius\GroupController::class . ":actionUpdate" )
        ->setName( "group_update" );

    $this->map( ["GET", "POST"], "/delete", App\Controller\Radius\GroupController::class . ":actionDelete" )
        ->setName( "group_delete" );
});

$app->group("/statistics", function () {

    $this->map( ["GET"], "/[list]", App\Controller\Radius\StatisticController::class . ":actionList" )
        ->setName( "statistic_list" );
});

$app->group("/clients", function () {

    $this->map( ["GET"], "/[list]", App\Controller\Radius\ClientController::class . ":actionList" )
        ->setName( "client_list" );

    $this->map( ["POST"], "/store", App\Controller\Radius\ClientController::class . ":actionStore" )
        ->setName( "client_store" );

    $this->map( ["GET", "POST"], "/create", App\Controller\Radius\ClientController::class . ":actionCreate" )
        ->setName( "client_create" );

    $this->map( ["GET", "POST"], "/update", App\Controller\Radius\ClientController::class . ":actionUpdate" )
        ->setName( "client_update" );

    $this->map( ["GET", "POST"], "/delete", App\Controller\Radius\ClientController::class . ":actionDelete" )
        ->setName( "client_delete" );
});

$app->group("/json", function () {

    $this->group("/groups", function () {
        $this->map( ["GET", "POST"], "/[list]", App\Controller\Radius\GroupController::class . ":actionListJSON" )
            ->setName( "json_group_list" );

        $this->map( ["GET", "POST"], "/exist", App\Controller\Radius\GroupController::class . ":actionExistJSON" )
            ->setName( "json_group_exist" );
    });
});

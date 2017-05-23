<?php

$app->map( ["GET"], "/", App\Controller\Radius\AppController::class . ":actionIndex" );

$app->group("/users", function () {

    $this->map( ["GET"], "/[list]", App\Controller\Radius\UserController::class . ":actionList" );
    $this->map( ["POST"], "/store", App\Controller\Radius\UserController::class . ":actionStore" );
    
    $this->map( ["GET", "POST"], "/create", App\Controller\Radius\UserController::class . ":actionCreate" );
    $this->map( ["GET", "POST"], "/update", App\Controller\Radius\UserController::class . ":actionUpdate" );
    $this->map( ["GET", "POST"], "/delete", App\Controller\Radius\UserController::class . ":actionDelete" );
    $this->map( ["GET", "POST"], "/statistics", App\Controller\Radius\UserController::class . ":actionStatistic" );
});

$app->group("/groups", function () {

    $this->map( ["GET"], "/[list]", App\Controller\Radius\GroupController::class . ":actionList" );

    $this->map( ["GET", "POST"], "/create", App\Controller\Radius\GroupController::class . ":actionCreate" );
    $this->map( ["GET", "POST"], "/update", App\Controller\Radius\GroupController::class . ":actionUpdate" );
    $this->map( ["GET", "POST"], "/delete", App\Controller\Radius\GroupController::class . ":actionDelete" );
});

$app->group("/statistics", function () {

    $this->map( ["GET"], "/[list]", App\Controller\Radius\StatisticController::class . ":actionList" );
});

$app->group("/clients", function () {

    $this->map( ["GET"], "/[list]", App\Controller\Radius\ClientController::class . ":actionList" );
    $this->map( ["POST"], "/store", App\Controller\Radius\ClientController::class . ":actionStore" );

    $this->map( ["GET", "POST"], "/create", App\Controller\Radius\ClientController::class . ":actionCreate" );
    $this->map( ["GET", "POST"], "/update", App\Controller\Radius\ClientController::class . ":actionUpdate" );
    $this->map( ["GET", "POST"], "/delete", App\Controller\Radius\ClientController::class . ":actionDelete" );
});

$app->group("/json", function () {

    $this->group("/groups", function () {
        $this->map( ["GET", "POST"], "/[list]", App\Controller\Radius\GroupController::class . ":actionListJSON" );
        $this->map( ["GET", "POST"], "/exist", App\Controller\Radius\GroupController::class . ":actionExistJSON" );
    });
});

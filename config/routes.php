<?php

$app->map( ["GET"], "/", App\Controller\Radius\AppController::class . ":actionIndex" );

$app->group("/usuarios", function () {

    $this->map( ["GET"], "/[listar]", App\Controller\Radius\UserController::class . ":actionList" );
    $this->map( ["POST"], "/armazenar", App\Controller\Radius\UserController::class . ":actionStore" );
    
    $this->map( ["GET", "POST"], "/criar", App\Controller\Radius\UserController::class . ":actionCreate" );
    $this->map( ["GET", "POST"], "/alterar", App\Controller\Radius\UserController::class . ":actionUpdate" );
    $this->map( ["GET", "POST"], "/apagar", App\Controller\Radius\UserController::class . ":actionDelete" );
    $this->map( ["GET", "POST"], "/estatistica", App\Controller\Radius\UserController::class . ":actionStatistic" );
});

$app->group("/grupos", function () {

    $this->map( ["GET"], "/[listar]", App\Controller\Radius\GroupController::class . ":actionList" );

    $this->map( ["GET", "POST"], "/criar", App\Controller\Radius\GroupController::class . ":actionCreate" );
    $this->map( ["GET", "POST"], "/alterar", App\Controller\Radius\GroupController::class . ":actionUpdate" );
    $this->map( ["GET", "POST"], "/apagar", App\Controller\Radius\GroupController::class . ":actionDelete" );
});

$app->group("/estatisticas", function () {

    $this->map( ["GET"], "/[listar]", App\Controller\Radius\StatisticController::class . ":actionList" );
});

$app->group("/clientes", function () {

    $this->map( ["GET"], "/[listar]", App\Controller\Radius\ClientController::class . ":actionList" );
    $this->map( ["POST"], "/armazenar", App\Controller\Radius\ClientController::class . ":actionStore" );

    $this->map( ["GET", "POST"], "/criar", App\Controller\Radius\ClientController::class . ":actionCreate" );
    $this->map( ["GET", "POST"], "/alterar", App\Controller\Radius\ClientController::class . ":actionUpdate" );
    $this->map( ["GET", "POST"], "/apagar", App\Controller\Radius\ClientController::class . ":actionDelete" );
});

$app->group("/json", function () {

    $this->group("/grupos", function () {
        $this->map( ["GET", "POST"], "/[listar]", App\Controller\Radius\GroupController::class . ":actionListJSON" );
        $this->map( ["GET", "POST"], "/existe", App\Controller\Radius\GroupController::class . ":actionExistJSON" );
    });
});

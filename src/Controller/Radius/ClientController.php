<?php

namespace App\Controller\Radius;

use App\Controller\Controller;

class ClientController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionCreate( $request, $response ) {

        return $this->view->render( $response, "Radius/Client/create.html");
    }
   
    public function actionList( $request, $response ) {

        return $this->view->render( $response, "Radius/Client/list.html");
    }

    public function actionUpdate( $request, $response ) {

        return $this->view->render( $response, "Radius/Client/update.html");
    }

    public function actionDelete( $request, $response ) {

        return $this->view->render( $response, "Radius/Client/delete.html");
    }

}

<?php

namespace App\Controller\Radius;

use App\Controller\Controller;

class GroupController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionCreate( $request, $response ) {

        return $this->view->render( $response, "Radius/Group/create.html");
    }
   
    public function actionList( $request, $response ) {

        return $this->view->render( $response, "Radius/Group/list.html");
    }

    public function actionUpdate( $request, $response ) {

        return $this->view->render( $response, "Radius/Group/update.html");
    }

    public function actionDelete( $request, $response ) {

        return $this->view->render( $response, "Radius/Group/delete.html");
    }

}

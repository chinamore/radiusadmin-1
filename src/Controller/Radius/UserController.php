<?php

namespace App\Controller\Radius;

use App\Controller\Controller;

use App\Model\Radius\User;
use App\Model\Radius\Group;

class UserController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionCreate( $request, $response ) {
    
        return $this->view->render( $response, "Radius/User/create.html");
    }
   
    public function actionList( $request, $response ) {

        return $this->view->render( $response, "Radius/User/list.html");
    }

    public function actionUpdate( $request, $response ) {

        return $this->view->render( $response, "Radius/User/update.html");
    }

    public function actionDelete( $request, $response ) {

        return $this->view->render( $response, "Radius/User/delete.html");
    }

    private function getAttributesCheck( $userName = null) {
    
        $attributes = [];

      
        

        
    }







}

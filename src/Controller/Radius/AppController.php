<?php

namespace App\Controller\Radius;

use App\Controller\Controller;

class AppController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }
    
    public function actionIndex( $request, $response ) {

        return $this->view->render( $response, "Radius/App/index.html");
    }
}

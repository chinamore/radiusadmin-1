<?php

namespace App\Controller;

class AppController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }
    
    public function actionIndex( $request, $response ) {

        return $this->view->render( $response, "index.html");
    }
}

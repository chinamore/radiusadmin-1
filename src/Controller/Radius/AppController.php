<?php

namespace App\Controller\Radius;

use App\Controller\Controller;

class AppController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }
    
    public function actionIndex( $request, $response ) {
 
        return $this->redirect( $response, "user_list" );
    }

    public function actionError( $request, $response ) {
    
        $error = $request->getQueryParam( "error", "Ocorreu um erro interno" );

        return $this->view->render( $response, "Radius/App/error.html", [
            
            "error"=>$error
        ]);
    }

    public function actionLogin( $request, $response ) {

        return $this->view->render( $response, "Radius/App/login.html");
    }

}

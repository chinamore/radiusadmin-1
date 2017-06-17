<?php

namespace App\Controller\Radius;

use App\Controller\Controller;

use App\Auth\Radius\Auth;

class AuthController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionAuthenticate( $request, $response ) {
    
        $errors = [];

        if( $request->isPost() ) {
        
            $data = $request->getParsedBody();

            $auth = new Auth();

            if( $auth->attempt( $data["user"], $data["password"] ) ) {

                $_SESSION["auth"] = $data["user"];
        
                return $response->withRedirect( $this->router->pathFor( "index" ) );        
            }

            $errors["main"] = [ "Usuário e/ou senha incorretos" ];
        }

        return $this->view->render( $response, "Radius/Auth/authenticate.html", [
        
            "errors"=>$errors
        ]);
    }

}


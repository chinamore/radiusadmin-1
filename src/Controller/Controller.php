<?php

namespace App\Controller;

use \StdClass;

class Controller {

    private $view;
    private $db;
    private $router;
    
    public function __construct( $container ) {

        $this->view = $container->get( "view" );
        $this->db = $container->get( "db" );
        $this->router = $container->get( "router" );
        $this->auth = $container->get( "auth" );
    }

    public function __get( $atrib ) {
    
        return $this->$atrib;
    }

    public function redirect( $response, $routerName, $queryParam = [], $postData = [] ) {
    
        return $response->withRedirect( 
            
            $this->router->pathFor( $routerName, $postData, $queryParam  )
        ); 
    }

    public function getTokenCSRF( $request ) {
    
        $token = new StdClass();

        $token->name = $request->getAttribute( "csrf_name" );
        $token->value = $request->getAttribute( "csrf_value" );
    
        return $token;

    } 


}

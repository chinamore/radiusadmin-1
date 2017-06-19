<?php

namespace App\Middleware;

class Middleware {

    private $router;
    private $auth;
    
    public function __construct( $container ) {

        $this->router = $container->get( "router" );
        $this->auth = $container->get( "auth" );
    }

    public function __get( $atrib ) {
    
        return $this->$atrib;
    }
}

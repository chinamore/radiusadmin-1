<?php

namespace App\Middleware;

class Middleware {

    private $router;
    
    public function __construct( $container ) {

        $this->router = $container->get( "router" );
    }

    public function __get( $atrib ) {
    
        return $this->$atrib;
    }
}

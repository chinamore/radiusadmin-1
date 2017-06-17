<?php

namespace App\Middleware\Radius;

use App\Middleware\Middleware;

class AuthMiddleware extends Middleware {

    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function __invoke($request, $response, $next) {

        if( !isset( $_SESSION["auth"] ) ) {
 
            return $response->withRedirect( $this->router->pathFor( "authenticate" ) ); 
        }

        $response = $next($request, $response);

        return $response;
    }
}

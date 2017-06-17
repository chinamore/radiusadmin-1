<?php

namespace App\Controller;

class Controller {

    private $view;
    private $db;
    private $router;
    private $session;
    
    public function __construct( $container ) {

        $this->view = $container->get( "view" );
        $this->db = $container->get( "db" );
        $this->router = $container->get( "router" );
        $this->session = $container->get( "session" );
    }

    public function __get( $atrib ) {
    
        return $this->$atrib;
    }
}

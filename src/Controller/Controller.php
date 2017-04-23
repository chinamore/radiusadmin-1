<?php

namespace App\Controller;

class Controller {

    private $view;
    private $db;
    
    public function __construct( $container ) {

        $this->view = $container->get( "view" );
        $this->db = $container->get( "db" );
    }

    public function __get( $atrib ) {
    
        return $this->$atrib;
    }
}

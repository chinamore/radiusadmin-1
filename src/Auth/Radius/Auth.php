<?php

namespace App\Auth\Radius;

use App\Auth\iAuth;

class Auth implements iAuth {

    public function attempt( $user, $password ) {
        
        if( $user == "paulo" && $password == "paulo" ) {
        
            return true;
        }

        return false;
    }
}

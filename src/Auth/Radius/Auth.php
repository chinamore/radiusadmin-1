<?php

namespace App\Auth\Radius;

use App\Auth\iAuth;
use App\Model\Admin\User;

class Auth implements iAuth {

    public function attempt( $login, $password ) {
        
        $user = User::where( "login", $login )->first();

        if( $user == null ) {
            
            return false;
        }

        if( password_verify( $password, $user->password ) ) {
 
            $_SESSION["auth_id"] = $user->id;

            return true;
        }

        return false;
    }

    public function check() {
    
        return isset( $_SESSION["auth_id"] );
    }

    public function user() {
    
        if( $this->check() ) {
        
            return User::find( $_SESSION["auth_id"] );
        }
    
        return null;
    }

    public function logout() {
    
        unset( $_SESSION["auth_id"] );
    }

}

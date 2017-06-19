<?php

namespace App\Auth;

interface iAuth {

    public function attempt( $user, $password );

    public function check();
    
    public function user();        

    public function logout();
 
}

<?php

namespace App\Auth;

interface iAuth {

    public function attempt( $user, $password );
 
}

<?php

namespace App\Controller\Radius;

use App\Controller\Controller;

use App\Model\Radius\User;
use App\Model\Radius\Group;
use App\Model\Radius\RadCheck;

class UserController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionCreate( $request, $response ) {
 
        $groups = Group::getAll();

        $operators = RadCheck::getOperators();

        return $this->view->render( $response, "Radius/User/create.html", [

            "groups" => $groups,
            "operators"=>$operators
        ]);
    }
   
    public function actionList( $request, $response ) {

        $name = $request->getQueryParam( "nome", "" );

        $attribute = $request->getQueryParam( "atributo", "" );

        $page = ( int ) $request->getQueryParam( "pagina", 0 );
    
        if( $page < 0 ) {
                     
            $page = 0;
        }

        $take = 50;

        $skip = $take * $page; 

        $users = User::find( $name, $attribute, $skip, $take );

        return $this->view->render( $response, "Radius/User/list.html", [
        
            "users"=>$users,
            "name"=>$name,
            "attribute"=>$attribute,
            "page"=>$page,
        ]);
    }

    public function actionUpdate( $request, $response ) {


        $name = $request->getQueryParam( "nome", "" );

        $groups = Group::getAll();

        $user = User::get( $name );


        $operators = Radcheck::getOperators();

        return $this->view->render( $response, "Radius/User/update.html", [
            
            "user"=>$user,
            "groups" => $groups,
            "operators"=>$operators
        ]);
    }

    public function actionDelete( $request, $response ) {

        return $this->view->render( $response, "Radius/User/delete.html");
    }

    private function getAttributesCheck( $userName = null) {
    
        $attributes = [];

      
        

        
    }







}

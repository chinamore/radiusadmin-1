<?php

namespace App\Controller\Radius;

use App\Controller\Controller;
use App\Model\Radius\Group;
use App\Model\Radius\RadCheck;
use \StdClass;

class GroupController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionCreate( $request, $response ) {

        $operators = RadCheck::getOperators();

        return $this->view->render( $response, "Radius/Group/create.html", [
 
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

        $groups = Group::find( $name, $attribute, $skip, $take );

        return $this->view->render( $response, "Radius/Group/list.html", [
        
            "groups"=>$groups,
            "name"=>$name,
            "attribute"=>$attribute,
            "page"=>$page,
        ]);
    }

    public function actionUpdate( $request, $response ) {

        $name = $request->getQueryParam( "name", "" );
        
        $group = Group::get( $name );

        $operators = RadCheck::getOperators();

        return $this->view->render( $response, "Radius/Group/update.html", [
 
            "group"=>$group,
            "operators"=>$operators
        ]);
    }

    public function actionJSON( $request, $response ) {
        
        $json = [];

        $query = $request->getQueryParam( "query", "" );

            
            $groups = Group::find( $query, "", 0, 10 );
        
            foreach( $groups as $group ) {
        
                $obj = new StdClass();
                $obj->label = $group->name;
                $obj->value = $group->name;
            
                $json[] = $obj;
            }

        $response->getBody()->write(  json_encode( $json ) );
    }

    public function actionDelete( $request, $response ) {

        return $this->view->render( $response, "Radius/Group/delete.html");
    }

}

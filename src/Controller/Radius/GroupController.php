<?php

namespace App\Controller\Radius;

use App\Controller\Controller;
use App\Model\Radius\Group;
use App\Model\Radius\RadCheck;

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

        $name = $request->getQueryParam( "name", "" );

        $attribute = $request->getQueryParam( "attribute", "" );

        $groups = Group::find( $name, $attribute );


        return $this->view->render( $response, "Radius/Group/list.html", [
        
            "groups"=>$groups
        ]);

        return $this->view->render( $response, "Radius/Group/list.html");
    }

    public function actionUpdate( $request, $response ) {

        $group = Group::get( "grupo1" );

        $operators = RadCheck::getOperators();

        return $this->view->render( $response, "Radius/Group/update.html", [
 
            "group"=>$group,
            "operators"=>$operators
        ]);
    }

    public function actionDelete( $request, $response ) {

        return $this->view->render( $response, "Radius/Group/delete.html");
    }

}

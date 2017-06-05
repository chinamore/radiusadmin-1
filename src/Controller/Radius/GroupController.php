<?php

namespace App\Controller\Radius;

use App\Controller\Controller;
use App\Model\Radius\Group;
use App\Model\Radius\RadCheck;
use App\Model\Radius\RadGroupCheck;
use App\Model\Radius\RadGroupReply;
use \StdClass;

class GroupController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionCreate( $request, $response ) {

        if( $request->isPost() ) {
            
            return $this->actionStore( $request, $response );
        }

        $operators = RadCheck::getOperators();

        return $this->view->render( $response, "Radius/Group/create.html", [
 
            "operators"=>$operators
        ]);
    }
 
    public function actionStore( $request, $response ) {
    
        $data = $request->getParsedBody();

        if( isset( $data["name"] ) && strlen( trim( $data["name"] ) ) > 0 ) {

            $name = $data["name"];

            $checks = [];

            if( isset( $data["attributes-check"] ) &&
                isset( $data["operators-check"] ) &&
                isset( $data["values-check"] ) ) {
            
                $checks = $this->createAttributesCheck( $name,
                    $data["attributes-check"],
                    $data["operators-check"],
                    $data["values-check"] );           
            }

            $replies = [];

            if( isset( $data["attributes-reply"] ) &&
                isset( $data["operators-reply"] ) &&
                isset( $data["values-reply"] ) ) {
            
                $replies = $this->createAttributesReply( $name,
                    $data["attributes-reply"],
                    $data["operators-reply"],
                    $data["values-reply"] );           
            }

            if(  count( $checks ) > 0 || count( $replies ) > 0 ) {
        
                $group = new Group( $name, $checks, $replies );
   
                $group->save();

                return $this->view->render( $response, "Radius/Group/view.html", [

                    "group"=>$group
                ]);
            }
        }   

        $errors = [
            "main"=>[
                "Você deve preencher o campo nome e no minimo um atributo."
            ]
        ];

        $operators = Radcheck::getOperators();

        return $this->view->render( $response, "Radius/Group/create.html", [

            "operators"=>$operators,
            "errors"=>$errors
        ]);    
    }

    public function actionList( $request, $response ) {

        $name = $request->getQueryParam( "name", "" );

        $attribute = $request->getQueryParam( "attribute", "" );

        $page = ( int ) $request->getQueryParam( "page", 0 );
    
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

    public function actionListJSON( $request, $response ) {
        
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

    public function actionExistJSON( $request, $response ) {
        
        $name = $request->getQueryParam( "name", "" );
        
        $group = Group::get( $name );

        $obj = new StdClass();
        
        $obj->result = true;

        if( $group == null ) {
            
            $obj->result = false;
        }

        $response->getBody()->write(  json_encode( $obj ) );
    }

    public function actionDelete( $request, $response ) {
    
        $name = $request->getParam( "name", null );
 
        $group = Group::get( $name );
    
        if( $group == null ) {
        
            return $this->view->render( $response, "Radius/App/error.html", [
            
                "errors"=>[ "Grupo não encontrado." ]
            ]);
        }

        if( $request->isPost() ) { 

            $group->delete();
        
            return $this->actionList( $request, $response );
        }
         
        return $this->view->render( $response, "Radius/Group/delete.html", [
        
            "group"=>$group
        ]);
    }

    private function createAttributesCheck( $groupName, $attributes, $operators, $values ) {
        
        $checks = [];

        $qtAttributesCheck = max( count( $attributes ), count( $operators ), count( $values ) );

        for( $i = 0; $i < $qtAttributesCheck; $i++ ) {
            
            if( !isset( $attributes[$i] ) || empty( $attributes[$i] ) ||
                !isset( $operators[$i] ) || empty( $operators[$i] ) ||
                !isset( $values[$i] ) || empty( $values[$i] )  ) {
            
                continue;
            }

            $check = new RadGroupCheck();
            $check->groupName = $groupName;
            $check->attribute = $attributes[$i];
            $check->op = $operators[$i];
            $check->value = $values[$i];

            $checks[] = $check;
        }
   
        return $checks;
    }

    private function createAttributesReply( $groupName, $attributes, $operators, $values ) {
        
        $replies = [];

        $qtAttributesReply = max( count( $attributes ), count( $operators ), count( $values ) );

        for( $i = 0; $i < $qtAttributesReply; $i++ ) {
            
            if( !isset( $attributes[$i] ) || empty( $attributes[$i] ) ||
                !isset( $operators[$i] ) || empty( $operators[$i] ) ||
                !isset( $values[$i] ) || empty( $values[$i] )  ) {
            
                continue;
            }

            $reply = new RadGroupReply();
            $reply->groupname = $groupName;
            $reply->attribute = $attributes[$i];
            $reply->op = $operators[$i];
            $reply->value = $values[$i];

            $replies[] = $reply;
        }
   
        return $replies;
    }


}

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

    public function actionView( $request, $response ) {
    
        $name = $request->getQueryParam( "name", null );

        $group = Group::get( $name );

        if( $group == null ) {
            
            return $this->redirect( $response, "error", [
            
                "error"=>"Grupo não encontrado"
            ]);       
        }

        return $this->view->render( $response, "Radius/Group/view.html",[
            
            "group"=>$group
        ]);
    }

    public function actionCreate( $request, $response ) {
 
        $group = Group::create();

        $errors = [];

        if( $request->isPost() ) {
            
            $group = $this->createGroup( $request->getParsedBody() );

            if( $group !== null && $group->save() ) {

                return $this->redirect( $response, "group_view", [
                 
                    "name"=>$group->name
                ]); 
            }

            $errors = [ "main"=> [ 
            
                "Erro, você deve preencher o nome e no mínimo um atributo" 
            ] ]; 
        }

        $operators = RadGroupCheck::getOperators();

        return $this->view->render( $response, "Radius/Group/create.html", [

            "group"=>$group,
            "operators"=>$operators,
            "errors"=>$errors
        ]);
    }

    public function actionUpdate( $request, $response ) {

        $name = $request->getQueryParam( "name", "" );

        $group = Group::get( $name );

        if( $group === null ) {
        
            return $this->redirect( $response, "error", [
                 
                "error"=>"Grupo não encontrado"
            ]); 
        }

        $errors = [];

        if( $request->isPost() ) {
            
            $newGroup = $this->createGroup( $request->getParsedBody() );

            if( $newGroup !== null && $newGroup->save( $group->name ) ) {

                return $this->redirect( $response, "group_view", [
                 
                    "name"=>$newGroup->name
                ]); 
            }

            $errors = [ "main"=> [ 
            
                "Erro, você deve preencher o nome e no mínimo um atributo" 
            ] ]; 
        }

        $operators = RadGroupcheck::getOperators();

        return $this->view->render( $response, "Radius/Group/update.html", [
            
            "group"=>$group,
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
        
        $obj = new StdClass();
        
        $obj->result = Group::exists( $name );

        $response->getBody()->write(  json_encode( $obj ) );
    }

    public function actionDelete( $request, $response ) {
    
        $name = $request->getQueryParam( "name", null );
 
        $group = Group::get( $name );
    
        if( $group === null ) {
           
            return $this->redirect( $response, "error" , [
            
                "error"=>"Grupo não encontrado"
            ]);
        }

        if( $request->isPost() ) { 

            $group->delete();
            
            return $this->redirect( $response, "group_list" );
        }
         
        return $this->view->render( $response, "Radius/Group/delete.html", [
        
            "group"=>$group
        ]);
    }

    private function createAttributesCheck( $data ) {
        
        $checks = [];

        if( isset( $data["name"] ) && 
            !empty( trim( $data["name"] ) ) && 
            isset( $data["attributes-check"] ) ) {
            
            $qtChecks = count( $data["attributes-check"] );
    
            for( $i = 0; $i < $qtChecks; $i++ ) {
                
                if( isset( $data["attributes-check"][$i] ) &&
                    !empty( trim( $data["attributes-check"][$i] ) ) &&
                    isset( $data["operators-check"][$i] ) && 
                    !empty( trim( $data["operators-check"][$i] ) ) &&
                    isset( $data["values-check"][$i] ) && 
                    !empty( trim( $data["values-check"][$i] ) ) ) {
                       
                    $check = new RadGroupCheck();
                    $check->groupname = $data["name"];
                    $check->attribute = $data["attributes-check"][$i];
                    $check->op = $data["operators-check"][$i];
                    $check->value = $data["values-check"][$i];

                    $checks[] = $check;                   
                }
            }
        }
  
        return $checks;
    }

    private function createAttributesReply( $data ) {
        
        $replies = [];

        if( isset( $data["name"] ) && 
            !empty( trim( $data["name"] ) ) && 
            isset( $data["attributes-reply"] ) ) {
            
            $qtChecks = count( $data["attributes-reply"] );
    
            for( $i = 0; $i < $qtChecks; $i++ ) {
                
                if( isset( $data["attributes-reply"][$i] ) &&
                    !empty( trim( $data["attributes-reply"][$i] ) ) &&
                    isset( $data["operators-reply"][$i] ) && 
                    !empty( trim( $data["operators-reply"][$i] ) ) &&
                    isset( $data["values-reply"][$i] ) && 
                    !empty( trim( $data["values-reply"][$i] ) ) ) {
                       
                    $reply = new RadGroupReply();
                    $reply->groupname = $data["name"];
                    $reply->attribute = $data["attributes-reply"][$i];
                    $reply->op = $data["operators-reply"][$i];
                    $reply->value = $data["values-reply"][$i];

                    $replies[] = $reply;                   
                }
            }
        }
  
        return $replies;
    }

     private function createGroup( $data ) {
   
        if( empty( $data["name"] ) ) {
        
            return null;
        }

        $name = $data["name"];

        $checks = $this->createAttributesCheck( $data );           
           
        $replies = $this->createAttributesReply( $data );

        return new Group( $name, $checks, $replies );
    } 
   
}

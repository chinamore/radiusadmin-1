<?php

namespace App\Controller\Radius;

use App\Controller\Controller;

use App\Model\Radius\User;
use App\Model\Radius\Group;
use App\Model\Radius\RadCheck;
use App\Model\Radius\RadReply;
use App\Model\Radius\RadAcct;

use \DateTime;

class UserController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionView( $request, $response ) {
    
        $name = $request->getQueryParam( "name", null );

        $user = User::get( $name );

        if( $user === null ) {
        
            return $this->redirect( $response, "error", [
                 
                "error"=>"Usuário não encontrado"
            ]); 
        }

        return $this->view->render( $response, "Radius/User/view.html",[
            
            "user"=>$user
        ]);
    }

    public function actionCreate( $request, $response ) {
        
        $user = User::create();

        $errors = [];

        if( $request->isPost() ) {
            
            $user = $this->createUser( $request->getParsedBody() );

            if( $user !== null && $user->save() ) {

                return $this->redirect( $response, "user_view", [
                 
                    "name"=>$user->name
                ]); 
            }

            $errors = [ "main"=> [ 
            
                "Erro, você deve preencher o nome e no mínimo um atributo" 
            ] ]; 
        }

        $token = $this->getTokenCSRF( $request );
        
        $groups = Group::getAll();

        $operators = RadCheck::getOperators();

        return $this->view->render( $response, "Radius/User/create.html", [

            "token"=>$token,
            "user"=>$user,
            "groups"=>$groups,
            "operators"=>$operators,
            "errors"=>$errors
        ]);
    }
   
    public function actionUpdate( $request, $response ) {

        $name = $request->getQueryParam( "name", "" );

        $user = User::get( $name );

        if( $user === null ) {
        
            return $this->redirect( $response, "error", [
                 
                "error"=>"Usuário não encontrado"
            ]); 
        }

        $errors = [];

        if( $request->isPost() ) {
            
            $newUser = $this->createUser( $request->getParsedBody() );

            if( $newUser !== null && $newUser->save( $user->name ) ) {

                return $this->redirect( $response, "user_view", [
                 
                    "name"=>$newUser->name
                ]); 
            }

            $errors = [ "main"=> [ 
            
                "Erro, você deve preencher o nome e no mínimo um atributo" 
            ] ]; 
        }

        $token = $this->getTokenCSRF( $request );

        $groups = Group::getAll();

	$operators = Radcheck::getOperators();

        return $this->view->render( $response, "Radius/User/update.html", [

            "token"=>$token,
            "user"=>$user,
            "groups"=>$groups,
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

        $users = User::find( $name, $attribute, $skip, $take );

        return $this->view->render( $response, "Radius/User/list.html", [
        
            "users"=>$users,
            "name"=>$name,
            "attribute"=>$attribute,
            "page"=>$page,
        ]);
    }

    public function actionDelete( $request, $response ) {
    
        $name = $request->getQueryParam( "name", null );
 
        $user = User::get( $name );
    
        if( $user === null ) {
           
            return $this->redirect( $response, "error" , [
            
                "error"=>"Usuário não encontrado"
            ]);
        }

        if( $request->isPost() ) { 

            $user->delete();
            
            return $this->redirect( $response, "user_list" );
        }
         
        $token = $this->getTokenCSRF( $request );

        return $this->view->render( $response, "Radius/User/delete.html", [
        
            "token"=>$token,
            "user"=>$user
        ]);
    }

    public function actionStatistic( $request, $response ) {
 
        $userName = $request->getQueryParam( "name", "" );
        
        $mac = $request->getQueryParam( "mac", "" );
        
        $nas = $request->getQueryParam( "nas", "" );
        
        $page = $request->getQueryParam( "page", 0 );
        
        $date1 = $request->getQueryParam( "date1", null );
        
        $date2 = $request->getQueryParam( "date2", null );
        
        $devices = RadAcct::select(  "callingstationid as mac"  )
            ->where( "username", $userName )
            ->distinct()
            ->get();
 
        $clients = RadAcct::select(  "nasipaddress"  )
            ->where( "username", $userName )
            ->distinct()
            ->get();
 
        $qtAccts = $this->getQueryBaseUserStatistic( $userName, $mac, $nas, $date1, $date2 )
            ->count();

        $timeAvg = $this->getQueryBaseUserStatistic( $userName, $mac, $nas, $date1, $date2 )
            ->avg( "acctsessiontime" );

        $uploadAvg = $this->getQueryBaseUserStatistic( $userName, $mac, $nas, $date1, $date2 )
            ->avg( "acctinputoctets" );

        $downloadAvg = $this->getQueryBaseUserStatistic( $userName, $mac, $nas, $date1, $date2 )
            ->avg( "acctoutputoctets" );

        $take = 50;

        $skip = $take * $page; 

        $radAccts = $this->getQueryBaseUserStatistic( $userName, $mac, $nas, $date1, $date2 )
            ->orderBy( "acctstarttime", "desc" )
            ->skip( $skip )
            ->take( $take )
            ->get();

        return $this->view->render( $response, "Radius/User/statistic.html", [

            "userName"=>$userName,
            "mac"=>$mac,
            "nas"=>$nas,
            "date1"=>$date1,
            "date2"=>$date2,
            "page"=>$page,
            "qtAccts"=>$qtAccts,
            "devices"=>$devices,
            "clients"=>$clients,
            "timeAvg"=>$timeAvg,
            "uploadAvg"=>$uploadAvg,
            "downloadAvg"=>$downloadAvg,
            "radAccts"=>$radAccts
        ]);
    }

    
    private function createUser( $data ) {
   
        if( empty( $data["name"] ) ) {
        
            return null;
        }

        $name = $data["name"];

        $checks = $this->createAttributesCheck( $data );           
           
        $replies = $this->createAttributesReply( $data );
        
        $groups = $this->createGroups( $data );

        return new User( $name, $checks, $replies, $groups );
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
                       
                    $check = new RadCheck();
                    $check->username = $data["name"];
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
                       
                    $reply = new RadReply();
                    $reply->username = $data["name"];
                    $reply->attribute = $data["attributes-reply"][$i];
                    $reply->op = $data["operators-reply"][$i];
                    $reply->value = $data["values-reply"][$i];

                    $replies[] = $reply;                   
                }
            }
        }
  
        return $replies;
    }

    private function createGroups( $data ) {
    
        $groups = [];

        if( isset( $data["groups"] ) ) {
        
            foreach( $data["groups"] as $groupName ) {
        
                $group = Group::get( $groupName );

                if( $group !== null ) {

                    $groups[] = $group;
                }
            }
        }

        return $groups;
    }

    private function getQueryBaseUserStatistic( $name, $mac, $nas, $date1, $date2 ) {
    
         $query = RadAcct::where( "username", $name )
            ->where( "callingstationid", "like", "%$mac%" )
            ->where( "nasipaddress", "like", "%$nas%" );

        if( !empty( $date1 ) ) {
 
            $date = DateTime::createFromFormat( "d/m/Y", $date1 );

            $query = $query->where( "acctstarttime", ">=", $date->format( "Y-m-d" ) );
        }

        if( !empty( $date2 ) ) {
         
            $date = DateTime::createFromFormat( "d/m/Y", $date2 );

            $query = $query->where( "acctstarttime", "<=", $date->format( "Y-m-d" ) );
        }
  
        return $query;
    }
}

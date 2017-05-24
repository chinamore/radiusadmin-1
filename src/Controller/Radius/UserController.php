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

    public function actionCreate( $request, $response ) {
 
        if( $request->isPost() ) {
            
            return $this->actionStore( $request, $response );
        }

        $groups = Group::getAll();

        $operators = RadCheck::getOperators();

        return $this->view->render( $response, "Radius/User/create.html", [

            "groups" => $groups,
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

            $groups = [];

            if( isset( $data["groups"] ) ) {

                $groups = $this->createGroups( $data["groups"] );
            }

            if( ( count( $checks ) > 0 || count( $replies ) > 0 ) ) {
        
                $user = new User( $name, $checks, $replies, $groups );
   
                $user->save();

                return $this->view->render( $response, "Radius/User/view.html", [

                    "user"=>$user
                ]);
            }
        }

        $errors = [
            "main"=>[
                "Você deve preencher o campo nome e no minimo um atributo."
            ]
        ];

        $groups = Group::getAll();

        $operators = Radcheck::getOperators();

        return $this->view->render( $response, "Radius/User/create.html", [

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

    public function actionUpdate( $request, $response ) {

        $name = $request->getQueryParam( "name", "" );

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

    public function actionStatistic( $request, $response ) {
 
        $userName = $request->getQueryParam( "userName", "" );
        
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

    private function createAttributesCheck( $userName, $attributes, $operators, $values ) {
        
        $checks = [];

        $qtAttributesCheck = max( count( $attributes ), count( $operators ), count( $values ) );

        for( $i = 0; $i < $qtAttributesCheck; $i++ ) {
            
            if( !isset( $attributes[$i] ) || empty( $attributes[$i] ) ||
                !isset( $operators[$i] ) || empty( $operators[$i] ) ||
                !isset( $values[$i] ) || empty( $values[$i] )  ) {
            
                continue;
            }

            $check = new RadCheck();
            $check->username = $userName;
            $check->attribute = $attributes[$i];
            $check->op = $operators[$i];
            $check->value = $values[$i];

            $checks[] = $check;
        }
   
        return $checks;
    }

    private function createAttributesReply( $userName, $attributes, $operators, $values ) {
        
        $replies = [];

        $qtAttributesReply = max( count( $attributes ), count( $operators ), count( $values ) );

        for( $i = 0; $i < $qtAttributesReply; $i++ ) {
            
            if( !isset( $attributes[$i] ) || empty( $attributes[$i] ) ||
                !isset( $operators[$i] ) || empty( $operators[$i] ) ||
                !isset( $values[$i] ) || empty( $values[$i] )  ) {
            
                continue;
            }

            $reply = new RadReply();
            $reply->username = $userName;
            $reply->attribute = $attributes[$i];
            $reply->op = $operators[$i];
            $reply->value = $values[$i];

            $replies[] = $reply;
        }
   
        return $replies;
    }

    private function createGroups( $groupsName ) {
    
        $groups = [];

        foreach( $groupsName as $groupName ) {
        
            $group = Group::get( $groupName );

            if( $group != null ) {

                $groups[] = $group;
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

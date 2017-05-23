<?php

namespace App\Controller\Radius;

use App\Controller\Controller;

use App\Model\Radius\User;
use App\Model\Radius\Group;
use App\Model\Radius\RadCheck;
use App\Model\Radius\RadAcct;

use \Sirius\Validation\Validator;
use \DateTime;

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
   
    public function actionStore( $request, $response ) {
    
        $data = $request->getParsedBody();

        $validator = new Validator();

        $validator->add( [

            "name"=>"required | maxlength(64)",
        ]);

        $validator->validate( $data );

        $errors = $validator->getMessages();

        $name = $data["name"];

        return $this->view->render( $response, "Radius/User/create.html", [
            "name"=>$name,         
            "errors"=>$errors
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

    public function actionStatistic( $request, $response ) {
 
        $userName = $request->getQueryParam( "nome", "" );
        
        $mac = $request->getQueryParam( "mac", "" );
        
        $nas = $request->getQueryParam( "nas", "" );
        
        $page = $request->getQueryParam( "pagina", 0 );
        
        $date1 = $request->getQueryParam( "data1", null );
        
        $date2 = $request->getQueryParam( "data2", null );
        
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

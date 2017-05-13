<?php

namespace App\Controller\Radius;

use App\Controller\Controller;

use App\Model\Radius\User;
use App\Model\Radius\Group;
use App\Model\Radius\RadCheck;
use App\Model\Radius\RadAcct;

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
 
        $name = $request->getQueryParam( "nome", "" );
        
        $mac = $request->getQueryParam( "mac", "" );
        
        $nas = $request->getQueryParam( "nas", "" );
        
        $page = $request->getQueryParam( "pagina", 0 );
        
        $date1 = $request->getQueryParam( "data1", null );
        
        $date2 = $request->getQueryParam( "data2", null );
        
        $user = User::get( $name );

        $take = 50;

        $skip = $take * $page; 

        $radAccts = $this->getQueryBaseUserStatistic( $name, $mac, $nas, $date1, $date2 )
            ->orderBy( "acctstarttime", "desc" )
            ->skip( $skip )
            ->take( $take )
            ->get();

        $qtAccts = $this->getQueryBaseUserStatistic( $name, $mac, $nas, $date1, $date2 )
            ->count();
    
        $qtMacs = $this->getQueryBaseUserStatistic( $name, $mac, $nas, $date1, $date2 )
            ->distinct()
            ->count(  "callingstationid"  );
        
        $timeAvg = $this->getQueryBaseUserStatistic( $name, $mac, $nas, $date1, $date2 )
            ->avg( "acctsessiontime" );

        $uploadAvg = $this->getQueryBaseUserStatistic( $name, $mac, $nas, $date1, $date2 )
            ->avg( "acctinputoctets" );

        $downloadAvg = $this->getQueryBaseUserStatistic( $name, $mac, $nas, $date1, $date2 )
            ->avg( "acctoutputoctets" );


        return $this->view->render( $response, "Radius/User/statistic.html", [

            "user"=>$user,
            "mac"=>$mac,
            "nas"=>$nas,
            "date1"=>$date1,
            "date2"=>$date2,
            "page"=>$page,
            "qtAccts"=>$qtAccts,
            "qtMacs"=>$qtMacs,
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

<?php

namespace App\Controller\Radius;

use App\Controller\Controller;
use App\Model\Radius\NAS;
use \Sirius\Validation\Validator;

class ClientController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionCreate( $request, $response ) {

        if( $request->isPost() ) {
            
            return $this->actionStore( $request, $response );
        }

        return $this->view->render( $response, "Radius/Client/create.html" );
    }
    
    public function actionStore( $request, $response ) {
    
        $data = $request->getParsedBody();

        $nas = ( isset( $data["nas"] ) ) ? $data["nas"] : [];

        $validator = new Validator();

        $validator->add( [

            "nasname"=>"required | maxlength(128)",
            "shortname"=>"maxlength(32)",
            "type"=>"maxlength(30)",
            "ports"=>"integer",
            "secret"=>"required | maxlength(60)",
            "server"=>"maxlength(64)",
            "community"=>"maxlength(50)",
            "description"=>"maxlength(200)"
        ]);

        if( $validator->validate( $nas ) ) {
        
            $client = new NAS();

            $client->fill( $nas );            
            
            if( $client->save() ) {
            
                return $this->view->render( $response, "Radius/Client/view.html", [
            
                    "client"=>$client
                ]);           
            }
        }

        $errors = $validator->getMessages();

        return $this->view->render( $response, "Radius/Client/create.html", [
            "nas"=>$nas,         
            "errors"=>$errors
        ]);
    } 


    public function actionList( $request, $response ) {

        $nasName = $request->getQueryParam( "nome", "" );

        $shortName = $request->getQueryParam( "apelido", "" );
        
        $type = $request->getQueryParam( "tipo", "" );

        $page = ( int ) $request->getQueryParam( "pagina", 0 );
    
        if( $page < 0 ) {
                     
            $page = 0;
        }

        $take = 50;

        $skip = $take * $page; 

        $clients = NAS::where( "nasname", "like", "%" . $nasName . "%" )
            ->where( "shortname", "like", "%" . $shortName . "%" )
            ->where( "type", "like", "%" . $type . "%" )
            ->skip( $skip )
            ->take( $take )
            ->get();

        return $this->view->render( $response, "Radius/Client/list.html", [
        
            "clients"=>$clients,
            "nasName"=>$nasName,
            "shortName"=>$shortName,
            "type"=>$type,
            "page"=>$page,
        ]);
    }

    public function actionUpdate( $request, $response ) {

        return $this->view->render( $response, "Radius/Client/update.html");
    }

    public function actionDelete( $request, $response ) {

        return $this->view->render( $response, "Radius/Client/delete.html");
    }

}

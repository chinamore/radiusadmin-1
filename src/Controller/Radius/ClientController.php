<?php

namespace App\Controller\Radius;

use App\Controller\Controller;
use App\Model\Radius\NAS;

class ClientController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionCreate( $request, $response ) {

        $client = new NAS();

        $data = $request->getParsedBody();
        
        if( isset( $data["nas"] ) ) {
        
            $client->fill( $data["nas"] );       

            if( $client->save() ) {
            
                return $this->view->render( $response, "Radius/Client/view.html", [
            
                    "client"=>$client
                ]);           
            }
        }
        
        return $this->view->render( $response, "Radius/Client/create.html", [
            
            "client"=>$client
        ]);
    }
   
    public function actionList( $request, $response ) {

        return $this->view->render( $response, "Radius/Client/list.html");
    }

    public function actionUpdate( $request, $response ) {

        return $this->view->render( $response, "Radius/Client/update.html");
    }

    public function actionDelete( $request, $response ) {

        return $this->view->render( $response, "Radius/Client/delete.html");
    }

}

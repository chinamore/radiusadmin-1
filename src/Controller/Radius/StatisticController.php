<?php

namespace App\Controller\Radius;

use App\Controller\Controller;

class StatisticController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionList( $request, $response ) {

        return $this->view->render( $response, "Radius/Statistic/list.html");
    }
}

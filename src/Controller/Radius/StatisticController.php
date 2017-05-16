<?php

namespace App\Controller\Radius;

use App\Controller\Controller;
use \DateTime;
use \DateInterval;

class StatisticController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionList( $request, $response ) {


        return $this->view->render( $response, "Radius/Statistic/list.html");
    }

    private function getDatesPointsToChart( $date1, $date2 ) {
    
        $days = [];
        
        $date1Work = clone $date1;
            
        $dayYear1 = $date1->format("z");
        $dayYear2 = $date2->format("z");

        if( ( $dayYear2 - $dayYear1 ) >= 9  ) {

            $increment = round( ( $dayYear2 - $dayYear1 ) / 9 ) ;
            
            $days[] = $date1Work->format("Y-m-d");

            for( $i = 0; $i < 8; $i++ ) {
        
                $date1Work->add( new DateInterval( "P${increment}D"));
            
                $days[] = $date1Work->format("Y-m-d");
            }

            $days[] = $date2->format("Y-m-d");
        
        }else {
        
            for(; $dayYear1 <= $dayYear2; $dayYear1++ ) {
                                
                $days[] = $date1Work->format("Y-m-d");
  
                $date1Work->add( new DateInterval( "P1D"));
            }
        }

        return $days;
    }
}

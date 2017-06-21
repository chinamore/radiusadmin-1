<?php

namespace App\Controller\Radius;

use App\Controller\Controller;
use App\Model\Radius\RadAcct;
use App\Model\Radius\RadPostAuth;
use \DateTime;
use \DateInterval;

class StatisticController extends Controller {
    
    public function __construct( $container ) {
    
        parent::__construct( $container );
    }

    public function actionList( $request, $response ) { 

        
        $textDate1 = $request->getQueryParam( "date1", date( "01/m/Y" ) );

        $textDate2 = $request->getQueryParam( "date2", date( "d/m/Y" ) );
        
        $date1 = Datetime::createFromFormat( "d/m/Y", $textDate1 );
        $date2 = Datetime::createFromFormat( "d/m/Y", $textDate2 );

        $datesTextSummary = $this->getDatesSummary( $date1, $date2 );

        $connectionsSummary = [];
        $usersSummary = [];

        foreach( $datesTextSummary as $date ) {
             
            $summaryDate1 = Datetime::createFromFormat( "d/m/Y", $date );

            $summaryDate2 = clone $summaryDate1;
            
            $summaryDate2->add( new DateInterval( "P1D" ) );

            $connectionsSummary[] = $this->countConnections( $summaryDate1, $summaryDate2 );
            $usersSummary[] = $this->countUsers( $summaryDate1, $summaryDate2 );
        }

        $lastAuthsOk = $this->getAuthsOk( $date1, $date2, 0, 5 );
        
        $lastAuthsError = $this->getAuthsError( $date1, $date2, 0, 5 );
        
        $qtUsers = $this->countUsers( $date1, $date2 );

        $qtAccts = $this->countConnections( $date1, $date2 );

        $timeAvg = $this->avgTimeConnections( $date1, $date2 );

        $uploadAvg = $this->avgUploadConnections( $date1, $date2 );

        $downloadAvg = $this->avgDownloadConnections( $date1, $date2 );
      
        return $this->view->render( $response, "Radius/Statistic/list.html", [
           
            "date1"=>$textDate1,
            "date2"=>$textDate2,
            "qtAccts"=>$qtAccts,
            "qtUsers"=>$qtUsers,
            "timeAvg"=>$timeAvg,
            "uploadAvg"=>$uploadAvg,
            "downloadAvg"=>$downloadAvg,
            "datesTextSummary"=>$datesTextSummary,
            "connectionsSummary"=>$connectionsSummary,
            "usersSummary"=>$usersSummary,
            "lastAuthsOk"=>$lastAuthsOk,
            "lastAuthsError"=>$lastAuthsError
        ]);
    }

    private function getDatesSummary( $date1, $date2 ) {
    
        $days = [];
        
        $date1Work = clone $date1;
            
        $dayYear1 = (int) $date1->format( "z" );
        $dayYear2 = (int) $date2->format( "z" );
        
        $qtDays = $dayYear2 - $dayYear1;
        
        if( $qtDays == 0 || $qtDays == 1 ) {
            
            $date1Work->sub( new DateInterval( "P1D" ) );
            $days[] = $date1Work->format( "d/m/Y" );


            $date1Work->add( new DateInterval( "P1D" ) );
            $days[] = $date1Work->format( "d/m/Y" );


            $date1Work->add( new DateInterval( "P1D" ) );
            $days[] = $date1Work->format( "d/m/Y" );

            return $days;
        }    
        
        if( $qtDays < 9 ) {
        
             for(; $dayYear1 <= $dayYear2; $dayYear1++ ) {
                                
                $days[] = $date1Work->format( "d/m/Y" );
  
                $date1Work->add( new DateInterval( "P1D") );
            }
        
            return $days;
        }

        
        $increment = round( ( $dayYear2 - $dayYear1 ) / 9 ) ;
            
        $days[] = $date1Work->format( "d/m/Y" );

        for( $i = 0; $i < 8; $i++ ) {
        
            $date1Work->add( new DateInterval( "P${increment}D") );
            
            $days[] = $date1Work->format( "d/m/Y" );
        }

        $days[] = $date2->format( "d/m/Y" );
        
        return $days;
    }

    private function countConnections( $date1, $date2 ) {
       
        return RadAcct::where( "acctstarttime", ">=", $date1->format( "Y-m-d" ) )
            ->where( "acctstarttime", "<", $date2->format( "Y-m-d" ) )
            ->count();

    }

    private function countUsers( $date1, $date2 ) {
        
         //is this a better way?
        $raw = RadAcct::where( "acctstarttime", ">=", $date1->format( "Y-m-d" ) )
            ->where( "acctstarttime", "<", $date2->format( "Y-m-d" ) )
            ->selectRaw( "count( distinct username ) as qtUsers")
            ->first();
    
        return $raw->qtUsers;
     
     }

    private function avgTimeConnections( $date1, $date2 ) {

        return RadAcct::where( "acctstarttime", ">=", $date1->format( "Y-m-d" ) )
            ->where( "acctstarttime", "<", $date2->format( "Y-m-d" ) )
            ->avg( "acctsessiontime" );
     }

     private function avgUploadConnections( $date1, $date2 ) {

        return RadAcct::where( "acctstarttime", ">=", $date1->format( "Y-m-d" ) )
            ->where( "acctstarttime", "<", $date2->format( "Y-m-d" ) )
            ->avg( "acctinputoctets" );
     }

     private function avgDownloadConnections( $date1, $date2 ) {

        return RadAcct::where( "acctstarttime", ">=", $date1->format( "Y-m-d" ) )
            ->where( "acctstarttime", "<", $date2->format( "Y-m-d" ) )
            ->avg( "acctoutputoctets" );
     }

    private function getAuthsOk( $date1, $date2, $skip = null , $take = null ) {
    
        $query = RadPostAuth::where( "authdate", ">=", $date1->format( "Y-m-d" ) )
            ->where( "authdate", "<", $date2->format( "Y-m-d" ) )
            ->where( "reply", "like", "%accept%" )
            ->orderBy( "id", "desc");
            
        if( $skip !== null )     
            $query->skip( $skip );

        if( $take !== null )
            $query->take( $take );

        return $query->get();
    }

    private function getAuthsError( $date1, $date2, $skip = null , $take = null ) {
    
        $query = RadPostAuth::where( "authdate", ">=", $date1->format( "Y-m-d" ) )
            ->where( "authdate", "<", $date2->format( "Y-m-d" ) )
            ->where( "reply", "like", "%reject%" )
            ->orderBy( "id", "desc");
            
        if( $skip !== null )     
            $query->skip( $skip );

        if( $take !== null )
            $query->take( $take );

        return $query->get();
    }

}

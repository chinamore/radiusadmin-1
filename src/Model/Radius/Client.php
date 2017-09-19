<?php

namespace App\Model\Radius;

use Illuminate\Database\Capsule\Manager;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

class Client {

    private $nas;

    private $validator;

    public function __construct( $nas = null ) {

        $this->nas = new NAS();
        
        if( $nas !== null ) {
        
            $this->nas = $nas;
        }

    }   

    public function __get( $propertie ) {
    
        return $this->nas->$propertie;
    }

    public function __set( $propertie, $value ) {
    
        $this->nas->$propertie = $value;
    }

    public function __isset( $propertie ) {

        return isset( $this->nas->$propertie );
    }
       
    public function validate() {

        return Validator::ip()->validate( $this->nasname ) &&
            Validator::intVal()->validate( $this->ports );
    }

    public function save( $oldName = null ) {
        
        if( $this->validate() ) {

            $this->nas->save();

            return true;
        }

        return false;
    }

    public function delete() {
        
        $this->nas->delete();
    }

    public function fill( $data ) {
   
        $this->nas->fill( $data );    
    }
   
    public static function create() {
   
        return new Client();
    }
           
    public static function exists( $nasName ) {
    
        $qt = NAS::where( "nasname", $nasName )->count();
        
        return $qt > 0 ; //only $qt 
    }    
 
    public static function getId( $id ) {
        
        $nas = NAS::find( $id );
    
        if( $nas !== null ) {
        
            return new Client( $nas ) ;
        } 

        return null;
    }
        
    public static function get( $nasName ) {
 
        if( empty( $nasName ) ) {
            
            return null;
        }

        $nas = NAS::where( "nasname", $name )->first();

        if( $nas !== null ) {
        
            return  new Client( $nas );
        }
        
        return null;
    }    
 
    public static function getAll() {
    
        $clients = [];

        $nases = NAS::all();
        
        foreach( $nases as $nas ) {
        
            $clients[] = new Client( $nas );
        }

        return $clients;
    }

    public static function getTypes() {

        return [
            "cisco",
            "computone",
            "livingstone",
            "juniper",
            "max40xx",
            "multitech",
            "netserver",
            "pathras",
            "patton",
            "portslave",
            "tc",
            "usrhiper",
            "other"
        ];
    }
   
}

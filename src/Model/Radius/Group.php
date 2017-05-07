<?php

namespace App\Model\Radius;

class Group {
   
    private $properties = [
    
        "name" => null,
        "attributesCheck" => [],
        "attributesReply" => []
    ];

    public function __construct( $name, 
        $attributesCheck = [], 
        $attributesReply = [] ) {

        $this->name = $name;

        $this->attributesCheck = $attributesCheck;
        $this->attributesReply = $attributesReply;
    }

    public function __get( $propertie ) {
    
        return $this->properties[ $propertie ];
    }

    public function __set( $propertie, $value ) {
    
        return $this->properties[ $propertie ] = $value;
    }

    public function __isset( $propertie ) {

        return isset( $this->properties[ $propertie ] );
    }

    public function save() {
    
        foreach( $this->attributesCheck as $check ) {
        
            $check->save();
        } 
        
        foreach( $this->attributesReply as $reply ) {
        
            $reply->save();
        }        
    }

    public function delete() {
    
         foreach( $this->attributesCheck as $check ) {
        
            $check->delete();
        } 
        
        foreach( $this->attributesReply as $reply ) {
        
            $reply->delete();
        }        

        RadUserGroup::where( "groupname", $this->name )->delete();  
    }

    private static function sortByName( $attributesCheck, $attributesReply ) {
    
        $attributes = [];
 
        foreach( $attributesCheck as $check ) {
       
            if( !isset( $attributes[ $check->groupname ] ) ) {
            
                $attributes[ $check->groupname ] = [];
            }

            if( !isset( $attributes[ $check->groupname ][ "check"] )  ) {
            
                $attributes[ $check->groupname ][ "check" ] = [];
            }

            $attributes[ $check->groupname ][ "check" ][] = $check;
        }
    
        foreach( $attributesReply as $reply ) {

            if( !isset( $attributes[ $reply->groupname ] ) ) {
            
                $attributes[ $reply->groupname ] = [];
            }

            if( !isset( $attributes[ $reply->groupname ][ "reply"] )  ) {
            
                $attributes[ $reply->groupname ][ "reply" ] = [];
            }

           $attributes[ $reply->groupname ][ "reply" ][] = $reply;
        }   
    
        return $attributes;
    }


    public static function getAll() {
    
        $groups = [];

        $attributesCheck = RadGroupCheck::all();
        
        $attributesReply = RadGroupReply::all();       

        $attributes = self::sortByName( $attributesCheck, $attributesReply );

        foreach( $attributes as $groupName => $attribute ) {

            $checks = ( isset( $attribute[ "check" ] ) ) ? $attribute[ "chekck" ] : [];
            
            $replies = ( isset( $attribute[ "reply" ] ) ) ? $attribute[ "reply" ] : [];
    
            $groups[] = new Group( $groupName, $checks, $replies );
        }

        return $groups;
    }

    public static function get( $name ) {
    
        $attributesCheck = RadGroupCheck::where( "groupname", $name )->get();
        
        $attributesReply = RadGroupReply::where( "groupname", $name )->get();
       
        return new Group( $name, $attributesCheck, $attributesReply );
    }

}

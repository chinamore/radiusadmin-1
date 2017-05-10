<?php

namespace App\Model\Radius;

class User {

    private $properties = [
    
        "name" => null,
        "attributesCheck" => [],
        "attributesReply "=> [],
        "groups" => []
    ];

    public function __construct( $name, 
        $attributesCheck = [], 
        $attributesReply = [],
        $groups = [] ) {

        $this->name = $name;

        $this->attributesCheck = $attributesCheck;
        $this->attributesReply = $attributesReply;

        $this->groups = $groups;
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
    
        RadUserGroup::where( "username", $this->name )->delete();
        
        foreach( $this->groups as $index => $group ) {
        
            $group->save();

            $radUserGroup = new RadUserGroup();

            $radUserGroup->priority = $index + 1;

            $radUserGroup->username = $this->name;

            $radUserGroup->groupname = $group->groupName;

            $radUserGroup->save();
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
       
            if( !isset( $attributes[ $check->username ] ) ) {
            
                $attributes[ $check->username ] = [];
            }

            if( !isset( $attributes[ $check->username ][ "check"] )  ) {
            
                $attributes[ $check->username ][ "check" ] = [];
            }

            $attributes[ $check->username ][ "check" ][] = $check;
        }
    
        foreach( $attributesReply as $reply ) {

            if( !isset( $attributes[ $reply->username ] ) ) {
            
                $attributes[ $reply->username ] = [];
            }

            if( !isset( $attributes[ $reply->username ][ "reply"] )  ) {
            
                $attributes[ $reply->username ][ "reply" ] = [];
            }

           $attributes[ $reply->username ][ "reply" ][] = $reply;
        }   
    
        return $attributes;
    }

    public static function find( $name, $attribute = "" ) {
        
        $users = [];

        $name = "%" . $name . "%";

        $attribute = "%" . $attribute . "%";

        $attributesCheck = RadCheck::where( "username", "like", $name  )
            ->where( "attribute", "like", $attribute )
            ->get();
 
        $attributesReply = RadReply::where( "username", "like", $name )
            ->where( "attribute", "like", $attribute )
            ->get();

        $attributes = self::sortByName( $attributesCheck, $attributesReply );

        foreach( $attributes as $userName => $attribute ) {
        
            $checks = ( isset( $attribute[ "check" ] ) ) ? $attribute[ "check" ] : [];
            $replies = ( isset( $attribute[ "reply" ] ) ) ? $attribute[ "reply" ] : [];

            $users[] = new User( $userName, $checks, $replies );
        }
        
        return $users;
    }


    public static function get( $name ) {
    
        $checks = RadCheck::where( "username", $name )->get();
        
        $replies = RadReply::where( "username", $name )->get();
        
        $groups = self::loadGroups( $name );

        return new User( $name, $checks, $replies, $groups );
    }    
 
    public static function getAll() {
    
        $users = [];

        $attributesCheck = RadCheck::all();
        
        $attributesReply = RadReply::all();
   
        $attributes = self::sortByName( $attributesCheck, $attributesReply );

        foreach( $attributes as $userName => $attribute ) {
        
            $checks = ( isset( $attribute[ "check" ] ) ) ? $attribute[ "check" ] : [];
            $replies = ( isset( $attribute[ "reply" ] ) ) ? $attribute[ "reply" ] : [];

            $users[] = new User( $userName, $checks, $replies );
        }
        
        return $users;
    }

    private static function loadGroups( $userName ) {
    
        $groups = [];

        $userGroups = RadUserGroup::where( "username", $userName )
            ->orderBy( "priority", "asc" )
            ->get();
    
        foreach( $userGroups as $userGroup ) {
        
            $groups[] = Group::get( $userGroup->groupname );        
        }

        return $groups;
    }

}

<?php

namespace App\Model\Radius;

use Illuminate\Database\Capsule\Manager;

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
       
    public function validate() {
    
        if( empty( trim( $this->name ) ) ) {
            
            return false;
        }
    
        if( empty( $this->attributesCheck ) && empty( $this->attributesReply ) ) {
        
            return false;
        }

        //add test groups

        return true;
    }

    public function save( $oldName = null ) {
        
        if( $this->validate() ) {

            if( $oldName !== null ) {
        
                $this->deleteToUpdate( $oldName ); 
            }

            $this->delete();

            foreach( $this->attributesCheck as $check ) {
        
                $check->save();
            }    
        
            foreach( $this->attributesReply as $reply ) {
        
                $reply->save();
            }
     
            foreach( $this->groups as $index => $group ) {

                $radUserGroup = new RadUserGroup();

                $radUserGroup->priority = $index + 1;

                $radUserGroup->username = $this->name;

                $radUserGroup->groupname = $group->name;

                $radUserGroup->save();
            }

            return true;
        }

        return false;
    }

    public function delete() {
        
        RadCheck::where( "username", $this->name )->delete();
        RadReply::where( "username", $this->name )->delete();
        RadUserGroup::where( "username", $this->name )->delete();
    }

    public function fill( $data ) {
   
        foreach( $this->properties as $key => $value ) {
        
            if( isset( $data[ $key ] ) ) {
            
                $this->proprerties[ $key ] = $data[ $key ];
            }
        }
    }
   
    public static function create() {
   
        return new User( null, [], [], [] );
    }
           
    public static function exists( $name ) {
    
        $qtChecks = RadCheck::where( "username", $name )->count();
        
        $qtReplies = RadReply::where( "username", $name )->count();
        
        return ( $qtChecks + $qtReplies ) > 0 ;
    }    
   
    public static function find( $name, $attribute = "", $skip = null, $take = 10 ) {
        
        $users = [];

        $name = "%" . $name . "%";

        $attribute = "%" . $attribute . "%";

        $queryRadcheck = RadCheck::select( "username" )
            ->where( "username", "like", $name )
            ->where( "attribute", "like", $attribute )
            ->distinct();

        $query = RadReply::select( "username" )
            ->where( "username", "like", $name )
            ->where( "attribute", "like", $attribute )
            ->union( $queryRadcheck )
            ->distinct()
            ->orderBy( "username", "asc" );
           
        if( $skip != null ) {
        
            $query = $query->skip( $skip );
        }
    
        if( $take != null ) {
            
            $query = $query->take( $take );
        }

        $names = $query->get();

        $attributesCheck = RadCheck::whereIn( "username", $names  )
            ->get();
 
        $attributesReply = RadReply::whereIn( "username", $names )
            ->get();

        $attributes = self::sortByName( $attributesCheck, $attributesReply );

        foreach( $attributes as $userName => $attribute ) {
        
            $checks = ( isset( $attribute[ "check" ] ) ) ? $attribute[ "check" ] : [];
            $replies = ( isset( $attribute[ "reply" ] ) ) ? $attribute[ "reply" ] : [];
            
            $groups = self::loadGroups( $userName );

            $users[] = new User( $userName, $checks, $replies, $groups );
        }
     
        return $users;
    }

    public static function get( $name ) {
 
        if( empty( $name ) ) {
            
            return null;
        }

        $checks = RadCheck::where( "username", $name )->get();
        
        $replies = RadReply::where( "username", $name )->get();
        
        if( count( $checks ) <= 0 && count( $replies ) <= 0 ) {
        
            return null;
        }

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

            $groups = self::loadGroups( $userName );
            
            $users[] = new User( $userName, $checks, $replies, $groups );
        }
       
        return $users;
    }

    private function deleteToUpdate( $name ) {
    
        $user = self::get( $name );

        if( $user !== null ) {
        
            $user->delete();
        }       
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

}

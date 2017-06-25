<?php

namespace Tests\Functional\Radius;

use Tests\Functional\BaseTestCase;

use App\Model\Radius\User;
use App\Model\Radius\Group;
use App\Model\Radius\RadCheck;
use App\Model\Radius\RadReply;

class UserControllerTest extends BaseTestCase {
    
    public function testActionList() {

        $response = $this->runApp( "GET", "/protected/users/list");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Listar usuário", (string) $response->getBody());
        $this->assertNotContains( "Ver usuário", (string) $response->getBody());

        $response = $this->runApp( "GET", "/protected/users/list?name=paulo" );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "paulo", (string) $response->getBody() );
        $this->assertNotContains( "iago", (string) $response->getBody() );


        $response = $this->runApp( "GET", "/protected/users/list?name=paulo&attribute=password");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "paulo", (string) $response->getBody() );
        $this->assertNotContains( "iago", (string) $response->getBody() );
    }
    
    public function testActionCreate() {

        $response = $this->runApp( "GET", "/protected/users/create");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Criar usuário", (string) $response->getBody());
        $this->assertNotContains( "Ver usuário", (string)$response->getBody());

        $response = $this->runApp( "POST", "/protected/users/create", [
        
            "name"=>date("zhmi"),
            "groups"=>[

                "grupo1"                   
            ],
            "attributes-check"=>[ "Auth-Type" ],
            "operators-check"=>[ ":=" ],
            "values-check"=>[ "Accept" ],
            "attributes-reply"=>[ "Session-Timeout" ],
            "operators-reply"=>[ ":=" ],
            "values-reply"=>[ "7200" ],

        ]);

        $this->assertEquals( 302, $response->getStatusCode() );
        $this->assertContains( "/users/view", (string) $response->getHeaderLine("Location") );

        $response = $this->runApp( "POST", "/protected/users/create", [] );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Criar usuário", (string) $response->getBody() );
        $this->assertNotContains( "Ver usuário", (string) $response->getBody() );
    }

    public function testActionDelete() {

        $response = $this->runApp( "GET", "/protected/users/delete?name=nao-existe-" . date("zhmi"));

        $this->assertEquals( 302, $response->getStatusCode() );
        $this->assertContains( "error", (string) $response->getHeaderLine("Location") );

        $userName = ( "existe-" . date("zhmi") );
        
        $check = new RadCheck();
        $check->username = $userName;
        $check->attribute = "Simultaneous-Use";
        $check->op = ":=";
        $check->value = "1";

        $reply = new RadReply();
        $reply->username = $userName;
        $reply->attribute = "Session-Timeout";
        $reply->op = ":=";
        $reply->value = "7200";

        $group = Group::get( "grupo1" );

        $user = new User( $userName, [ $check ], [ $reply ], [ $group ] );

        $user->save();

        $response = $this->runApp( "GET", "/protected/users/delete?name=${userName}");
 
        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Apagar usuário", (string) $response->getBody() );
        $this->assertNotContains( "Listar usuário", (string) $response->getBody() );
 
        $response = $this->runApp( "POST", "/protected/users/delete?name=${userName}");

        $this->assertEquals( 302, $response->getStatusCode() );
        $this->assertContains( "users/list", (string) $response->getHeaderLine("Location") );
 
    }

}

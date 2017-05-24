<?php

namespace Tests\Functional\Radius;

use Tests\Functional\BaseTestCase;

class UserControllerTest extends BaseTestCase {
    
    public function testActionList() {

        $response = $this->runApp( "GET", "/users/list");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Listar usuário", (string) $response->getBody());
        $this->assertNotContains( "Ver usuário", (string) $response->getBody());

        $response = $this->runApp( "GET", "/users/list?name=paulo" );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "paulo", (string) $response->getBody() );
        $this->assertNotContains( "iago", (string) $response->getBody() );


        $response = $this->runApp( "GET", "/users/list?name=paulo&attribute=password");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "paulo", (string) $response->getBody() );
        $this->assertNotContains( "iago", (string) $response->getBody() );
    }
    
    public function testActionCreate() {

        $response = $this->runApp( "GET", "/users/create");

        //$this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Criar usuário", (string) $response->getBody());
        $this->assertNotContains( "Ver usuário", (string)$response->getBody());

        $response = $this->runApp( "POST", "/users/create", [
        
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

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Ver usuário", (string) $response->getBody() );
        $this->assertNotContains( "Criar usuário", (string) $response->getBody() );

        $response = $this->runApp( "POST", "/users/create", [] );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Criar usuário", (string) $response->getBody() );
        $this->assertNotContains( "Ver usuário", (string) $response->getBody() );
    }

}

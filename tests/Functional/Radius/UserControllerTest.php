<?php

namespace Tests\Functional\Radius;

use Tests\Functional\BaseTestCase;

class UserControllerTest extends BaseTestCase {
    
    public function testActionList() {

        $response = $this->runApp( "GET", "/usuarios/listar");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Listar usuário", (string) $response->getBody());
        $this->assertNotContains( "Ver usuário", (string) $response->getBody());

        $response = $this->runApp( "GET", "/usuarios/listar?nome=paulo" );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "paulo", (string) $response->getBody() );
        $this->assertNotContains( "iago", (string) $response->getBody() );


        $response = $this->runApp( "GET", "/usuarios/listar?nome=paulo&atributo=password");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "paulo", (string) $response->getBody() );
        $this->assertNotContains( "iago", (string) $response->getBody() );
    }
    
    public function testActionCreate() {

        $response = $this->runApp( "GET", "/usuarios/criar");
        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Criar usuário", (string) $response->getBody());
        $this->assertNotContains( "Ver usuário", (string)$response->getBody());

        $response = $this->runApp( "POST", "/usuarios/criar", [
        
            "name"=>date("zhmi"),
            "groups"=>[

                "grupo1"                   
            ],
            "attributes-check"=>[ "Auth-Type" ],
            "operator-check"=>[ ":=" ],
            "value-check"=>[ "Accept" ],
            "attributes-reply"=>[ "Session-Timeout" ],
            "operator-reply"=>[ ":=" ],
            "value-reply"=>[ "7200" ],

        ]);

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Ver usuário", (string) $response->getBody() );
        $this->assertNotContains( "Criar usuário", (string) $response->getBody() );

        $response = $this->runApp( "POST", "/usuarios/criar", [] );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Criar usuário", (string) $response->getBody() );
        $this->assertNotContains( "Ver usuário", (string) $response->getBody() );
    }

}

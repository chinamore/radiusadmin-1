<?php

namespace Tests\Functional\Radius;

use Tests\Functional\BaseTestCase;

class GroupControllerTest extends BaseTestCase {

    public function testActionList() {

        $response = $this->runApp( "GET", "/groups/list");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Listar grupo", (string) $response->getBody());
        $this->assertNotContains( "Ver grupo", (string) $response->getBody());

        $response = $this->runApp( "GET", "/groups/list?name=grupo1" );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "grupo1", (string) $response->getBody() );
        $this->assertNotContains( "grupo2", (string) $response->getBody() );


        $response = $this->runApp( "GET", "/groups/list?name=grupo1&attribute=password");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "grupo1", (string) $response->getBody() );
        $this->assertNotContains( "grupo2", (string) $response->getBody() );
    }

    public function testActionCreate() {

        $response = $this->runApp( "GET", "/groups/create");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Criar grupo", (string) $response->getBody());
        $this->assertNotContains( "Ver grupo", (string)$response->getBody());

        $response = $this->runApp( "POST", "/groups/create", [
        
            "name"=>date("zhmi"),
            "attributes-check"=>[ "Auth-Type" ],
            "operators-check"=>[ ":=" ],
            "values-check"=>[ "Accept" ],
            "attributes-reply"=>[ "Session-Timeout" ],
            "operators-reply"=>[ ":=" ],
            "values-reply"=>[ "7200" ],
        ]);

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Ver grupo", (string) $response->getBody() );
        $this->assertNotContains( "Criar grupo", (string) $response->getBody() );

        $response = $this->runApp( "POST", "/groups/create", [] );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Criar grupo", (string) $response->getBody() );
        $this->assertNotContains( "Ver grupo", (string) $response->getBody() );
    }

    public function testActionExistJSON() {

        $response = $this->runApp( "GET", "/json/groups/exist?name=grupo1");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "true", (string) $response->getBody());
        $this->assertNotContains( "false", (string) $response->getBody());

        $response = $this->runApp( "GET", "/json/groups/exist?name=grupoQueNaoExiste" );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "false", (string) $response->getBody() );
        $this->assertNotContains( "true", (string) $response->getBody() );

        $response = $this->runApp( "GET", "/json/groups/exist" );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "false", (string) $response->getBody() );
        $this->assertNotContains( "true", (string) $response->getBody() );
    }

}

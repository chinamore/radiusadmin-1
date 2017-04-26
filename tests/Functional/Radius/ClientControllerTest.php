<?php

namespace Tests\Functional\Radius;

use Tests\Functional\BaseTestCase;

class ClientControllerTest extends BaseTestCase {
 
    public function testActionCreate() {

        $response = $this->runApp( "GET", "/clientes/criar");
        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "cliente criar", (string) $response->getBody());
        $this->assertNotContains( "cliente ver", (string)$response->getBody());

        $response = $this->runApp( "POST", "/clientes/criar", [
            
            "nasname"=>"192.168.0.10",
            "shortname"=>"adm",
            "type"=>"other",
            "ports"=>"50",
            "secret"=>"secret",
            "community"=>"public",
            "description"=>"description"
        ]);

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "cliente ver", (string) $response->getBody() );
        $this->assertNotContains( "cliente criar", (string) $response->getBody() );

        $response = $this->runApp( "POST", "/clientes/criar", [] );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "cliente criar", (string) $response->getBody() );
        $this->assertNotContains( "cliente ver", (string) $response->getBody() );
    }

}

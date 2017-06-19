<?php

namespace Tests\Functional\Radius;

use Tests\Functional\BaseTestCase;

class ClientControllerTest extends BaseTestCase {
 
    public function testActionCreate() {

        $response = $this->runApp( "GET", "/protected/clients/create");
        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Criar cliente", (string) $response->getBody());
        $this->assertNotContains( "Ver cliente", (string)$response->getBody());

        $response = $this->runApp( "POST", "/protected/clients/create", [
            
            "nas"=>[

                "nasname"=>"192.168.0.10",
                "shortname"=>"adm",
                "type"=>"other",
                "ports"=>"50",
                "secret"=>"secret",
                "server"=>"server",
                "community"=>"public",
                "description"=>"description"
        ]]);

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Ver cliente", (string) $response->getBody() );
        $this->assertNotContains( "Criar cliente", (string) $response->getBody() );

        $response = $this->runApp( "POST", "/protected/clients/create", [] );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Criar cliente", (string) $response->getBody() );
        $this->assertNotContains( "Ver cliente", (string) $response->getBody() );
    }

}

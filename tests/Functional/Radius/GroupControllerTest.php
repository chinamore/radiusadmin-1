<?php

namespace Tests\Functional\Radius;

use Tests\Functional\BaseTestCase;

class GroupControllerTest extends BaseTestCase {

    public function testActionList() {

        $response = $this->runApp( "GET", "/grupos/listar");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Listar grupo", (string) $response->getBody());
        $this->assertNotContains( "Ver grupo", (string) $response->getBody());

        $response = $this->runApp( "GET", "/grupos/listar?nome=grupo1" );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "grupo1", (string) $response->getBody() );
        $this->assertNotContains( "grupo2", (string) $response->getBody() );


        $response = $this->runApp( "GET", "/grupos/listar?nome=grupo1&atributo=password");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "grupo1", (string) $response->getBody() );
        $this->assertNotContains( "grupo2", (string) $response->getBody() );
    }

}

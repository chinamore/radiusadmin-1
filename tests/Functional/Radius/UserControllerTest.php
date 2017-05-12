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

}

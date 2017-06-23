<?php

namespace Tests\Functional;

class AppControllerTest extends BaseTestCase {
 
    public function testActionIndex() {

        $response = $this->runApp('GET', '/');
        
        $this->assertEquals( 302, $response->getStatusCode() );
        $this->assertContains( "/users/list", (string) $response->getHeaderLine("Location") );

    }

}

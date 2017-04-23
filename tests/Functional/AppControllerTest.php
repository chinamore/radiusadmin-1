<?php

namespace Tests\Functional;

class AppControllerTest extends BaseTestCase {
 
    public function testIndex() {

        $response = $this->runApp('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains("Index", (string)$response->getBody());
        $this->assertNotContains("Gato", (string)$response->getBody());
    }

}

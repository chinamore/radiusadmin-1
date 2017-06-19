<?php

namespace Tests\Functional\Radius;

use Tests\Functional\BaseTestCase;
use App\Model\Radius\Group;
use App\Model\Radius\RadGroupCheck;
use App\Model\Radius\RadGroupReply;

class GroupControllerTest extends BaseTestCase {

    public function testActionList() {

        $response = $this->runApp( "GET", "/protected/groups/list");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Listar grupo", (string) $response->getBody());
        $this->assertNotContains( "Ver grupo", (string) $response->getBody());

        $response = $this->runApp( "GET", "/protected/groups/list?name=grupo1" );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "grupo1", (string) $response->getBody() );
        $this->assertNotContains( "grupo2", (string) $response->getBody() );


        $response = $this->runApp( "GET", "/protected/groups/list?name=grupo1&attribute=password");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "grupo1", (string) $response->getBody() );
        $this->assertNotContains( "grupo2", (string) $response->getBody() );
    }

    public function testActionCreate() {

        $response = $this->runApp( "GET", "/protected/groups/create");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Criar grupo", (string) $response->getBody());
        $this->assertNotContains( "Ver grupo", (string)$response->getBody());

        $response = $this->runApp( "POST", "/protected/groups/create", [
        
            "name"=>date("zhmi"),
            "attributes-check"=>[ "Auth-Type" ],
            "operators-check"=>[ ":=" ],
            "values-check"=>[ "Accept" ],
            "attributes-reply"=>[ "Session-Timeout" ],
            "operators-reply"=>[ ":=" ],
            "values-reply"=>[ "7200" ],
        ]);

        $this->assertEquals( 302, $response->getStatusCode() );

        $this->assertContains( "/groups/view", (string) $response->getHeaderLine("Location") );

        $response = $this->runApp( "POST", "/protected/groups/create", [] );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Criar grupo", (string) $response->getBody() );
        $this->assertNotContains( "Ver grupo", (string) $response->getBody() );
    }

    public function testActionDelete() {

        $response = $this->runApp( "GET", "/protected/groups/delete", [
        
            "name"=>"nao-existe-" . date("zhmi")
        ]);

        $this->assertEquals( 302, $response->getStatusCode() );
        $this->assertContains( "error", (string) $response->getHeaderLine("Location") );

        $groupName = "existe-" . date("zhmi");
        
        $check = new RadGroupCheck();
        $check->groupname = $groupName;
        $check->attribute = "Simultaneous-Use";
        $check->op = ":=";
        $check->value = "1";

        $reply = new RadGroupReply();
        $reply->groupname = $groupName;
        $reply->attribute = "Session-Timeout";
        $reply->op = ":=";
        $reply->value = "7200";

        $group = new Group( $groupName, [ $check ], [ $reply ] );

        $group->save();

        $response = $this->runApp( "GET", "/protected/groups/delete", [
        
            "name"=>$groupName
        ]);
        

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Apagar grupo", (string) $response->getBody() );
        $this->assertNotContains( "Listar grupo", (string) $response->getBody() );
 
        $response = $this->runApp( "POST", "/protected/groups/delete", [
        
            "name"=>$groupName
        ]);

        $this->assertEquals( 302, $response->getStatusCode() );
        $this->assertContains( "groups/list", (string) $response->getHeaderLine("Location") );
 
    }

    public function testActionExistJSON() {

        $response = $this->runApp( "GET", "/protected/json/groups/exist?name=grupo1");

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "true", (string) $response->getBody());
        $this->assertNotContains( "false", (string) $response->getBody());

        $response = $this->runApp( "GET", "/protected/json/groups/exist?name=grupoQueNaoExiste" );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "false", (string) $response->getBody() );
        $this->assertNotContains( "true", (string) $response->getBody() );

        $response = $this->runApp( "GET", "/protected/json/groups/exist" );

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "false", (string) $response->getBody() );
        $this->assertNotContains( "true", (string) $response->getBody() );
    }

}

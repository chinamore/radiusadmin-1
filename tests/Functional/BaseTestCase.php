<?php

namespace Tests\Functional;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

/**
 * from slim/slim-skeleton
 *
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase {
    /**
     * Use middleware when running application?
     *
     * @var bool
     */
    protected $withMiddleware = false;

    /**
     * Process the application given a request method and URI
     *
     * @param string $requestMethod the request method (e.g. GET, POST, etc.)
     * @param string $requestUri the request URI
     * @param array|object|null $requestData the request data
     * @return \Slim\Http\Response
     */
    public function runApp($requestMethod, $requestUri, $requestData = null) {

        //for auth 
        $_SESSION = [ "auth_id"=>1 ];

        // Create a mock environment for testing with
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );

        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);

        // Add request data, if it exists
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        // Set up a response object
        $response = new Response();
       
        $settings = [
            "settings" => [

                "determineRouteBeforeAppMiddleware" => true,

                "displayErrorDetails" => true,

                "debug"=>true,

                "db_radius" => [
                    "driver" => "mysql",
                    "host" => "localhost",
                    "database" => "radius_test",
                    "username" => "root",
                    "password" => "root",
                    "charset"   => "utf8",
                    "collation" => "utf8_unicode_ci",
                    "prefix"    => "",
                ],

                "db_admin" => [
                    "driver" => "mysql",
                    "host" => "localhost",
                    "database" => "admin_test",
                    "username" => "root",
                    "password" => "root",
                    "charset"   => "utf8",
                    "collation" => "utf8_unicode_ci",
                    "prefix"    => "",
                ]
            ],
        ];

        // Instantiate the application
        $app = new App($settings);

        // Set up dependencies
        require __DIR__ . '/../../config/dependencies.php';

        // Register middleware
        if ($this->withMiddleware) {
            require __DIR__ . '/../../config/middleware.php';
        }

        // Register routes
        require __DIR__ . '/../../config/routes.php';

        // Process the application
        $response = $app->process($request, $response);

        // Return the response
        return $response;
    }
}

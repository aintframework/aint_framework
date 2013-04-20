<?php
namespace tests\aint\mvc\dispatching_test;

require_once 'aint/mvc/dispatching.php';
use aint\mvc\dispatching;
require_once 'aint/http.php';
use aint\http;
require_once 'aint/mvc/routing.php';
use aint\mvc\routing;

function test_action() {return 'result';}

class dispatching_test extends \PHPUnit_Framework_TestCase {

    public function test_dispatch() {
        // testing that dispatch chooses first router that didn't return null
        $router1_called = false;
        $router1 = function() use (&$router1_called) {$router1_called = true; return null;};
        $router2_called = false;
        $router2 = function() use (&$router2_called) {
            $router2_called = true;
            return [
                routing\route_action => function($request, $params) {return 'result' . $request . $params;},
                routing\route_params => ' test'
            ];
        };
        $router3_called = false;
        $router3 = function() use (&$router3_called) {$router3_called = true; return null;};

        $request = ' http request';
        $result = dispatching\dispatch_request($request, [$router1, $router2, $router3], __NAMESPACE__, function() {});
        $this->assertTrue($router1_called);
        $this->assertTrue($router2_called);
        $this->assertFalse($router3_called); // shouldn't get to the third router
        // here checking that result is what the found action returns, also confirm the request/route params
        // arguments were passed to it
        $this->assertEquals('result http request test', $result);
    }

    public function test_dispatch_string_callable_action() {
        $router = function() {
            return [
                routing\route_action => 'test_action',
                routing\route_params => []
            ];
        };
        $result = dispatching\dispatch_request('', [$router], __NAMESPACE__, function() {});
        $this->assertEquals('result', $result);
    }

    public function test_dispatch_error_handler() {
        $router = function() {
            return [
                routing\route_action => function(){throw new \exception();},
                routing\route_params => []
            ];};
        $error_handler = function($r, $p, $e){
            $this->assertInstanceOf('exception', $e);
            return 'error handled!';
        };
        $result = dispatching\dispatch_request('', [$router], __NAMESPACE__, $error_handler);
        $this->assertEquals('error handled!', $result);
    }

    public function test_dispatch_not_found_error() {
        $router = function() {return null;}; // null for not found
        $error_handler = function($r, $p, $e) {return $e;};
        $result = dispatching\dispatch_request('', [$router], __NAMESPACE__, $error_handler);
        $this->assertInstanceOf('aint\mvc\dispatching\not_found_error', $result);
    }

    public function test_request_callback() {
        $router = function() {
            return [
                routing\route_action => function($request, $params) {
                    return 'result' . $request . $params;
                },
                routing\route_params => ' test'
            ];
        };
        $request_callback = function($request) {
            return ' modified ' . $request;
        };
        $result = dispatching\dispatch_request('original request', [$router], __NAMESPACE__,
            function() {}, [$request_callback]);
        $this->assertEquals('result modified original request test', $result);
    }

    public function test_route_callback() {
        $router = function() {
            return [
                routing\route_action => function($request) {
                    return 'result1' . $request;
                },
                routing\route_params => ' router1'
            ];
        };
        $route_callback = function($request, $route) {
            return [
                routing\route_action => function($request2) use ($request, $route) {
                    return 'result2 ' . $request2 . $request . $route[routing\route_params];
                },
                routing\route_params => []
            ];
        };
        $result = dispatching\dispatch_request('request', [$router], __NAMESPACE__,
            function() {}, [], [$route_callback]);
        $this->assertEquals('result2 requestrequest router1', $result);
    }

    public function test_response_callback() {
        $router = function() {
            return [
                routing\route_action => function($request, $params) {
                    return 'result ' . $request . $params;
                },
                routing\route_params => ' test'
            ];
        };
        $response_callback = function($request, $route, $response) {
            return 'modified ' . $response;
        };
        $result = dispatching\dispatch_request('original request', [$router], __NAMESPACE__,
            function() {}, [], [], [$response_callback]);
        $this->assertEquals('modified result original request test', $result);
    }
}
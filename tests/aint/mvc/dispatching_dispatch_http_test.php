<?php
// Stubbing functions
namespace tests\aint\mvc\dispatching_dispatch_http_test;

\aint\test\require_mock('aint/mvc/dispatching.php', [
    'namespace aint\mvc\dispatching' => 'namespace ' . __NAMESPACE__,
    'function dispatch_request(' => 'function dispatch_not_needed(',
    ' http\send_response(' => ' \\' . __NAMESPACE__ . '\http_send_response(',
    ' http\build_request_from_globals(' => ' \\' . __NAMESPACE__ . '\http_build_request_from_globals(',
]);

function dispatch_request($request, $routers, $actions_namespace, $error_handler) {
    dispatching_dispatch_http_test::$dispatch_params = [
        $request, $routers, $actions_namespace, $error_handler
    ];
    return 'response to be sent';
}

function http_build_request_from_globals() {
    return 'request built from globals';
}

function http_send_response($response) {
    dispatching_dispatch_http_test::$send_response_param = $response;
}

use tests\aint\mvc\dispatching_dispatch_http_test as dispatching;

class dispatching_dispatch_http_test extends \PHPUnit_Framework_TestCase {

    public static $dispatch_params;
    public static $send_response_param;

    public function test_run() {
        // testing that dispatch method is called with http\build_request_from_globals
        // and that the result is sent via http\send_response function
        // we're stubbing all these functions for the test
        $error_handler = function() {};
        dispatching\dispatch_http(['router1', 'router2'], 'actions_namespace', $error_handler);
        $this->assertEquals('response to be sent', self::$send_response_param);
        $this->assertEquals([
            'request built from globals',['router1', 'router2'],
            'actions_namespace', $error_handler
        ], self::$dispatch_params);
    }

}
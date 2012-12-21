<?php
namespace tests\aint\mvc\routing_test;

require_once 'aint/mvc/routing.php';
use aint\mvc\routing;

require_once 'aint/http.php';
use aint\http;

class routing_test extends \PHPUnit_Framework_TestCase {

    public function test_route_segment_empty_path() {
        $request = [http\request_path => ''];
        $this->assertEquals(
            routing\default_namespace .'\\' . routing\default_function . routing\function_postfix,
            routing\route_segment($request)[routing\route_action]);
    }

    public function test_route_segment_default_function() {
        $request = [http\request_path => 'albums'];
        $route = routing\route_segment($request);
        $this->assertEquals('albums\\' . routing\default_function . routing\function_postfix,
            $route[routing\route_action]);
    }

    public function test_route_segment_no_params() {
        $request = [http\request_path => 'albums/list'];
        $route = routing\route_segment($request);
        $this->assertEquals('albums\list' . routing\function_postfix,
            $route[routing\route_action]);
        $this->assertEmpty($route[routing\route_params]);
    }

    public function test_route_segment_with_params() {
        $request = [http\request_path => 'albums/list/id/123/test/yes'];
        $route = routing\route_segment($request);
        $expected_route = [
            routing\route_action => 'albums\list' . routing\function_postfix,
            routing\route_params => ['id' => 123, 'test' => 'yes']
        ];
        $this->assertEquals($expected_route, $route);
    }

    public function test_assemble_segment() {
        $this->assertEquals('/albums/edit/id/1/test/xyz',
            routing\assemble_segment('albums\edit' . routing\function_postfix, ['id' => 1, 'test' => 'xyz']));
    }
}
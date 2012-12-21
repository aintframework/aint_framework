<?php
namespace tests\aint\http_test;

\aint\test\require_mock('aint/http.php', [
    'namespace aint\http' => 'namespace ' . __NAMESPACE__,
    '\'header\'' => '\'\\' . __NAMESPACE__ . '\header\'',
    ' header(' => ' \\' . __NAMESPACE__ . '\header(',
]);

function header($string) {
    \tests\aint\http_test\http_test::$header_called_with[] = $string;
}

use tests\aint\http_test as http;

class http_test extends \PHPUnit_Framework_TestCase {

    public function test_build_request_from_globals() {
        // emulating the global environment
        $_SERVER['REQUEST_URI'] = '/albums/edit/id/1/?a=3&b=4';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['a'] = 3;
        $_GET['b'] = 4;
        $_POST['x'] = 4;
        $_POST['y'] = 5;

        $request = http\build_request_from_globals();
        $expected = [
            http\request_scheme => 'http',
            http\request_body => '',
            http\request_path => 'albums/edit/id/1',
            http\request_params => [
                'a' => 3, 'b' => 4,
                'x' => 4, 'y' => 5
            ],
            http\request_method => 'POST'
        ];
        $this->assertEquals($expected, $request);
    }

    public function test_is_post_get_delete_put() {
        // post
        $request = [http\request_method => http\request_method_post];
        $this->assertTrue(http\is_post($request));
        $this->assertFalse(http\is_get($request));
        $this->assertFalse(http\is_delete($request));
        $this->assertFalse(http\is_put($request));
        // put
        $request = [http\request_method => http\request_method_put];
        $this->assertFalse(http\is_post($request));
        $this->assertFalse(http\is_get($request));
        $this->assertFalse(http\is_delete($request));
        $this->assertTrue(http\is_put($request));
        // delete
        $request = [http\request_method => http\request_method_delete];
        $this->assertFalse(http\is_post($request));
        $this->assertFalse(http\is_get($request));
        $this->assertTrue(http\is_delete($request));
        $this->assertFalse(http\is_put($request));
        // get
        $request = [http\request_method => http\request_method_get];
        $this->assertFalse(http\is_post($request));
        $this->assertTrue(http\is_get($request));
        $this->assertFalse(http\is_delete($request));
        $this->assertFalse(http\is_put($request));
    }

    public function test_build_response() {
        $response = http\build_response('test me', 201, ['Content-Type: plain/text']);
        $this->assertEquals([
            http\response_body => 'test me',
            http\response_status => 201,
            http\response_headers => ['Content-Type: plain/text']
        ], $response);
    }

    public function test_redirect() {
        $response = http\build_response();
        $this->assertEquals(200, $response[http\response_status]);
        $response = http\redirect($response, '/test');
        $this->assertEquals(302, $response[http\response_status]);
        $this->assertEquals(['Location: /test'], $response[http\response_headers]);
    }

    public function test_build_redirect() {
        $response = http\build_redirect('/test2');
        $this->assertEquals(302, $response[http\response_status]);
        $this->assertEquals('', $response[http\response_body]);
        $this->assertEquals(['Location: /test2'], $response[http\response_headers]);
    }

    public static $header_called_with = [];

    public function test_send_response() {
        $response = http\build_response('test body', 201, ['Content-Type: text/plain']);
        ob_start();
        http\send_response($response);
        $body = ob_get_clean();
        $this->assertEquals('test body', $body);
        $this->assertEquals(['HTTP/1.1 201', 'Content-Type: text/plain'], self::$header_called_with);
    }

    public function test_response_status() {
        $response = http\build_response('test body', 200, []);
        $this->assertEquals(200, $response[http\response_status]);
        $response = http\response_status($response, 404);
        $this->assertEquals(404, $response[http\response_status]);
        // just in case, checking body hasn't changed
        $this->assertEquals('test body', $response[http\response_body]);
    }
}
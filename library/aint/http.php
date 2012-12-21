<?php
/**
 * HTTP-related functions
 */
namespace aint\http;

/**
 * Http Request data
 */
const request_scheme = 'scheme',
      request_body = 'body',
      request_path = 'path',
      request_params = 'params',
      request_method = 'method';

/**
 * Http Response data
 */
const response_body = 'body',
      response_headers = 'headers',
      response_status = 'status';

/**
 * Http Request method types
 */
const request_method_post = 'POST',
      request_method_get = 'GET',
      request_method_put = 'PUT',
      request_method_delete = 'DELETE';

/**
 * Retrieves data about the current HTTP request using PHP's global arrays
 *
 * @return array
 */
function build_request_from_globals() {
    $path = $_SERVER['REQUEST_URI'];
    if (($separator_position = strpos($path, '?')) !== false)
        $path = substr($path, 0, $separator_position);
    return [
        request_scheme => empty($_SERVER['HTTPS']) ? 'http' : 'https',
        request_body => file_get_contents("php://input"),
        request_path => trim($path, '/'),
        request_params => array_merge($_GET, $_POST), // todo
        request_method => $_SERVER['REQUEST_METHOD']
    ];
}

/**
 * Whether the request is a POST
 *
 * @param $request
 * @return bool
 */
function is_post($request) {
    return $request[request_method] === request_method_post;
}

/**
 * Whether the request is a GET
 *
 * @param $request
 * @return bool
 */
function is_get($request) {
    return $request[request_method] === request_method_get;
}

/**
 * Whether the request is a DELETE
 *
 * @param $request
 * @return bool
 */
function is_delete($request) {
    return $request[request_method] === request_method_delete;
}

/**
 * Whether the request is a PUT
 *
 * @param $request
 * @return bool
 */
function is_put($request) {
    return $request[request_method] === request_method_put;
}

/**
 * Prepares data for HTTP response based on the parameters passed
 *
 * @param string $body
 * @param int $code
 * @param array $headers
 * @return array
 */
function build_response($body = '', $code = 200, $headers = []) {
    return [
        response_body => $body,
        response_status => $code,
        response_headers => $headers
    ];
}

/**
 * Prepares default response data and sets it to be redirected to location specified
 *
 * @param $location
 * @return mixed
 */
function build_redirect($location) {
    return redirect(build_response(), $location);
}

/**
 * Sets the response data passed to be redirected to location specified
 *
 * @param $response
 * @param $location
 * @return mixed
 */
function redirect($response, $location) {
    array_push($response[response_headers], 'Location: ' . $location);
    $response[response_status] = 302;
    return $response;
}

/**
 * Outputs response data
 *
 * @param $response
 */
function send_response($response) {
    header('HTTP/1.1 ' . $response[response_status]);
    array_walk($response[response_headers], 'header');
    echo $response[response_body];
}

// todo: function to set response's status
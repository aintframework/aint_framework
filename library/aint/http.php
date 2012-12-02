<?php
namespace aint\http;

const request_scheme = 'scheme';
const request_body = 'body';
const request_path = 'path';
const request_params = 'params';
const request_method = 'method';

const response_body = 'body';
const response_headers = 'headers';
const response_status = 'status';

const request_method_post = 'POST';
const request_method_get = 'GET';
const request_method_put = 'PUT';
const request_method_delete = 'DELETE';

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

function is_post($request) {
    return $request[request_method] === request_method_post;
}

function is_get($request) {
    return $request[request_method] === request_method_get;
}

function is_delete($request) {
    return $request[request_method] === request_method_delete;
}

function is_put($request) {
    return $request[request_method] === request_method_put;
}

function build_response($body = '', $code = 200, $headers = []) {
    return [
        response_body => $body,
        response_status => $code,
        response_headers => $headers
    ];
}

function build_redirect($location) {
    return redirect(build_response(), $location);
}

function redirect($response, $location) {
    array_push($response[response_headers], 'Location: ' . $location);
    $response[response_status] = 302;
    return $response;
}

function send_response($response) {
    header('HTTP/1.1 ' . $response[response_status]);
    array_walk($response[response_headers], 'header');
    echo $response[response_body];
}
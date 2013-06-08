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
      request_method = 'method',
      request_headers = 'headers';

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
        request_params => array_merge($_GET, $_POST), // todo make these separate, provide functions
        request_method => $_SERVER['REQUEST_METHOD'],
        request_headers => get_headers_from_globals(),
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
 * @return array
 */
function redirect($response, $location) {
    array_push($response[response_headers], 'Location: ' . $location);
    $response[response_status] = 302;
    return $response;
}

/**
 * Adds cookie header to the response array passed
 *
 * @param array $response
 * @param string $name Cookie name
 * @param string $value Value
 * @param integer $expires Timestamp
 * @param string $path
 * @param string $domain
 * @param boolean $secure
 * @param boolean $http_only
 * @param null $max_age
 * @param null $version
 * @return array
 */
function add_cookie_header(array $response, $name = null, $value = null, $expires = null, $path = null, $domain = null,
                           $secure = false, $http_only = false, $max_age = null, $version = null) {
    if (strpos($value, '"')!==false)
        $value = '"' . urlencode(str_replace('"', '', $value)) . '"';
    else
        $value = urlencode($value);

    $cookie_string = $name . '=' . $value;
    if ($version !== null)
        $cookie_string .= '; Version=' . $version;
    if ($max_age !== null)
        $cookie_string .= '; Max-Age=' . $max_age;
    if ($expires !== null)
        $cookie_string .= '; Expires=' . date(DATE_COOKIE, $expires);
    if ($domain !== null)
        $cookie_string .= '; Domain=' . $domain;
    if ($path !== null)
        $cookie_string .= '; Path=' . $path;
    if ($secure)
        $cookie_string .= '; Secure';
    if ($http_only)
        $cookie_string .= '; HttpOnly';

    $response[response_headers][] = 'Set-Cookie: ' . $cookie_string;
    return $response;
}

/**
 * Returns value of a cookie by name
 *
 * @param $request
 * @param $name
 * @return mixed|null
 */
function get_cookie_value($request, $name) {
    if (isset($request[request_headers]['Cookie'])) {
        $key_value_pairs = preg_split('#;\s*#', $request[request_headers]['Cookie']);
        foreach ($key_value_pairs as $key_value) {
            list($key, $value) = preg_split('#=\s*#', $key_value, 2);
            if ($key == $name)
                return $value;
        }
    }
    return null;
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

/**
 * Changes HTTP status in response data
 *
 * @param $response
 * @param $status
 * @return array
 */
function response_status($response, $status) {
    $response[response_status] = $status;
    return $response;
}

/**
 * Extracts http headers from current context
 *
 * Uses getallheaders function if available
 *
 * @return array
 */
function get_headers_from_globals() {
    if (function_exists('getallheaders'))
        return getallheaders();
    else {
        $headers = [];
        foreach($_SERVER as $header => $value)
            if (strpos($header, 'HTTP_') === 0) {
                $formatted_header_name =
                    str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($header, 5)))));
                $headers[$formatted_header_name] = $value;
            }
        return $headers;
    }
}
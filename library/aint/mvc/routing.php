<?php
/**
 * Functions for routing an HTTP request to an action-function and back.
 */
namespace aint\mvc\routing;

require_once 'aint/http.php';
use aint\http; 
require_once 'aint/common.php';
use aint\common;

/**
 * Route data
 */
const
route_params = 'params',
route_action = 'action';

/**
 * Function to be used as default when only controller is specified
 */
const
segment_default_function = 'index'; // todo: introduce default controller as well, to make typical usage simpler

/**
 * Function postfix, appended to all function names.
 */
const
function_postfix = '_action'; // todo: consider as default for route_root as well or simply get rid of route_roo

/**
 * Routes
 *    /albums/edit/id/1/a/test to albums\edit_action()
 *    with ['id' => '1', 'a' => 'test'] as parameters
 * Routes
 *    /albums is routed to albums\index_action with no parameters
 *
 * @param $request
 * @return array|null
 */
function route_segment($request) {
    if (!($path = $request[http\request_path]))
        return null;
    else {
        $params = explode('/', $path);
        $action = array_shift($params) . '\\';
        $action .= (count($params) > 0)
            ? array_shift($params)
            : segment_default_function;
        $action .= function_postfix;
        $params_combined = [];
        foreach ($params as $key => $value)
            if ($key % 2 == 0)
                $params_combined[$value] = common\get_param($params, $key + 1, '');
        return [route_action => $action,
                route_params => $params_combined];
    }
}

/**
 * Routes / to the $index_action
 *
 * @param $request
 * @param $index_action
 * @return array|null
 */
function route_root($request, $index_action) {
    return (!$request[http\request_path])
        ? [route_action => $index_action,
           route_params => []]
        : null;
}

/**
 * Assembles albums\edit_action, ['id' => 123] to
 * /albums/edit/id/123
 *
 * @param $route_action
 * @param array $route_params
 * @return string
 */
function assemble_segment($route_action, $route_params = []) {
    $params_uncombined = [];
    foreach ($route_params as $key => $value) {
        $params_uncombined[] = $key;
        $params_uncombined[] = $value;
    }
    // removing function postfix and replacing \ to /
    $base_uri = str_replace('\\', '/',
        substr($route_action, 0, -strlen(function_postfix)));
    return '/' . $base_uri . '/' . implode('/', $params_uncombined);
}
<?php
/**
 * Dispatching mechanisms:
 *    processing http request,
 *    routing it to a specific handler
 *    sending the http response
 */
namespace aint\mvc\dispatching;

require_once 'aint/http.php';
use aint\http; 
require_once 'aint/mvc/routing.php';
use aint\mvc\routing;

/**
 * Error thrown when an http request cannot be routed
 */
class not_found_error extends \exception {};

/**
 * Dispatches global http request with routers stack passed
 *
 * @param $routers array
 * @param $actions_namespace string
 * @param $error_handler callable
 */
function run(array $routers, $actions_namespace, callable $error_handler) {
    http\send_response(dispatch(
        http\build_request_from_globals(),
        $routers,
        $actions_namespace,
        $error_handler
    ));
}

/**
 * The quickest way to run an application.
 *
 * Uses route_segment as the only router
 * with index\index_action to handle the root
 *
 * @param $actions_namespace string e.g. app\controller\actions
 * @param $error_handler callable
 */
function run_default($actions_namespace, callable $error_handler) {
    run(['\aint\mvc\routing\route_segment'],
        $actions_namespace,
        $error_handler);
}

/**
 * Dispatches the HTTP request passed, trying to find a fitting router in the routers
 * stack passed.
 *
 * @param $request
 * @param $routers
 * @param $actions_namespace
 * @param $error_handler
 * @return mixed
 * @throws not_found_error
 */
function dispatch($request, $routers, $actions_namespace, callable $error_handler) {
    foreach ($routers as $router) {
        $route = call_user_func($router, $request);
        if ($route !== null)
            break;
    }
    try {
        if (empty($route)
            || (!is_callable($action = $route[routing\route_action])
                && !is_callable($action = $actions_namespace . '\\' . $action)))
            throw new not_found_error();
        return $action($request, $route[routing\route_params]);
    } catch (\exception $error) {
        $params = empty($route) ? [] : $route[routing\route_params];
        return $error_handler($request, $params, $error);
    }
}
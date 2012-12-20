<?php
/**
 * PDO db driver for aint framework
 */
namespace aint\db\driver\pdo;

require_once 'aint/common.php';
use aint\common;

/**
 * Database Connection parameters
 */
const
param_dns = 'dns',
param_username = 'username',
param_password = 'password';

/**
 * Fetches all resulting records for a query
 *
 * @param $pdo_connection
 * @param $query
 * @return mixed
 */
function fetch_all($pdo_connection, $query) {
    return query($pdo_connection, $query)->fetchAll();
}

/**
 * Prepares a statement object for a query and returns it
 *
 * @param $pdo_connection
 * @param $query
 * @return mixed
 */
function query($pdo_connection, $query) {
    $statement = $pdo_connection->prepare($query);
    $statement->execute();
    return $statement;
}

/**
 * Connects to the database and returns the link to the connection established
 *
 * @param $params
 * @return \PDO
 */
function db_connect($params) {
    $username = common\get_param($params, param_username);
    $password = common\get_param($params, param_password);
    return new \PDO($params[param_dns], $username, $password);
}
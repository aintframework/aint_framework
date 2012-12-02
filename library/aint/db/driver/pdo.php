<?php
namespace aint\db\driver\pdo;

require_once 'aint/common.php';
use aint\common;

const param_dns = 'dns';
const param_username = 'username';
const param_password = 'password';

function fetch_all($pdo_connection, $query) {
    return query($pdo_connection, $query)->fetchAll();
}

function query($pdo_connection, $query) {
    $statement = $pdo_connection->prepare($query);
    $statement->execute();
    return $statement;
}

function db_connect($params) {
    $username = common\get_param($params, param_username);
    $password = common\get_param($params, param_password);
    return new \PDO($params[param_dns], $username, $password);
}
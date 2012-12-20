<?php
/**
 * Implementation of Table Gateway Pattern
 */
namespace aint\db\table;

require_once 'aint/db/sql.php';
use aint\db\sql;

/**
 * Prepares and executes a select query on the table specified.
 *
 * @param $db_connection
 * @param $platform_namespace
 * @param $driver_namespace
 * @param $table
 * @param array $where
 * @return mixed
 */
function select($db_connection, $platform_namespace, $driver_namespace, $table, array $where = []) {
    $select = sql\prepare_select($platform_namespace, '*', $table);
    $where = sql\prepare_where($platform_namespace, $where);
    $fetch_all = $driver_namespace . '\fetch_all';
    return $fetch_all($db_connection, implode(' ', [$select, $where]));
}

/**
 * Prepares and executes an insert on the table specified.
 * The data for the new record is presented as a key => value array
 *
 * @param $db_connection
 * @param $platform_namespace
 * @param $driver_namespace
 * @param $table
 * @param array $data
 * @return mixed
 */
function insert($db_connection, $platform_namespace, $driver_namespace, $table, array $data) {
    $query = $driver_namespace . '\query';
    return $query($db_connection, sql\prepare_insert($platform_namespace, $table, $data));
}

/**
 * Prepares and executes an update on the table specified.
 * The data for the update is presented as a key => value array
 * WHERE part is an array of constraints all of which must qualify
 *
 * @param $db_connection
 * @param $platform_namespace
 * @param $driver_namespace
 * @param $table
 * @param $data
 * @param array $where
 * @return mixed
 */
function update($db_connection, $platform_namespace, $driver_namespace, $table, $data, $where = []) {
    $update = sql\prepare_update($platform_namespace, $table, $data);
    $where = sql\prepare_where($platform_namespace, $where);
    $query = $driver_namespace . '\query';
    return $query($db_connection, implode(' ', [$update, $where]));
}

/**
 * Prepares and executes a delete on the table specified.
 * WHERE part is an array of constraints all of which must qualify
 *
 * @param $db_connection
 * @param $platform_namespace
 * @param $driver_namespace
 * @param $table
 * @param array $where
 * @return mixed
 */
function delete($db_connection, $platform_namespace, $driver_namespace, $table, array $where = []) {
    $delete = sql\prepare_delete($platform_namespace, $table);
    $where = sql\prepare_where($platform_namespace, $where);
    $query = $driver_namespace . '\query';
    return $query($db_connection, implode(' ', [$delete, $where]));
}


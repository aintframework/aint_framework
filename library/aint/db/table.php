<?php
namespace aint\db\table;

use aint\db\sql;

function select($db_connection, $platform_namespace, $driver_namespace, $table, array $where = []) {
    $select = sql\prepare_select($platform_namespace, '*', $table);
    $where = sql\prepare_where($platform_namespace, $where);
    $fetch_all = $driver_namespace . '\fetch_all';
    return $fetch_all($db_connection, implode(' ', [$select, $where]));
}

function insert($db_connection, $platform_namespace, $driver_namespace, $table, array $data) {
    $query = $driver_namespace . '\query';
    return $query($db_connection, sql\prepare_insert($platform_namespace, $table, $data));
}

function update($db_connection, $platform_namespace, $driver_namespace, $table, $data, $where = []) {
    $update = sql\prepare_update($platform_namespace, $table, $data);
    $where = sql\prepare_where($platform_namespace, $where);
    $query = $driver_namespace . '\query';
    return $query($db_connection, implode(' ', [$update, $where]));
}

function delete($db_connection, $platform_namespace, $driver_namespace, $table, array $where = []) {
    $delete = sql\prepare_delete($platform_namespace, $table);
    $where = sql\prepare_where($platform_namespace, $where);
    $query = $driver_namespace . '\query';
    return $query($db_connection, implode(' ', [$delete, $where]));
}


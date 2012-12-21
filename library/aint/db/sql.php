<?php
/**
 * SQL specific operations, common for all platforms
 */
namespace aint\db\sql;

require_once 'aint/common.php';
use aint\common;

/**
 * SQL query parts specs
 */
const select_specification = 'select %s from %s',
      delete_specification = 'delete from %s',
      update_specification = 'update %s set %s',
      insert_specification = 'insert into %s (%s) values (%s)',
      where_specification = 'where %s',
      limit_specification = 'limit %s';

/**
 * Error thrown if fields listed for a select are neither a wildcard nor an array
 */
class bad_columns_error extends common\error{};

/**
 * Prepares WHERE part of SQL query
 *
 * @param $platform_namespace
 * @param array $where
 * @return string
 */
function prepare_where($platform_namespace, array $where) {
    $quote_identifier = $platform_namespace . '\quote_identifier';
    $quote_value = $platform_namespace . '\quote_value';
    $sql = [];
    foreach ($where as $column => $value)
        $sql[] = $quote_identifier($column) . ' = ' . $quote_value($value);
    return !empty($sql)
        ? sprintf(where_specification, implode(' and ', $sql))
        : '';
}

/**
 * Prepares SELECT part of sql query
 *
 * @param $platform_namespace
 * @param $columns
 * @param $table
 * @return string
 * @throws bad_columns_error
 */
function prepare_select($platform_namespace, $columns, $table) {
    $quote_identifier = $platform_namespace . '\quote_identifier';
    if (is_array($columns))
        $columns = implode(',', array_map($quote_identifier, $columns));
    elseif ($columns != '*')
        throw new bad_columns_error($columns);
    return sprintf(select_specification, $columns, $quote_identifier($table));
}

/**
 * Prepares DELETE part of sql query
 *
 * @param $platform_namespace
 * @param $table
 * @return string
 */
function prepare_delete($platform_namespace, $table) {
    $quote_identifier = $platform_namespace . '\quote_identifier';
    return sprintf(delete_specification, $quote_identifier($table));
}

/**
 * Prepares INSERT part of SQL query
 *
 * @param $platform_namespace
 * @param $table
 * @param $data
 * @return string
 */
function prepare_insert($platform_namespace, $table, $data) {
    $quote_identifier = $platform_namespace . '\quote_identifier';
    $quote_value = $platform_namespace . '\quote_value';
    $keys = implode(',',
        array_map($quote_identifier, array_keys($data)));
    $values = $quote_value(array_values($data));
    return sprintf(insert_specification, $quote_identifier($table), $keys, $values);
}


/**
 * Prepares UPDATE part of SQL query
 *
 * @param $platform_namespace
 * @param $table
 * @param $data
 * @return string
 */
function prepare_update($platform_namespace, $table, $data) {
    $quote_identifier = $platform_namespace . '\quote_identifier';
    $quote_value = $platform_namespace . '\quote_value';
    $set = [];
    foreach ($data as $column => $value)
        $set[] = $quote_identifier($column) . ' = ' . $quote_value($value);
    $set = implode(', ', $set);
    return sprintf(update_specification, $quote_identifier($table), $set);
}
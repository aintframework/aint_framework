<?php
/**
 * SQLite platform specific functions for composing valid SQL queries
 */
namespace aint\db\platform\sqlite;

/**
 * Quotes an identifier such as a column name
 *
 * @param $identifier
 * @return string
 */
function quote_identifier($identifier) {
    return '"' . str_replace('"', '\\' . '"', $identifier) . '"';
}

/**
 * Quotes a value such as a column value
 *
 * @param $value
 * @return string
 */
function quote_value($value){
    $value = str_replace('\'', '\\' . '\'', $value);
    if (is_array($value))
        $value = implode('\', \'', $value);
    return '\'' . $value . '\'';
}

/**
 * Quotes all values in the string, presented by placeholders
 * (using `sprintf`)
 *
 * @param string
 * @param mixed,...
 * @return string
 */
function quote_into() {
    $args = func_get_args();
    $query = array_shift($args);
    $params = array_map(__NAMESPACE__ . '\quote_value', $args);
    array_unshift($params, $query);
    return call_user_func_array('sprintf', $params);
}

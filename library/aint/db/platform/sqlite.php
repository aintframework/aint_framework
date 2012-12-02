<?php
namespace aint\db\platform\sqlite;

function quote_identifier($identifier) {
    return '"' . str_replace('"', '\\' . '"', $identifier) . '"';
}

function quote_value($value){
    $value = str_replace('\'', '\\' . '\'', $value);
    if (is_array($value))
        $value = implode('\', \'', $value);
    return '\'' . $value . '\'';
}

function quote_into() {
    $args = func_get_args();
    $query = array_shift($args);
    $params = array_map(__NAMESPACE__ . '\quote_value', $args);
    array_unshift($params, $query);
    return call_user_func_array('sprintf', $params);
}

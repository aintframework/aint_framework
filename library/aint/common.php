<?php
/**
 * Common, general purpose functions
 */
namespace aint\common;

/**
 * Base-Class for all errors, provides array access capabilities
 * todo: probably get rid of this, or consider aint\error namespace
 */
class error extends \exception implements \arrayaccess {
    protected $error_data;

    public function __construct($error_data = []) {
        $this->error_data = $error_data;
    }

    public function offsetExists($key) {
        return isset($this->error_data[$key]);
    }

    public function offsetGet($key) {
        return isset($this->error_data[$key])
            ? $this->error_data[$key]
            : null;
    }

    public function offsetSet($key, $value) {
        $this->error_data[$key] = $value;
    }

    public function offsetUnset($key) {
        if (isset($this->error_data[$key]))
            unset($this->error_data[$key]);
    }
}

/**
 * Checks if $name parameter is set in $data array
 * returns its value if yes, and if not - returns $default
 *
 * No notice or warning is ever triggered
 *
 * @param $data
 * @param $name
 * @param null $default
 * @return null
 */
function get_param($data, $name, $default = null) {
    return array_key_exists($name, $data)
        ? $data[$name] : $default;
}

/**
 * Merges two arrays recursively
 *
 * @param array $config1
 * @param array $config2
 * @return array
 */
function merge_recursive(array $config1, array $config2) {
    foreach ($config2 as $key => $value)
        if (array_key_exists($key, $config1))
            if (is_int($key))
                $config1[] = $value;
            elseif (is_array($value))
                $config1[$key] = merge_recursive($config1[$key], $value);
            else
                $config1[$key] = $value;
        else
            $config1[$key] = $value;
    return $config1;
}
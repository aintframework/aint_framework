<?php
namespace aint\common;

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

function get_param($data, $name, $default = null) {
    return array_key_exists($name, $data)
        ? $data[$name] : $default;
}

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
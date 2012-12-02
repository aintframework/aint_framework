<?php
namespace aint\test;

// todo: test, rewrite, use regex, optimize, add recursive require_mock
function require_mock($filename, $replacements) {
    $file = stream_resolve_include_path($filename);
    $code = substr(file_get_contents($file), 5);
    if (is_callable($replacements))
        $code = $replacements($code);
    elseif (is_array($replacements))
        foreach ($replacements as $key => $value)
            $code = str_replace($key, $value, $code);
    eval($code);
}

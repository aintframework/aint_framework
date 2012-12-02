<?php
namespace aint\templating;

/**
 * Renders template specified
 *
 * Usage example:
 * render_template('/home/alex/my_templates/template.tpl', ['data' => 'Hello World'])
 *
 * @param $template string
 * @param $vars array
 * @return string
 */
function render_template() {
    ob_start();
    if (func_num_args() > 1)
        extract(func_get_arg(1));
    include func_get_arg(0);
    return ob_get_clean();
}
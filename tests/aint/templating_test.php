<?php
namespace tests\aint\templating_test;

require_once 'aint/templating.php';
use aint\templating;


class templating_test extends \PHPUnit_Framework_TestCase
{
    public function test_render_template_no_vars()
    {
        $file = dirname(realpath(__FILE__)) . '/_files/templating_test/no_vars.phtml';
        $text = templating\render_template($file);
        $this->assertEquals('This is a php template', $text);
    }

    public function test_render_template()
    {
        $file = dirname(realpath(__FILE__)) . '/_files/templating_test/template.phtml';
        $text = templating\render_template($file, [
            'variable' => 'test',
            'array_variable' => [
                'val1' => 1,
                'val2' => 2
            ]
        ]);
        $expectedText = "This text\n"
                      . "template\n"
                      . "has vars: test\n"
                      . "Numbers: 1 , 2";
        $this->assertEquals($expectedText, $text);
    }
}
<?php
namespace tests\aint\common_test;

require_once 'aint/common.php';
use aint\common;

class common_test extends \PHPUnit_Framework_TestCase {

    public function test_get_param() {
        $array = ['test' => 2];
        $this->assertEquals(2, common\get_param($array, 'test'));
        $this->assertNull(common\get_param($array, 'not_existing'));
        $this->assertEquals(3, common\get_param($array, 'default', 3));
    }

    public function test_merge_recursive() {
        $array1 = [
            'db' => 3,
            'user' => 'john',
            'sub' => [
                'test' => 12,
                'a' => 'old'
            ]
        ];
        $array2 = [
            'user' => 'alex',
            'sub' => [
                'new' => 'Yes',
                'a' => 'new'
            ]
        ];

        $this->assertEquals([
            'db' => 3,
            'user' => 'alex',
            'sub' => [
                'test' => 12,
                'new' => 'Yes',
                'a' => 'new'
            ]
        ], common\merge_recursive($array1, $array2));
    }
}
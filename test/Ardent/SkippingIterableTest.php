<?php

namespace Ardent;

class SkippingIterableTest extends \PHPUnit_Framework_TestCase {

    function testEmpty() {
        $inner = new ArrayIterable([]);
        $iterator = $inner->skip(2);
        $this->assertCount(0, $iterator);
    }

    function test() {
        $inner = new ArrayIterable([1, 2, 3, 4, 5]);
        $iterator = $inner->skip(2);

        $this->assertCount(3, $iterator);
        $expected = [
            2 => 3,
            3 => 4,
            4 => 5
        ];
        $this->assertEquals($expected, $iterator->toArray(TRUE));
    }

    function testNegative() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterable($array);
        $iterator = $inner->skip(-2);

        $this->assertCount(5, $iterator);
        $this->assertEquals($array, $iterator->toArray());
    }

}

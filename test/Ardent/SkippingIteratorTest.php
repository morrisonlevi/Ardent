<?php

namespace Ardent;

use Ardent\Iterator\ArrayIterator;

class SkippingIteratorTest extends \PHPUnit_Framework_TestCase {

    function testEmpty() {
        $inner = new ArrayIterator([]);
        $iterator = $inner->skip(2);
        $this->assertCount(0, $iterator);
    }

    function test() {
        $inner = new ArrayIterator([1, 2, 3, 4, 5]);
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
        $inner = new ArrayIterator($array);
        $iterator = $inner->skip(-2);

        $this->assertCount(5, $iterator);
        $this->assertEquals($array, $iterator->toArray());
    }

}

<?php

namespace Ardent;

use Ardent\Iterator\ArrayIterator;

class MappingIteratorTest extends \PHPUnit_Framework_TestCase {

    function testEmpty() {
        $inner = new ArrayIterator([]);
        $i = 0;
        $iterator = $inner->map(function () use (&$i) {
            $i++;
        });

        $this->assertCount(0, $iterator);
        $this->assertEquals([], $iterator->toArray());
        $this->assertEquals(0, $i);
    }

    function test() {
        $array = [0, 1, 2, 3, 4];
        $inner = new ArrayIterator($array);
        $iterator = $inner->map(function ($val) {
            return $val * 2;
        });

        $this->assertCount(count($array), $iterator);
        $this->assertEquals([0, 2, 4, 6, 8], $iterator->toArray());
    }

}

<?php

namespace Ardent;

use Ardent\Iterator\ArrayIterator;

class SlicingIteratorTest extends \PHPUnit_Framework_TestCase {

    function testEmpty() {
        $inner = new ArrayIterator([]);
        $iterator = $inner->slice(0, 2);
        $this->assertCount(0, $iterator);
    }

    function testBeginning() {
        $inner = new ArrayIterator([1, 2, 3, 4, 5]);
        $iterator = $inner->slice(0, 2);
        $this->assertCount(2, $iterator);

        $expected = [1, 2];
        $this->assertEquals($expected, $iterator->toArray());
    }

    function testMiddle() {
        $inner = new ArrayIterator([1, 2, 3, 4, 5]);
        $iterator = $inner->slice(2, 2);
        $this->assertCount(2, $iterator);

        $expected = [3, 4];
        $this->assertEquals($expected, $iterator->toArray());
    }

    function testEnd() {
        $inner = new ArrayIterator([1, 2, 3, 4, 5]);
        $iterator = $inner->slice(3, 2);
        $this->assertCount(2, $iterator);

        $expected = [4, 5];
        $this->assertEquals($expected, $iterator->toArray());
    }

    function testCountMoreThanAvailable() {
        $inner = new ArrayIterator([1, 2, 3, 4, 5]);
        $iterator = $inner->slice(3, 3);
        $this->assertCount(2, $iterator);

        $expected = [4, 5];
        $this->assertEquals($expected, $iterator->toArray());
    }

}

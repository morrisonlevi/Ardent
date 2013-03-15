<?php

namespace Ardent;

class SlicingIterableTest extends \PHPUnit_Framework_TestCase {

    function testEmpty() {
        $inner = new ArrayIterable([]);
        $iterator = $inner->slice(0, 2);
        $this->assertCount(0, $iterator);
    }

    function testBeginning() {
        $inner = new ArrayIterable([1, 2, 3, 4, 5]);
        $iterator = $inner->slice(0, 2);
        $this->assertCount(2, $iterator);

        $expected = [1, 2];
        $this->assertEquals($expected, $iterator->toArray());
    }

    function testMiddle() {
        $inner = new ArrayIterable([1, 2, 3, 4, 5]);
        $iterator = $inner->slice(2, 2);
        $this->assertCount(2, $iterator);

        $expected = [3, 4];
        $this->assertEquals($expected, $iterator->toArray());
    }

    function testEnd() {
        $inner = new ArrayIterable([1, 2, 3, 4, 5]);
        $iterator = $inner->slice(3, 2);
        $this->assertCount(2, $iterator);

        $expected = [4, 5];
        $this->assertEquals($expected, $iterator->toArray());
    }

    function testCountMoreThanAvailable() {
        $inner = new ArrayIterable([1, 2, 3, 4, 5]);
        $iterator = $inner->slice(3, 3);
        $this->assertCount(2, $iterator);

        $expected = [4, 5];
        $this->assertEquals($expected, $iterator->toArray());
    }

}

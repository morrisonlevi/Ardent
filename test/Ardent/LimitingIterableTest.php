<?php

namespace Ardent;

class LimitingIterableTest extends \PHPUnit_Framework_TestCase {

    function testEmpty() {
        $inner = new ArrayIterable([]);
        $iterator = $inner->limit(2);

        $this->assertCount(0, $iterator);

        $i = 0;
        $iterator->each(function () use (&$i) {
            $i++;
        });
        $this->assertEquals(0, $i);
    }

    function testLimit() {
        $inner = new ArrayIterable([1, 4, 5]);
        $iterator = $inner->limit(2);

        $this->assertCount(2, $iterator);

        $i = 0;
        $iterator->each(function () use (&$i) {
            $i++;
        });
        $this->assertEquals(2, $i);
    }

    function testLimitExtra() {
        $inner = new ArrayIterable([1, 4, 5]);
        $iterator = $inner->limit(4);

        $this->assertCount(3, $iterator);

        $i = 0;
        $iterator->each(function () use (&$i) {
            $i++;
        });
        $this->assertEquals(3, $i);
    }

}
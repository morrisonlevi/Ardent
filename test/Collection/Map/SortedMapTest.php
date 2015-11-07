<?php

namespace Ardent\Collection;

class SortedMapTest extends \PHPUnit_Framework_TestCase {

    function testOffsetSet() {
        $map = new SortedMap();
        $this->assertCount(0, $map);

        $map[0] = 1;
        $map[1] = 2;
        $map[2] = 3;

        $this->assertCount(3, $map);

        for ($i = 0; $i < $map->count(); $i++) {
            $actual = $map[$i];
            $this->assertEquals($i + 1, $actual);
        }

        $map[0] = 0;
        $this->assertCount(3, $map);
    }


    /**
     * @depends testOffsetSet
     */
    function testOffsetSetAlreadySet() {
        $map = new SortedMap();
        $map[1] = 1;
        $map[1] = 0;

        $expected = 0;
        $actual = $map[1];
        $this->assertEquals($expected, $actual);
    }


    /**
     * @depends testOffsetSet
     */
    function testOffsetUnset() {
        $map = new SortedMap();
        $map[0] = 0;

        unset($map[0]);

        $this->assertCount(0, $map);
    }


    /**
     * @depends testOffsetSet
     * @depends testOffsetUnset
     */
    function testIsEmpty() {
        $map = new SortedMap();
        $this->assertTrue($map->isEmpty());
        $this->assertTrue($map->getIterator()->isEmpty());

        $map[0] = 0;
        $this->assertFalse($map->isEmpty());
        $this->assertFalse($map->getIterator()->isEmpty());

        unset($map[0]);
        $this->assertTrue($map->isEmpty());
        $this->assertTrue($map->getIterator()->isEmpty());
    }


    /**
     * @depends testOffsetSet
     */
    function testContains() {
        $map = new SortedMap();

        $this->assertFalse($map->contains(0));

        $map[0] = 1;
        $this->assertFalse($map->contains(0));
        $this->assertTrue($map->contains(1));
    }


    function testFirst() {
        $map = new SortedMap();
        $map[0] = 'hello';

        $this->assertEquals(0, $map->firstKey());

        $map[1] = 'there';
        $this->assertEquals(0, $map->firstKey());
    }


    function testLast() {
        $map = new SortedMap();
        $map[0] = 'hello';

        $this->assertEquals(0, $map->lastKey());

        $map[1] = 'there';
        $this->assertEquals(1, $map->lastKey());
    }


    /**
     * @depends testOffsetSet
     */
    function testOffsetExists() {
        $map = new SortedMap();

        $this->assertFalse($map->offsetExists(0));

        $map[5] = 5;
        $map[8] = 8;
        $map[2] = 2;
        $this->assertFalse($map->offsetExists(0));
        $this->assertFalse($map->offsetExists(10));
        $this->assertTrue($map->offsetExists(5));
        $this->assertTrue($map->offsetExists(8));
        $this->assertTrue($map->offsetExists(2));
    }


    /**
     * @depends testOffsetSet
     */
    function testClear() {
        $map = new SortedMap();

        $map[0] = 0;
        $map->clear();
        $this->assertCount(0, $map);
        $this->assertTrue($map->isEmpty());
        $this->assertTrue($map->getIterator()->isEmpty());
    }


    /**
     * @depends testOffsetSet
     * @depends testOffsetExists
     */
    function test__construct() {
        $map = new SortedMap(function ($a, $b) {
            return compare(abs($a), abs($b));
        });

        $this->assertFalse($map->offsetExists(0));

        $map[5] = 5;
        $map[8] = 8;
        $map[2] = 2;
        $this->assertFalse($map->offsetExists(0));
        $this->assertFalse($map->offsetExists(10));
        $this->assertFalse($map->offsetExists(-10));

        $this->assertTrue($map->offsetExists(5));
        $this->assertTrue($map->offsetExists(8));
        $this->assertTrue($map->offsetExists(2));
        $this->assertTrue($map->offsetExists(-5));
        $this->assertTrue($map->offsetExists(-8));
        $this->assertTrue($map->offsetExists(-2));

    }


}

<?php

namespace Ardent;

class SortedMapTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Ardent\SortedMap::insert
     * @covers \Ardent\SortedMap::get
     * @covers \Ardent\SortedMap::offsetSet
     * @covers \Ardent\SortedMap::offsetGet
     * @covers \Ardent\SortedMap::offsetExists
     * @covers \Ardent\SortedMap::count
     */
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
     * @covers \Ardent\SortedMap::offsetUnset
     * @covers \Ardent\SortedMap::remove
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
     * @covers \Ardent\SortedMap::isEmpty
     */
    function testIsEmpty() {
        $map = new SortedMap();
        $this->assertTrue($map->isEmpty());

        $map[0] = 0;
        $this->assertFalse($map->isEmpty());

        unset($map[0]);
        $this->assertTrue($map->isEmpty());
    }

    /**
     * @covers \Ardent\SortedMap::offsetGet
     * @expectedException \Ardent\KeyException
     */
    function testOffsetGetNotSetKey() {
        $map = new SortedMap();
        $map->offsetGet(0);
    }

    /**
     * @covers \Ardent\SortedMap::get
     * @expectedException \Ardent\KeyException
     */
    function testGetNotSetKey() {
        $map = new SortedMap();
        $map->get(0);
    }

    /**
     * @depends testOffsetSet
     * @depends testOffsetUnset
     * @covers \Ardent\SortedMap::offsetGet
     * @expectedException \Ardent\KeyException
     */
    function testOffsetGetUnsetKey() {
        $map = new SortedMap();
        $map[0] = 0;
        unset($map[0]);
        $map->offsetGet(0);
    }

    /**
     * @depends testOffsetSet
     * @depends testOffsetUnset
     * @covers \Ardent\SortedMap::get
     * @expectedException \Ardent\KeyException
     */
    function testGetUnsetKey() {
        $map = new SortedMap();
        $map[0] = 0;
        unset($map[0]);
        $map->get(0);
    }

    /**
     * @depends testOffsetSet
     * @covers \Ardent\SortedMap::contains
     */
    function testContains() {
        $map = new SortedMap();

        $this->assertFalse($map->contains(0));

        $map[0] = 1;
        $this->assertFalse($map->contains(0));
        $this->assertTrue($map->contains(1));
    }

    /**
     * @depends testOffsetSet
     * @covers \Ardent\SortedMap::offsetExists
     * @covers \Ardent\SortedMap::compareStandard
     * @covers \Ardent\SortedMap::compareKeys
     * @covers \Ardent\SortedMap::__construct
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
     * @covers \Ardent\SortedMap::clear
     * @covers \Ardent\SortedMap::count
     * @covers \Ardent\SortedMap::isEmpty
     */
    function testClear() {
        $map = new SortedMap();

        $map[0] = 0;
        $map->clear();
        $this->assertCount(0, $map);
        $this->assertTrue($map->isEmpty());
    }

    /**
     * @depends testOffsetSet
     * @depends testOffsetExists
     * @covers \Ardent\SortedMap::__construct
     */
    function test__construct() {
        $map = new SortedMap(function ($a, $b) {
            $map = new SortedMap();
            return $map->compareStandard(abs($a), abs($b));
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

    /**
     * @depends testOffsetSet
     */
    function testIterator() {
        $map = new SortedMap();
        $map[0] = 1;
        $map[2] = 3;
        $map[3] = 4;
        $map[1] = 2;


        $iterator = $map->getIterator();

        $this->assertInstanceOf('Ardent\\SortedMapIterator', $iterator);
        $this->assertCount(count($map), $iterator);

        $iterator->rewind();

        for ($i = 0; $i < count($map); $i++) {
            $this->assertTrue($iterator->valid());

            $this->assertEquals($i, $iterator->key());
            $expectedValue = $map[$i];
            $this->assertEquals($expectedValue, $iterator->current());

            $iterator->next();
        }

        $this->assertFalse($iterator->valid());
        $this->assertCount(count($map), $iterator);

        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());

        $iterator->next();
        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());
    }

    /**
     * @expectedException \Ardent\TypeException
     */
    function testBadIteratorKey() {
        $tree = new BinaryTree(0);
        $tree->setLeft(new BinaryTree(-1));
        $tree->setRight(new BinaryTree(1));

        $iterator = new SortedMapIterator(new InOrderIterator($tree), 3);
        $iterator->key();
    }

    /**
     * @expectedException \Ardent\TypeException
     */
    function testBadIteratorCurrent() {
        $tree = new BinaryTree(0);
        $tree->setLeft(new BinaryTree(-1));
        $tree->setRight(new BinaryTree(1));

        $iterator = new SortedMapIterator(new InOrderIterator($tree), 3);
        $iterator->current();
    }

}

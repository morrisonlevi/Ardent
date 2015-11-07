<?php

namespace Ardent\Collection;

class HashMapTest extends \PHPUnit_Framework_TestCase {



    function test_count_empty_returnsZero() {
        $map = new HashMap();
        $this->assertCount(0, $map);
    }


    function test_isEmpty_empty_returnsTrue() {
        $map = new HashMap();
        $this->assertTrue($map->isEmpty());
        $this->assertTrue($map->getIterator()->isEmpty());
    }


    function testOffsetSetOffsetGet() {
        $map = new HashMap();
        $this->assertCount(0, $map);
        $this->assertTrue($map->isEmpty());

        $map[0] = 2;
        $offsetGet = $map[0];
        $this->assertEquals(2, $offsetGet);
        $this->assertCount(1, $map);
        $this->assertFalse($map->isEmpty());

        $map[1] = 2;
        $offsetGet = $map[1];
        $this->assertEquals(2, $offsetGet);
        $this->assertCount(2, $map);
        $this->assertFalse($map->isEmpty());
    }

    function testOffsetUnsetEmpty() {
        $map = new HashMap();
        unset($map[0]);
    }


    /**
     * @depends testOffsetSetOffsetGet
     */
    function testOffsetUnset() {
        $map = new HashMap();
        $map[0] = 1;
        unset($map[0]);

        $this->assertCount(0, $map);
        $this->assertTrue($map->isEmpty());
    }


    /**
     * @depends testOffsetSetOffsetGet
     */
    function testOffsetExists() {
        $map = new HashMap();
        $this->assertFalse($map->offsetExists(0));

        $map[0] = 1;
        $this->assertTrue($map->offsetExists(0));
        $this->assertFalse($map->offsetExists(1));

        $map[1] = 2;
        $this->assertTrue($map->offsetExists(0));
        $this->assertTrue($map->offsetExists(1));
        $this->assertFalse($map->offsetExists(-1));
    }


    /**
     * @depends testOffsetUnset
     */
    function testContains() {
        $map = new HashMap();
        $this->assertFalse($map->contains(1));

        $map[0] = 2;
        $this->assertFalse($map->contains(0));
        $this->assertTrue($map->contains(2));

        $map[2] = 4;
        $this->assertTrue($map->contains(2));
        $this->assertTrue($map->contains(4));

        unset($map[0]);

        $this->assertFalse($map->contains(0));
        $this->assertFalse($map->contains(2));
        $this->assertTrue($map->contains(4));
    }


    /**
     * @depends testOffsetSetOffsetGet
     */
    function testHashInteger() {
        $map = new HashMap();
        $map[0] = 1;
        $this->assertEquals(1, $map[0]);
    }


    /**
     * @depends testOffsetSetOffsetGet
     */
    function testHashString() {
        $map = new HashMap();
        $map['hello'] = 'there';
        $this->assertEquals('there', $map['hello']);
    }


    /**
     * @depends testOffsetSetOffsetGet
     */
    function testHashResource() {
        $map = new HashMap();
        $resource = fopen(__FILE__, 'r');
        $map[$resource] = 1;
        $this->assertEquals(1, $map[$resource]);
        fclose($resource);
    }


    /**
     * @depends testOffsetSetOffsetGet
     */
    function testHashArray() {
        $map = new HashMap();
        $array = array(0);
        $map->offsetSet($array, 1);
        $this->assertEquals(1, $map->offsetGet($array));
    }


    /**
     * @depends testContains
     */
    function testHashObject() {
        $map = new HashMap();
        $object = new \StdClass;
        $map->offsetSet($object, 1);
        $object->property = 'set';
        $this->assertEquals(1, $map->offsetGet($object));

        $copy = clone $object;
        $this->assertFalse($map->contains($copy));
    }


    /**
     * @depends testOffsetSetOffsetGet
     */
    function testHashNull() {
        $map = new HashMap();
        $map->offsetSet(null, 1);
        $this->assertEquals(1, $map->offsetGet(null));
    }

}

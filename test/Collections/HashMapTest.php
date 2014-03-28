<?php

namespace Collections;

class HashMapTest extends \PHPUnit_Framework_TestCase {

    /**
     * @expectedException \Collections\KeyException
     */
    function testGetException() {
        $map = new HashMap();
        $map->get(0);
    }

    /**
     * @expectedException \Collections\KeyException
     */
    function testOffsetGetException() {
        $map = new HashMap();
        $map->offsetGet(0);
    }

    function testInsertGet() {
        $map = new HashMap();
        $this->assertCount(0, $map);
        $this->assertTrue($map->isEmpty());

        $map->set(0, 1);
        $get = $map->get(0);
        $this->assertEquals(1, $get);
        $this->assertCount(1, $map);
        $this->assertFalse($map->isEmpty());

        $map->set(1, 0);
        $get = $map->get(1);
        $this->assertEquals(0, $get);
        $this->assertCount(2, $map);
        $this->assertFalse($map->isEmpty());
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

    function testRemoveEmpty() {
        $map = new HashMap();
        $map->remove(0);
    }

    function testOffsetUnsetEmpty() {
        $map = new HashMap();
        unset($map[0]);
    }

    /**
     * @depends testInsertGet
     */
    function testRemove() {
        $map = new HashMap();
        $map->set(0, 1);
        $map->remove(0);

        $this->assertCount(0, $map);
        $this->assertTrue($map->isEmpty());
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
        $map['0'] = '1';
        $this->assertEquals('1', $map['0']);
    }

    /**
     * @depends testOffsetSetOffsetGet
     */
    function testHashResource() {
        $map = new HashMap();
        $resource = fopen(__FILE__, 'r');
        $map->set($resource, 1);
        $this->assertEquals(1, $map->get($resource));
    }

    /**
     * @depends testOffsetSetOffsetGet
     */
    function testHashArray() {
        $map = new HashMap();
        $array = array(0);
        $map->set($array, 1);
        $this->assertEquals(1, $map->get($array));
    }

    /**
     * @depends testContains
     */
    function testHashObject() {
        $map = new HashMap();
        $object = new \StdClass;
        $map->set($object, 1);
        $object->property = 'set';
        $this->assertEquals(1, $map->get($object));

        $copy = clone $object;
        $this->assertFalse($map->contains($copy));
    }

    /**
     * @depends testOffsetSetOffsetGet
     */
    function testHashNull() {
        $map = new HashMap();
        $map->set(NULL, 1);
        $this->assertEquals(1, $map->get(NULL));
    }

    /**
     * @depends testOffsetSetOffsetGet
     */
    function testIteratorEmpty() {
        $map = new HashMap();
        $iterator = $map->getIterator();
        $this->assertInstanceOf('\\Collections\\HashMapIterator', $iterator);

        $iterator->rewind();
        $this->assertFalse($iterator->valid());
    }

    /**
     * @depends testIteratorEmpty
     */
    function testIterator() {
        $map = new HashMap();
        $map[0] = 2;
        $map[2] = 4;
        $map[4] = 6;

        $iterator = $map->getIterator();
        $this->assertCount(3, $iterator);

        $iterator->rewind();
        for ($i = 0; $i <= 4; $i += 2) {
            $this->assertTrue($iterator->valid());
            $value = $i + 2;
            $this->assertEquals($i, $iterator->key());
            $this->assertEquals($value, $iterator->current());
            $iterator->next();
        }

        $this->assertFalse($iterator->valid());

    }

}

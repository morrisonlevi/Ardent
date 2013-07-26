<?php
namespace Ardent;

use StdClass;

class HashSetTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var HashSet
     */
    protected $object;

    protected function setUp() {
        $this->object = new HashSet;
    }

    function testAdd() {
        $this->object->add(new StdClass());
        $this->assertCount(1, $this->object);

        $item2 = new StdClass();
        $this->object->add($item2);
        $this->assertCount(2, $this->object);

        $this->object->add($item2);
        $this->assertCount(2, $this->object);
    }

    /**
     * @depends testAdd
     */
    function testRemove() {
        $item = new StdClass();
        $this->object->add($item);
        $this->object->remove($item);

        $this->assertCount(0, $this->object);

        $item2 = new StdClass();
        $this->object->add($item);
        $this->object->add($item2);
        $this->object->remove($item);

        $this->assertCount(1, $this->object);
    }

    /**
     * @depends testAdd
     */
    function testClear() {
        $this->object->add(0);
        $this->object->clear();

        $this->assertCount(0, $this->object);
    }

    /**
     * @depends testAdd
     */
    function testContains() {
        $scalar = 0;
        $this->assertFalse($this->object->contains($scalar));
        $this->object->add($scalar);
        $this->assertTrue($this->object->contains($scalar));
        $this->assertTrue($this->object->contains('0'));

        $object = new StdClass();
        $this->assertFalse($this->object->contains($object));
        $this->object->add($object);
        $this->assertTrue($this->object->contains($object));

        $resource = fopen(__FILE__, 'r');
        $this->assertFalse($this->object->contains($resource));
        $this->object->add($resource);
        $this->assertTrue($this->object->contains($resource));
        fclose($resource);

        $emptyArray = array();
        $this->assertFalse($this->object->contains($emptyArray));
        $this->object->add($emptyArray);
        $this->assertTrue($this->object->contains($emptyArray));

        $array = array(0, 1);
        $this->assertFalse($this->object->contains($array));
        $this->object->add($array);
        $this->assertTrue($this->object->contains($array));

        $null = NULL;

        $this->assertFalse($this->object->contains($null));
        $this->object->add($null);
        $this->assertTrue($this->object->contains($null));

    }

    /**
     * @depends testAdd
     */
    function testIsEmpty() {
        $this->assertTrue($this->object->isEmpty());

        $this->object->add(0);
        $this->assertFalse($this->object->isEmpty());
    }

    /**
     * @expectedException \Ardent\Exception\FunctionException
     */
    function testContainsException() {
        $obj = new HashSet(function($item) {return array($item);});

        $obj->contains($obj);
    }

    /**
     * @expectedException \Ardent\Exception\FunctionException
     */
    function testAddException() {
        $obj = new HashSet(function($item) {return array($item);});

        $obj->add($obj);
    }

    /**
     * @expectedException \Ardent\Exception\FunctionException
     */
    function testRemoveException() {
        $obj = new HashSet(function($item) {return array($item);});

        $obj->remove($obj);
    }

    /**
     * @depends testAdd
     */
    function testDifferenceSelf() {
        $a = new HashSet();
        $a->add(0);

        $diff = $a->difference($a);
        $this->assertInstanceOf('Ardent\\HashSet', $diff);
        $this->assertNotSame($diff, $a);
        $this->assertCount(0, $diff);
    }

    /**
     * @depends testAdd
     */
    function testIteratorEmpty() {
        $set = new HashSet();

        $iterator = $set->getIterator();
        $this->assertInstanceOf('Ardent\\Iterator\\HashSetIterator', $iterator);
        $this->assertCount(0, $iterator);
        $this->assertFalse($iterator->valid());
        $iterator->rewind();
        $this->assertFalse($iterator->valid());
        $iterator->next();
        $this->assertFalse($iterator->valid());

    }

    /**
     * @depends testAdd
     */
    function testIterator() {
        $set = new HashSet();
        $set->add(0);

        $iterator = $set->getIterator();
        $this->assertInstanceOf('Ardent\\Iterator\\HashSetIterator', $iterator);
        $this->assertCount(count($set), $iterator);

        $this->assertTrue($iterator->valid());
        $this->assertEquals(0, $iterator->key());
        $this->assertEquals(0, $iterator->current());

        $set->add(1);
        $set->add(2);
        $set->add(3);


        $iterator = $set->getIterator();
        $this->assertCount(count($set), $iterator);
        $usedValues = new HashSet();
        for ($i = 0; $i < count($set); $i++) {
            $this->assertTrue($iterator->valid());

            $this->assertEquals($i, $iterator->key());
            $this->assertFalse($usedValues->contains($iterator->current()));
            $usedValues->add($iterator->current());

            $iterator->next();
        }

        $this->assertFalse($iterator->valid());
        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());
        $iterator->next();
        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());

    }

}

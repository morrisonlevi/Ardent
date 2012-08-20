<?php

namespace Spl;

class VectorTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var Vector
     */
    protected $object;

    protected function setUp() {
        $this->object = new Vector;
    }

    /**
     * @covers Spl\Vector::clear
     */
    public function testClear() {
        $this->object[] = 1;
        $this->object->clear();

        $this->assertCount(0, $this->object);
    }

    /**
     * @covers Spl\Vector::contains
     */
    public function testContains() {
        $this->assertFalse($this->object->contains(1));

        $this->object[] = 1;

        $this->assertTrue($this->object->contains(1));
    }

    /**
     * @covers Spl\Vector::isEmpty
     */
    public function testIsEmpty() {
        $this->assertTrue($this->object->isEmpty());

        $this->object[] = 1;
        $this->assertFalse($this->object->isEmpty());
    }

    /**
     * @covers Spl\Vector::offsetExists
     */
    public function testOffsetExists() {
        $this->assertFalse($this->object->offsetExists(0));

        $this->object[] = 1;

        $this->assertTrue($this->object->offsetExists(0));
        $this->assertFalse($this->object->offsetExists(1));

    }

    /**
     * @covers Spl\Vector::offsetGet
     * @expectedException Spl\OutOfBoundsException
     */
    public function testOffsetGetOutOfBoundsException() {
        $this->object[0];
    }

    /**
     * @covers Spl\Vector::offsetGet
     * @expectedException Spl\TypeException
     */
    public function testOffsetGetTypeException() {
        $this->object[new \StdClass()];
    }

    /**
     * @covers Spl\Vector::offsetSet
     */
    public function testOffsetSet() {
        $this->object[] = 1;
        $this->object[0] = 0;
    }

    /**
     * @covers Spl\Vector::offsetUnset
     * @covers Spl\Vector::count
     */
    public function testOffsetUnset() {
        unset($this->object[0]);

        $this->object[] = 1;
        unset($this->object[0]);

        $this->assertCount(0, $this->object);
    }


    /**
     * @covers Spl\Vector::append
     * @todo   Implement testAppend().
     */
    public function testAppend() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\Vector::get
     * @todo   Implement testGet().
     */
    public function testGet() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\Vector::set
     * @todo   Implement testSet().
     */
    public function testSet() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\Vector::remove
     * @todo   Implement testRemove().
     */
    public function testRemove() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\Vector::removeObject
     * @todo   Implement testRemoveObject().
     */
    public function testRemoveObject() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\Vector::removeAll
     * @todo   Implement testRemoveAll().
     */
    public function testRemoveAll() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\Vector::filter
     * @todo   Implement testFilter().
     */
    public function testFilter() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\Vector::slice
     * @todo   Implement testSlice().
     */
    public function testSlice() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\Vector::toArray
     * @todo   Implement testToArray().
     */
    public function testToArray() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\Vector::getIterator
     * @todo   Implement testGetIterator().
     */
    public function testGetIterator() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

}

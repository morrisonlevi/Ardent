<?php
namespace Spl;

class StdVectorTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var StdVector
     */
    protected $object;

    protected function setUp() {
        $this->object = new StdVector;
    }

    /**
     * @covers Spl\StdVector::clear
     */
    public function testClear() {
        $this->object[] = 1;
        $this->object->clear();

        $this->assertCount(0, $this->object);
    }

    /**
     * @covers Spl\StdVector::contains
     */
    public function testContains() {
        $this->assertFalse($this->object->contains(1));

        $this->object[] = 1;

        $this->assertTrue($this->object->contains(1));
    }

    /**
     * @covers Spl\StdVector::isEmpty
     */
    public function testIsEmpty() {
        $this->assertTrue($this->object->isEmpty());

        $this->object[] = 1;
        $this->assertFalse($this->object->isEmpty());
    }

    /**
     * @covers Spl\StdVector::offsetExists
     */
    public function testOffsetExists() {
        $this->assertFalse($this->object->offsetExists(0));

        $this->object[] = 1;

        $this->assertTrue($this->object->offsetExists(0));
        $this->assertFalse($this->object->offsetExists(1));

    }

    /**
     * @covers Spl\StdVector::offsetGet
     * @expectedException Spl\OutOfBoundsException
     */
    public function testOffsetGetOutOfBoundsException() {
        $this->object[0];
    }

    /**
     * @covers Spl\StdVector::offsetGet
     * @expectedException Spl\TypeException
     */
    public function testOffsetGetTypeException() {
        $this->object[new \StdClass()];
    }

    /**
     * @covers Spl\StdVector::offsetSet
     */
    public function testOffsetSet() {
        $this->object[] = 1;
        $this->object[0] = 0;
    }

    /**
     * @covers Spl\StdVector::offsetUnset
     * @covers Spl\StdVector::count
     */
    public function testOffsetUnset() {
        unset($this->object[0]);

        $this->object[] = 1;
        unset($this->object[0]);

        $this->assertCount(0, $this->object);
    }


    /**
     * @covers Spl\StdVector::append
     * @todo   Implement testAppend().
     */
    public function testAppend() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\StdVector::get
     * @todo   Implement testGet().
     */
    public function testGet() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\StdVector::set
     * @todo   Implement testSet().
     */
    public function testSet() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\StdVector::remove
     * @todo   Implement testRemove().
     */
    public function testRemove() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\StdVector::removeObject
     * @todo   Implement testRemoveObject().
     */
    public function testRemoveObject() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\StdVector::removeAll
     * @todo   Implement testRemoveAll().
     */
    public function testRemoveAll() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\StdVector::slice
     * @todo   Implement testSlice().
     */
    public function testSlice() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\StdVector::toArray
     * @todo   Implement testToArray().
     */
    public function testToArray() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\StdVector::current
     * @todo   Implement testCurrent().
     */
    public function testCurrent() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\StdVector::next
     * @todo   Implement testNext().
     */
    public function testNext() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\StdVector::key
     * @todo   Implement testKey().
     */
    public function testKey() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\StdVector::valid
     * @todo   Implement testValid().
     */
    public function testValid() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Spl\StdVector::rewind
     * @todo   Implement testRewind().
     */
    public function testRewind() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}

<?php

namespace Spl;

class VectorMock extends Vector {
    public function &getInnerArray() {
        return $this->array;
    }
}

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
        $this->object->append(1);
        $this->object->clear();

        $this->assertCount(0, $this->object);
    }

    /**
     * @covers Spl\Vector::contains
     */
    public function testContains() {
        $this->assertFalse($this->object->contains(1));

        $this->object->append(1);

        $this->assertTrue($this->object->contains(1));
    }

    /**
     * @covers Spl\Vector::isEmpty
     */
    public function testIsEmpty() {
        $this->assertTrue($this->object->isEmpty());

        $this->object->append(1);
        $this->assertFalse($this->object->isEmpty());
    }

    /**
     * @covers Spl\Vector::offsetExists
     */
    public function testOffsetExists() {
        $this->assertFalse($this->object->offsetExists(0));

        $this->object->append(1);

        $this->assertTrue($this->object->offsetExists(0));
        $this->assertFalse($this->object->offsetExists(1));

    }

    /**
     * @covers Spl\Vector::offsetGet
     * @expectedException \Spl\IndexException
     */
    public function testOffsetGetOutOfBoundsException() {
        $this->object[0];
    }

    /**
     * @covers Spl\Vector::offsetGet
     * @expectedException \Spl\TypeException
     */
    public function testOffsetGetTypeException() {
        $this->object->get(new \StdClass);
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
     */
    public function testAppend() {
        $vector = new VectorMock;
        $vector->append(1);
        $this->assertCount(1, $vector);

        $vector->append(2);
        $this->assertCount(2, $vector);

        $array =& $vector->getInnerArray();
        $this->assertEquals(1, $array[0]);
        $this->assertEquals(2, $array[1]);
    }

    /**
     * @covers Spl\Vector::get
     */
    public function testGet() {
        $vector = new VectorMock;
        $array =& $vector->getInnerArray();

        for ($i = 0; $i < 3; $i++) {
            $array[$i] = $i;
            $this->assertEquals($i, $vector->get($i));
        }
    }

    /**
     * @covers Spl\Vector::set
     */
    public function testSet() {
        $vector = new VectorMock;
        $array =& $vector->getInnerArray();

        for ($i = 0; $i < 3; $i++) {
            $vector->append($i * 2);
            $vector->set($i, $i);
            $this->assertEquals($i, $array[$i]);
        }
    }

    /**
     * @covers Spl\Vector::remove
     */
    public function testRemove() {
        $vector = new VectorMock(0);
        $array =& $vector->getInnerArray();

        $vector->remove(0);

        $this->assertCount(0, $vector);

        $array[0] = 0;
        $array[1] = 1;

        $vector->remove(0);
        $this->assertCount(1, $vector);
        $this->assertEquals(1, $array[0]);

        //test no-harm done on invalid index
        $vector->remove(1);
        $this->assertCount(1, $vector);
        $this->assertEquals(1, $array[0]);
    }

    /**
     * @covers Spl\Vector::__construct
     * @covers Spl\Vector::removeItem
     */
    public function testRemoveItem() {
        $vector = new VectorMock(0, 2, 4);
        $array =& $vector->getInnerArray();

        $vector->removeItem(2);

        $this->assertCount(2, $vector);
        $this->assertEquals(0, $array[0]);
        $this->assertEquals(4, $array[1]);

        // test for no-harm done on missing

        $vector->removeItem(2);

        $this->assertCount(2, $vector);
        $this->assertEquals(0, $array[0]);
        $this->assertEquals(4, $array[1]);
    }

    /**
     * @covers Spl\Vector::filter
     */
    public function testFilter() {
        $vector = new VectorMock();
        $array =& $vector->getInnerArray();

        for ($i = 0; $i < 5; $i++) {
            $array[$i] = $i;
        }

        $filtered = $vector->filter(function() {return TRUE;});
        $this->assertInstanceOf('Spl\\Vector', $filtered);
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals($i, $array[$i]);
            $this->assertEquals($i, $filtered[$i]);
        }

        $filtered = $vector->filter(function($item, $index) {return $index % 2;});
        $this->assertInstanceOf('Spl\\Vector', $filtered);
        $this->assertCount(2, $filtered);
        for ($i = 0, $j = 1; $i < 2; $i++, $j += 2) {
            $this->assertEquals($j, $filtered[$i]);
        }

    }

    /**
     * @depends testGet
     * @covers Spl\Vector::slice
     */
    public function testSlice() {
        $vector = new VectorMock(0,1,2,3);

        $slice = $vector->slice(1);
        $this->assertInstanceOf('Spl\\Vector', $slice);
        $this->assertCount(3, $slice);
        for ($i = 0; $i < 3; $i++) {
            $this->assertEquals($i+1, $slice[$i]);
        }

        $slice = $vector->slice(0, 1);
        $this->assertInstanceOf('Spl\\Vector', $slice);
        $this->assertCount(1, $slice);
        $this->assertEquals(0, $slice[0]);
    }

    /**
     * @covers Spl\Vector::toArray
     */
    public function testToArray() {
        $emptyArray = $this->object->toArray();
        $this->assertTrue(is_array($emptyArray));
        $this->assertCount(0, $emptyArray);

        $this->object->append(1);
        $this->object->append(5);
        $this->object->append(3);

        $notEmptyArray = $this->object->toArray();
        $this->assertTrue(is_array($notEmptyArray));
        $this->assertCount(3, $notEmptyArray);
    }

    /**
     * @covers Spl\Vector::getIterator
     */
    public function testGetIterator() {
        $iterator = $this->object->getIterator();

        $this->assertInstanceOf('\\Spl\\VectorIterator', $iterator);
    }

}

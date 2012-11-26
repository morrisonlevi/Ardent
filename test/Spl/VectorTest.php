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
     * @covers \Spl\Vector::clear
     */
    function testClear() {
        $this->object->append(1);
        $this->object->clear();

        $this->assertCount(0, $this->object);
    }

    /**
     * @covers \Spl\Vector::contains
     */
    function testContains() {
        $this->assertFalse($this->object->contains(1));

        $this->object->append(1);

        $this->assertTrue($this->object->contains(1));
    }

    /**
     * @covers \Spl\Vector::isEmpty
     */
    function testIsEmpty() {
        $this->assertTrue($this->object->isEmpty());

        $this->object->append(1);
        $this->assertFalse($this->object->isEmpty());
    }

    /**
     * @covers \Spl\Vector::offsetExists
     */
    function testOffsetExists() {
        $this->assertFalse($this->object->offsetExists(0));

        $this->object->append(1);

        $this->assertTrue($this->object->offsetExists(0));
        $this->assertFalse($this->object->offsetExists(1));

    }

    /**
     * @covers \Spl\Vector::offsetGet
     * @expectedException \Spl\IndexException
     */
    function testOffsetGetOutOfBoundsException() {
        $this->object[0];
    }

    /**
     * @covers \Spl\Vector::offsetGet
     * @expectedException \Spl\TypeException
     */
    function testOffsetGetTypeException() {

        $this->object->get(array());
    }

    /**
     * @covers \Spl\Vector::offsetSet
     */
    function testOffsetSet() {
        $this->object[] = 1;
        $this->object[0] = 0;
    }

    /**
     * @covers \Spl\Vector::offsetUnset
     * @covers \Spl\Vector::count
     */
    function testOffsetUnset() {
        unset($this->object[0]);

        $this->object[] = 1;
        unset($this->object[0]);

        $this->assertCount(0, $this->object);
    }


    /**
     * @covers \Spl\Vector::append
     */
    function testAppend() {
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
     * @covers \Spl\Vector::get
     */
    function testGet() {
        $vector = new VectorMock;
        $array =& $vector->getInnerArray();

        for ($i = 0; $i < 3; $i++) {
            $array[$i] = $i;
            $this->assertEquals($i, $vector->get($i));
        }
    }

    /**
     * @covers \Spl\Vector::get
     * @expectedException \Spl\TypeException
     */
    function testGetNonInteger() {
        $vector = new Vector();
        $vector->get(array());
    }

    /**
     * @covers \Spl\Vector::get
     * @expectedException \Spl\IndexException
     */
    function testGetNonExistentOffset() {
        $vector = new Vector();
        $vector->get(1);
    }

    /**
     * @covers \Spl\Vector::set
     */
    function testSet() {
        $vector = new VectorMock;
        $array =& $vector->getInnerArray();

        for ($i = 0; $i < 3; $i++) {
            $vector->append($i * 2);
            $vector->set($i, $i);
            $this->assertEquals($i, $array[$i]);
        }
    }

    /**
     * @covers \Spl\Vector::set
     * @expectedException \Spl\TypeException
     */
    function testSetNonInteger() {
        $vector = new Vector();
        $vector->set(array(), 0);
    }

    /**
     * @covers \Spl\Vector::set
     * @expectedException \Spl\IndexException
     */
    function testSetNonExistentOffset() {
        $vector = new Vector();
        $vector->set(1, 0);
    }

    /**
     * @covers \Spl\Vector::remove
     */
    function testRemove() {
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
     * @covers \Spl\Vector::remove
     * @expectedException \Spl\TypeException
     */
    function testRemoveNonInteger() {
        $vector = new Vector();
        $vector->remove(array());
    }

    /**
     * @covers \Spl\Vector::__construct
     * @covers \Spl\Vector::removeItem
     */
    function testRemoveItem() {
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
     * @covers \Spl\Vector::filter
     */
    function testFilter() {
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
     * @covers \Spl\Vector::filter
     * @expectedException \Spl\TypeException
     */
    function testFilterNotCallable() {
        $vector = new Vector();
        $vector->append(0);
        $vector->filter('happy_hour_is_not_callable');
    }

    /**
     * @depends testGet
     * @covers \Spl\Vector::slice
     */
    function testSlice() {
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
     * @covers \Spl\Vector::slice
     * @expectedException \Spl\EmptyException
     */
    function testSliceEmpty() {
        $vector = new Vector();
        $vector->slice(0);
    }

    /**
     * @covers \Spl\Vector::slice
     * @expectedException \Spl\TypeException
     */
    function testSliceNonIntegerStart() {
        $vector = new Vector(0);
        $vector->slice(array());
    }

    /**
     * @covers \Spl\Vector::slice
     * @expectedException \Spl\TypeException
     */
    function testSliceNonIntegerCount() {
        $vector = new Vector(0);
        $vector->slice(0, array());
    }

    /**
     * @covers \Spl\Vector::slice
     * @expectedException \Spl\IndexException
     */
    function testSliceStartGreaterThanCount() {
        $vector = new Vector(0);
        $vector->slice(1);
    }

    /**
     * @covers \Spl\Vector::slice
     * @expectedException \Spl\IndexException
     */
    function testSliceStartLessThanNegativeCount() {
        $vector = new Vector(0);
        $vector->slice(-2);
    }

    /**
     * @covers \Spl\Vector::toArray
     */
    function testToArray() {
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
     * @covers \Spl\Vector::getIterator
     */
    function testGetIterator() {
        $iterator = $this->object->getIterator();

        $this->assertInstanceOf('\\Spl\\VectorIterator', $iterator);
    }

    /**
     * @covers \Spl\Vector::map
     */
    function testMap() {
        $vector = new Vector(0, 1, 2, 3);
        $size = $vector->count();

        $doubleValue = function ($value) {
            return $value * 2;
        };

        $map = $vector->map($doubleValue);

        $this->assertInstanceOf('Spl\\Vector', $map);
        $this->assertCount(4, $map);

        for ($i = 0; $i < $size; $i++) {
            $this->assertEquals($doubleValue($vector[$i]), $map[$i]);
        }
    }

    /**
     * @covers \Spl\Vector::map
     * @expectedException \Spl\TypeException
     */
    function testMapNotCallable() {
        $vector = new Vector();
        $vector->map('happy_hour_is_not_callable');
    }

    /**
     * @covers \Spl\Vector::apply
     */
    function testApply() {
        $vector = new Vector(0, 1, 2, 3);
        $size = $vector->count();

        $doubleValue = function ($value) {
            return $value * 2;
        };

        $vector->apply($doubleValue);

        $this->assertInstanceOf('Spl\\Vector', $vector);
        $this->assertCount(4, $vector);

        for ($i = 0; $i < $size; $i++) {
            $this->assertEquals($doubleValue($i), $vector[$i]);
        }
    }
    /**
     * @covers \Spl\Vector::apply
     * @expectedException \Spl\TypeException
     */
    function testApplyNotCallable() {
        $vector = new Vector();
        $vector->apply('happy_hour_is_not_callable');
    }

}

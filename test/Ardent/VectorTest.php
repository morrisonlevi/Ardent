<?php

namespace Ardent;

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
     * @covers \Ardent\Vector::clear
     */
    function testClear() {
        $this->object->append(1);
        $this->object->clear();

        $this->assertCount(0, $this->object);
    }

    /**
     * @covers \Ardent\Vector::contains
     */
    function testContains() {
        $this->assertFalse($this->object->contains(1));

        $this->object->append(1);

        $this->assertTrue($this->object->contains(1));
    }

    /**
     * @covers \Ardent\Vector::isEmpty
     */
    function testIsEmpty() {
        $this->assertTrue($this->object->isEmpty());

        $this->object->append(1);
        $this->assertFalse($this->object->isEmpty());
    }

    /**
     * @covers \Ardent\Vector::offsetExists
     */
    function testOffsetExists() {
        $this->assertFalse($this->object->offsetExists(0));

        $this->object->append(1);

        $this->assertTrue($this->object->offsetExists(0));
        $this->assertFalse($this->object->offsetExists(1));

    }

    /**
     * @covers \Ardent\Vector::offsetGet
     * @expectedException \Ardent\IndexException
     */
    function testOffsetGetOutOfBoundsException() {
        $this->object[0];
    }

    /**
     * @covers \Ardent\Vector::offsetGet
     * @expectedException \Ardent\TypeException
     */
    function testOffsetGetTypeException() {

        $this->object->get(array());
    }

    /**
     * @covers \Ardent\Vector::offsetSet
     */
    function testOffsetSet() {
        $this->object[] = 1;
        $this->object[0] = 0;
    }

    /**
     * @covers \Ardent\Vector::offsetUnset
     * @covers \Ardent\Vector::count
     */
    function testOffsetUnset() {
        unset($this->object[0]);

        $this->object[] = 1;
        unset($this->object[0]);

        $this->assertCount(0, $this->object);
    }

    function testAppendAll() {
        $data = [0, 2, 4, 6];
        $traversable = new \ArrayIterator($data);

        $vector = new VectorMock;
        $vector->append(0);
        $vector->appendAll($traversable);
        $this->assertCount(5, $vector);

        $i = 0;
        $expect = [0, 0, 2, 4, 6];
        foreach ($vector as $key => $value) {
            $this->assertEquals($i, $key);
            $this->assertEquals($expect[$i], $value);
            $i++;
        }
        $this->assertEquals(count($expect), $i);
    }

    /**
     * @covers \Ardent\Vector::append
     * @covers \Ardent\Vector::count
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
     * @covers \Ardent\Vector::get
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
     * @covers \Ardent\Vector::get
     * @expectedException \Ardent\TypeException
     */
    function testGetNonInteger() {
        $vector = new Vector();
        $vector->get(array());
    }

    /**
     * @covers \Ardent\Vector::get
     * @expectedException \Ardent\IndexException
     */
    function testGetNonExistentOffset() {
        $vector = new Vector();
        $vector->get(1);
    }

    /**
     * @covers \Ardent\Vector::set
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
     * @covers \Ardent\Vector::set
     * @expectedException \Ardent\TypeException
     */
    function testSetNonInteger() {
        $vector = new Vector();
        $vector->set(array(), 0);
    }

    /**
     * @covers \Ardent\Vector::set
     * @expectedException \Ardent\IndexException
     */
    function testSetNonExistentOffset() {
        $vector = new Vector();
        $vector->set(1, 0);
    }

    /**
     * @covers \Ardent\Vector::remove
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
     * @covers \Ardent\Vector::remove
     * @expectedException \Ardent\TypeException
     */
    function testRemoveNonInteger() {
        $vector = new Vector();
        $vector->remove(array());
    }

    /**
     * @covers \Ardent\Vector::__construct
     * @covers \Ardent\Vector::removeItem
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
     * @covers \Ardent\Vector::filter
     */
    function testFilter() {
        $vector = new VectorMock();
        $array =& $vector->getInnerArray();

        for ($i = 0; $i < 5; $i++) {
            $array[$i] = $i;
        }

        $filtered = $vector->filter(function() {return TRUE;});
        $this->assertInstanceOf('Ardent\\Vector', $filtered);
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals($i, $array[$i]);
            $this->assertEquals($i, $filtered[$i]);
        }

        $filtered = $vector->filter(function($item, $index) {return $index % 2;});
        $this->assertInstanceOf('Ardent\\Vector', $filtered);
        $this->assertCount(2, $filtered);
        for ($i = 0, $j = 1; $i < 2; $i++, $j += 2) {
            $this->assertEquals($j, $filtered[$i]);
        }

    }

    /**
     * @depends testGet
     * @covers \Ardent\Vector::slice
     */
    function testSlice() {
        $vector = new VectorMock(0,1,2,3);

        $slice = $vector->slice(1);
        $this->assertInstanceOf('Ardent\\Vector', $slice);
        $this->assertCount(3, $slice);
        for ($i = 0; $i < 3; $i++) {
            $this->assertEquals($i+1, $slice[$i]);
        }

        $slice = $vector->slice(0, 1);
        $this->assertInstanceOf('Ardent\\Vector', $slice);
        $this->assertCount(1, $slice);
        $this->assertEquals(0, $slice[0]);
    }

    /**
     * @covers \Ardent\Vector::slice
     * @expectedException \Ardent\EmptyException
     */
    function testSliceEmpty() {
        $vector = new Vector();
        $vector->slice(0);
    }

    /**
     * @covers \Ardent\Vector::slice
     * @expectedException \Ardent\TypeException
     */
    function testSliceNonIntegerStart() {
        $vector = new Vector(0);
        $vector->slice(array());
    }

    /**
     * @covers \Ardent\Vector::slice
     * @expectedException \Ardent\TypeException
     */
    function testSliceNonIntegerCount() {
        $vector = new Vector(0);
        $vector->slice(0, array());
    }

    /**
     * @covers \Ardent\Vector::slice
     * @expectedException \Ardent\IndexException
     */
    function testSliceStartGreaterThanCount() {
        $vector = new Vector(0);
        $vector->slice(1);
    }

    /**
     * @covers \Ardent\Vector::slice
     * @expectedException \Ardent\IndexException
     */
    function testSliceStartLessThanNegativeCount() {
        $vector = new Vector(0);
        $vector->slice(-2);
    }

    /**
     * @covers \Ardent\Vector::toArray
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
     * @covers \Ardent\Vector::map
     */
    function testMap() {
        $vector = new Vector(0, 1, 2, 3);
        $size = $vector->count();

        $doubleValue = function ($value) {
            return $value * 2;
        };

        $map = $vector->map($doubleValue);

        $this->assertInstanceOf('Ardent\\Vector', $map);
        $this->assertCount(4, $map);

        for ($i = 0; $i < $size; $i++) {
            $this->assertEquals($doubleValue($vector[$i]), $map[$i]);
        }
    }

    /**
     * @covers \Ardent\Vector::apply
     */
    function testApply() {
        $vector = new Vector(0, 1, 2, 3);
        $size = $vector->count();

        $doubleValue = function ($value) {
            return $value * 2;
        };

        $vector->apply($doubleValue);

        $this->assertInstanceOf('Ardent\\Vector', $vector);
        $this->assertCount(4, $vector);

        for ($i = 0; $i < $size; $i++) {
            $this->assertEquals($doubleValue($i), $vector[$i]);
        }
    }

    /**
     * @depends testAppend
     * @covers \Ardent\Vector::getIterator
     * @covers \Ardent\VectorIterator::__construct
     * @covers \Ardent\VectorIterator::rewind
     * @covers \Ardent\VectorIterator::valid
     * @covers \Ardent\VectorIterator::key
     * @covers \Ardent\VectorIterator::current
     * @covers \Ardent\VectorIterator::next
     * @covers \Ardent\VectorIterator::count
     */
    function testIterator() {
        $this->object->append(1);
        $this->object->append(2);
        $this->object->append(3);
        $this->object->append(4);


        $iterator = $this->object->getIterator();
        $this->assertInstanceOf('\\Ardent\\VectorIterator', $iterator);
        $this->assertCount(count($this->object), $iterator);

        for ($i = 0; $i < count($this->object); $i++) {
            $this->assertTrue($iterator->valid());
            $this->assertEquals($i, $iterator->key());
            $this->assertEquals($i + 1, $iterator->current());

            $iterator->next();
        }

        $this->assertFalse($iterator->valid());

        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());

        $iterator->next();
        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());
        $this->assertCount(count($this->object), $iterator);

    }

    /**
     * @depends testIterator
     * @covers \Ardent\VectorIterator::seek
     */
    function testIteratorSeek() {
        $this->object->append(1);
        $this->object->append(2);
        $this->object->append(3);
        $this->object->append(4);

        $iterator = $this->object->getIterator();

        $iterator->seek(1);
        $this->assertEquals(1, $iterator->key());
        $this->assertEquals(2, $iterator->current());

        $iterator->seek(3);
        $this->assertEquals(3, $iterator->key());
        $this->assertEquals(4, $iterator->current());

        $iterator->seek(0);
        $this->assertEquals(0, $iterator->key());
        $this->assertEquals(1, $iterator->current());

        $iterator->seek(2);
        $this->assertEquals(2, $iterator->key());
        $this->assertEquals(3, $iterator->current());

        $iterator->seek(2);
        $this->assertEquals(2, $iterator->key());
        $this->assertEquals(3, $iterator->current());

        $iterator->seek(1);
        $this->assertEquals(1, $iterator->key());
        $this->assertEquals(2, $iterator->current());

        $iterator->seek(2);
        $this->assertEquals(2, $iterator->key());
        $this->assertEquals(3, $iterator->current());
    }

    /**
     * @depends testIterator
     * @covers \Ardent\VectorIterator::seek
     * @expectedException \Ardent\IndexException
     */
    function testIteratorSeekNegative() {
        $this->object->append(0);

        $iterator = $this->object->getIterator();
        $iterator->seek(-1);
    }

    /**
     * @depends testIterator
     * @covers \Ardent\VectorIterator::seek
     * @expectedException \Ardent\IndexException
     */
    function testIteratorSeekBeyondEnd() {
        $this->object->append(0);

        $iterator = $this->object->getIterator();
        $iterator->seek(1);
    }

}

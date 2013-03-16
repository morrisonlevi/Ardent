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
        $contains1 = function ($item) {
           return $item == 1;
        };
        $this->assertFalse($this->object->contains($contains1));

        $this->object->append(1);

        $this->assertTrue($this->object->contains($contains1));
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

        $vector = new Vector;
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

    function testMapToArray() {
        $map = [1, 2, 3, 4];
        $vector = new Vector();
        $vector->appendAll(new \ArrayIterator($map));

        $this->assertEquals($map, $vector->toArray());
        $this->assertEquals($map, $vector->toArray(TRUE));
    }

    function testContainsEmpty() {
        $vector = new Vector();
        $this->assertFalse($vector->contains(function () {
            return TRUE;
        }));
    }

    function testContainsFalse() {
        $vector = new Vector(1, 2, 3, 4, 5);
        $this->assertFalse($vector->contains(function ($val) {
            return $val === 0;
        }));
    }

    function testContainsFirst() {
        $vector = new Vector(1, 2, 3, 4, 5);
        $this->assertTrue($vector->contains(function ($val) {
            return $val === 1;
        }));
    }

    function testContainsMiddle() {
        $vector = new Vector(1, 2, 3, 4, 5);
        $this->assertTrue($vector->contains(function ($val) {
            return $val === 3;
        }));
    }

    function testContainsLast() {
        $vector = new Vector(1, 2, 3, 4, 5);
        $this->assertTrue($vector->contains(function ($val) {
            return $val === 5;
        }));
    }

    function testEachEmpty() {
        $vector = new Vector();
        $i = 0;
        $vector->each(function ($val) use (&$i) {
            $i += $val;
        });

        $this->assertEquals(0, $i);
    }

    function testEach() {
        $vector = new Vector(1, 2, 3);

        $k = '';
        $v = 0;
        $vector->each(function ($val, $key) use (&$v, &$k) {
            $k .= $key;
            $v += $val;
        });

        $this->assertEquals('012', $k);
        $this->assertEquals(6, $v);
    }

    function testEveryEmpty() {
        $vector = new Vector();
        $this->assertTrue($vector->every('is_null'));
    }

    function testEverySome() {
        $vector = new Vector(NULL, NULL, 0);
        $this->assertFalse($vector->every(function ($val) { return $val === NULL;}));
    }

    function testEvery() {
        $vector = new Vector(NULL, NULL, NULL);
        $this->assertTrue($vector->every(function ($val) { return $val === NULL;}));
    }

    function testJoinEmpty() {
        $vector = new Vector();
        $this->assertEquals('', $vector->join(','));
    }

    function testJoinSingle() {
        $vector = new Vector(0);
        $this->assertEquals('0', $vector->join(','));
    }

    function testJoinMultiple() {
        $vector = new Vector(0, 2, 4);
        $this->assertEquals('0, 2, 4', $vector->join(', '));
    }

    function testLimit() {
        $vector = new Vector(0);
        $this->assertInstanceOf(
            'Ardent\\LimitingIterable',
            $vector->limit(0)
        );
    }

    function testMaxEmpty() {
        $vector = new Vector();
        $this->assertNull($vector->max(function() {return 1;}));
    }

    function testMax() {
        $vector = new Vector(0, 5, 3, 8);
        $this->assertEquals(8, $vector->max());
    }

    function testMinEmpty() {
        $vector = new Vector();
        $this->assertNull($vector->min(function() {return 1;}));
    }

    function testMin() {
        $vector = new Vector(0, 5, 3, 8);
        $this->assertEquals(0, $vector->min());
    }

    function testMap() {
        $vector = new Vector(0);
        $this->assertInstanceOf(
            'Ardent\\MappingIterable',
            $vector->map(function() {})
        );
    }

    function testNoneMatchedSome() {
        $vector = new Vector(0, 5, 3, -5);
        $none = $vector->none(function ($value, $key) {
            return $value < 3;
        });
        $this->assertFalse($none);
    }

    function testNoneFalse() {
        $vector = new Vector(0, 5, 3, -5);
        $none = $vector->none(function ($value, $key) {
            return $value < 0;
        });
        $this->assertFalse($none);
    }

    function testNoneTrue() {
        $vector = new Vector(0, 5, 3, 8);
        $none = $vector->none(function ($value, $key) {
            return $value < 0;
        });
        $this->assertTrue($none);
    }

    function testReduceEmpty() {
        $vector = new Vector();
        $max = $vector->reduce(10, 'max');
        $this->assertEquals(10, $max);
    }

    function testReduceInitialIsMax() {
        $vector = new Vector(0, 5);
        $max = $vector->reduce(10, 'max');
        $this->assertEquals(10, $max);
    }

    function testReduce() {
        $vector = new Vector(0, 5);
        $max = $vector->reduce(-5, 'max');
        $this->assertEquals(5, $max);
    }

    function testSlice() {
        $vector = new Vector(0, 5);
        $slicer = $vector->slice(0, 1);
        $this->assertInstanceOf('Ardent\\SlicingIterable', $slicer);
    }

    function testSkip() {
        $vector = new Vector([0]);
        $this->assertInstanceOf(
            'Ardent\\SkippingIterable',
            $vector->skip(0)
        );
    }

    function testWhere() {
        $vector = new Vector(0);
        $this->assertInstanceOf(
            'Ardent\\FilteringIterable',
            $vector->where(function () {})
        );
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

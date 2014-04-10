<?php

namespace Collections;

class ArrayIteratorTest extends \PHPUnit_Framework_TestCase {


    function testCount() {
        $array = [];
        $iterator = new ArrayIterator($array);

        $this->assertCount(count($array), $iterator);

        $array = [0];
        $iterator = new ArrayIterator($array);

        $this->assertCount(count($array), $iterator);

        $array = [0, 2, 4, 6];
        $iterator = new ArrayIterator($array);

        $this->assertCount(count($array), $iterator);
    }


    function testIsEmptyTrue() {
        $iterator = new ArrayIterator([]);
        $this->assertTrue($iterator->isEmpty());
    }


    function testIsEmptyFalse() {
        $iterator = new ArrayIterator([1]);
        $this->assertFalse($iterator->isEmpty());
    }


    function testIteration() {
        $array = [0, 2, 4, 8];
        $iterator = new ArrayIterator($array);

        $i = 0;
        $iterator->rewind();
        while ($i < count($array)) {
            $this->assertTrue($iterator->valid());
            $this->assertEquals($i, $iterator->key());
            $this->assertEquals($array[$i], $iterator->current());
            $iterator->next();
            $i++;
        }
    }


    function testArrayToArray() {
        $array = [0, 2, 4, 8];
        $iterator = new ArrayIterator($array);

        $this->assertEquals($array, $iterator->toArray());
    }


    function testMapToArray() {
        $map = [
            'a' => '1',
            'b' => '2',
            'c' => '3',
            'd' => '4',
        ];
        $iterator = new ArrayIterator($map);
        $this->assertEquals(array_values($map), $iterator->values()->toArray());
        $this->assertEquals($map, $iterator->toArray());
    }


    function testLimit() {
        $iterator = new ArrayIterator([0]);
        $this->assertInstanceOf(
            'Collections\\LimitingIterator',
            $iterator->limit(0)
        );
    }


    function testMap() {
        $iterator = new ArrayIterator([0]);
        $this->assertInstanceOf(
            'Collections\\MappingIterator',
            $iterator->map(function() {})
        );
    }


    function testReduceEmpty() {
        $iterator = new ArrayIterator([]);
        $max = $iterator->reduce(10, 'max');
        $this->assertEquals(10, $max);
    }


    function testReduceInitialIsMax() {
        $iterator = new ArrayIterator([0, 5]);
        $max = $iterator->reduce(10, 'max');
        $this->assertEquals(10, $max);
    }


    function testReduce() {
        $iterator = new ArrayIterator([0, 5]);
        $max = $iterator->reduce(-5, 'max');
        $this->assertEquals(5, $max);
    }


    function testSeek() {
        $iterator = new ArrayIterator([0, 5, 2, 6]);
        $iterator->rewind();

        $iterator->seek(3);
        $this->assertEquals(6, $iterator->current());

        $iterator->seek(1);
        $this->assertEquals(5, $iterator->current());

        $iterator->seek(2);
        $this->assertEquals(2, $iterator->current());

        $iterator->seek(0);
        $this->assertEquals(0, $iterator->current());

        $iterator->seek(3);
        $this->assertEquals(6, $iterator->current());

    }


    function testSlice() {
        $iterator = new ArrayIterator([0, 5]);
        $slicer = $iterator->slice(0, 1);
        $this->assertInstanceOf('Collections\\SlicingIterator', $slicer);
    }


    function testSkip() {
        $iterator = new ArrayIterator([0]);
        $this->assertInstanceOf(
            'Collections\\SkippingIterator',
            $iterator->skip(0)
        );
    }


    function testFilter() {
        $iterator = new ArrayIterator([0]);
        $this->assertInstanceOf(
            'Collections\\FilteringIterator',
            $iterator->filter(function () {})
        );
    }

}

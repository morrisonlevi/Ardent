<?php

namespace Collections;

class IteratorCollectionAdapterTest extends \PHPUnit_Framework_TestCase {


    function testIteration() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorCollectionAdapter($inner);

        $i = 0;
        foreach ($iterator as $key => $value) {
            $this->assertEquals($i, $key);
            $this->assertEquals($array[$i++], $value);
        }
        $this->assertEquals(5, $i);
    }


    function testLimit() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([0]));
        $this->assertInstanceOf(
            'Collections\\LimitingIterator',
            $iterator->limit(0)
        );
    }


    function testMap() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([0]));
        $this->assertInstanceOf(
            'Collections\\MappingIterator',
            $iterator->map(function() {})
        );
    }


    function testReduceEmpty() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([]));
        $max = $iterator->reduce(10, 'max');
        $this->assertEquals(10, $max);
    }


    function testReduceInitialIsMax() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([0, 5]));
        $max = $iterator->reduce(10, 'max');
        $this->assertEquals(10, $max);
    }


    function testReduce() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([0, 5]));
        $max = $iterator->reduce(-5, 'max');
        $this->assertEquals(5, $max);
    }


    function testSlice() {
        $iterator = new \ArrayIterator();
        $collection = new IteratorCollectionAdapter($iterator);
        $driver = new CollectionTestDriver();
        $driver->doSliceTests($collection, [$iterator, 'append']);
    }


    function testSkip() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([0]));
        $this->assertInstanceOf(
            'Collections\\SkippingIterator',
            $iterator->skip(0)
        );
    }


    function testFilter() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([0]));
        $this->assertInstanceOf(
            'Collections\\FilteringIterator',
            $iterator->filter(function () {})
        );
    }


    function testValuesArray() {
        $array = [1,2,3];
        $iterator = new IteratorCollectionAdapter(new ArrayIterator($array));

        $this->assertEquals([1,2,3], $iterator->values()->toArray());
    }


    function testValuesMap() {
        $array = ['one' => 1, 'two' => 2, 'three' => 3];
        $iterator = new IteratorCollectionAdapter(new ArrayIterator($array));

        $this->assertEquals([1,2,3], $iterator->values()->toArray());
    }


    function testKeysArray() {
        $array = [1,2,3];
        $iterator = new IteratorCollectionAdapter(new ArrayIterator($array));

        $this->assertEquals([0,1,2], $iterator->keys()->toArray());
    }


    function testKeysMap() {
        $array = ['one' => 1, 'two' => 2, 'three' => 3];
        $iterator = new IteratorCollectionAdapter(new ArrayIterator($array));

        $this->assertEquals(['one', 'two', 'three'], $iterator->keys()->toArray());
    }


    function testValuesOnValuesReturnsSame() {
        $array = ['one' => 1, 'two' => 2, 'three' => 3];
        $iterator = new ValueIterator(new ArrayIterator($array));

        $this->assertSame($iterator, $iterator->values());

    }

}

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

    function testEveryMatchesNone() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorCollectionAdapter($inner);

        $this->assertFalse($iterator->every(function ($value) {
            return $value < 0;
        }));
    }

    function testEveryMatchesSome() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorCollectionAdapter($inner);

        $this->assertFalse($iterator->every(function ($value) {
            return $value < 3;
        }));
    }

    function testEveryMatchesAll() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorCollectionAdapter($inner);

        $this->assertTrue($iterator->every(function ($value) {
            return $value < PHP_INT_MAX;
        }));
    }

    function testContainsFalse() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorCollectionAdapter($inner);

        $this->assertFalse($iterator->any(function ($value) {
            return $value === NULL;
        }));
    }

    function testContains() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorCollectionAdapter($inner);

        $this->assertTrue($iterator->any(function ($value) {
            return $value === 3;
        }));
    }

    function testJoinEmpty() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([]));
        $this->assertEquals('', $iterator->join(','));
    }

    function testJoinSingle() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([0]));
        $this->assertEquals('0', $iterator->join(','));
    }

    function testJoinMultiple() {
        $iterator = new IteratorCollectionAdapter( new ArrayIterator([0, 2, 4]));
        $this->assertEquals('0, 2, 4', $iterator->join(', '));
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

    function testMaxEmpty() {
        $this->setExpectedException('\\Collections\\StateException');
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([]));
        $iterator->max();
    }

    function testMax() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([0, 5, 3, 8]));
        $this->assertEquals(8, $iterator->max());
    }

    function testMinEmpty() {
        $this->setExpectedException('\\Collections\\StateException');
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([]));
        $iterator->min();
    }

    function testMin() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([0, 5, 3, 8]));
        $this->assertEquals(0, $iterator->min());
    }

    function testNoneMatchedSome() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([0, 5, 3, -5]));
        $none = $iterator->none(function ($value, $key) {
            return $value < 3;
        });
        $this->assertFalse($none);
    }

    function testNoneFalse() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([0, 5, 3, -5]));
        $none = $iterator->none(function ($value, $key) {
            return $value < 0;
        });
        $this->assertFalse($none);
    }

    function testNoneTrue() {
        $iterator = new IteratorCollectionAdapter(new ArrayIterator([0, 5, 3, 8]));
        $none = $iterator->none(function ($value, $key) {
            return $value < 0;
        });
        $this->assertTrue($none);
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

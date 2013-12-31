<?php

namespace Collections;

class IteratorToCollectionAdapterTest extends \PHPUnit_Framework_TestCase {

    function testIteration() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorToCollectionAdapter($inner);

        $i = 0;
        foreach ($iterator as $key => $value) {
            $this->assertEquals($i, $key);
            $this->assertEquals($array[$i++], $value);
        }
        $this->assertEquals(5, $i);
    }

    function testEach() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorToCollectionAdapter($inner);

        $keys = '';
        $sum = 0;
        $iterator->each(function ($value, $key) use (&$keys, &$sum) {
            $keys .= $key;
            $sum += $value;
        });

        $this->assertEquals('01234', $keys);
        $this->assertEquals(15, $sum);
    }

    function testEveryMatchesNone() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorToCollectionAdapter($inner);

        $this->assertFalse($iterator->every(function ($value) {
            return $value < 0;
        }));
    }

    function testEveryMatchesSome() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorToCollectionAdapter($inner);

        $this->assertFalse($iterator->every(function ($value) {
            return $value < 3;
        }));
    }

    function testEveryMatchesAll() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorToCollectionAdapter($inner);

        $this->assertTrue($iterator->every(function ($value) {
            return $value < PHP_INT_MAX;
        }));
    }

    function testContainsFalse() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorToCollectionAdapter($inner);

        $this->assertFalse($iterator->any(function ($value) {
            return $value === NULL;
        }));
    }

    function testContains() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterator($array);
        $iterator = new IteratorToCollectionAdapter($inner);

        $this->assertTrue($iterator->any(function ($value) {
            return $value === 3;
        }));
    }

    function testCountCountable() {
        $iterator = new IteratorToCollectionAdapter(new \ArrayIterator([0, 2, 4]));
        $this->assertCount(3, $iterator);
    }

    function testCountEmptyNotCountable() {
        $iterator = new IteratorToCollectionAdapter(new \EmptyIterator());
        $this->assertCount(0, $iterator);
    }

    function testCountNotCountable() {
        $iterator = new IteratorToCollectionAdapter(new \IteratorIterator(new \ArrayIterator([1])));
        $this->assertCount(1, $iterator);
    }

    function testIsEmptyTrue() {
        $iterator = new IteratorToCollectionAdapter(new \EmptyIterator());
        $this->assertTrue($iterator->isEmpty());
    }

    function testIsEmptyFalse() {
        $iterator = new IteratorToCollectionAdapter(new \ArrayIterator([1]));
        $this->assertFalse($iterator->isEmpty());
    }

    function testJoinEmpty() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([]));
        $this->assertEquals('', $iterator->join(','));
    }

    function testJoinSingle() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([0]));
        $this->assertEquals('0', $iterator->join(','));
    }

    function testJoinMultiple() {
        $iterator = new IteratorToCollectionAdapter( new ArrayIterator([0, 2, 4]));
        $this->assertEquals('0, 2, 4', $iterator->join(', '));
    }

    function testLimit() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([0]));
        $this->assertInstanceOf(
            'Collections\\LimitingIterator',
            $iterator->limit(0)
        );
    }

    function testMap() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([0]));
        $this->assertInstanceOf(
            'Collections\\MappingIterator',
            $iterator->map(function() {})
        );
    }

    function testMaxEmpty() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([]));
        $this->assertNull($iterator->max(function() {return 1;}));
    }

    function testMax() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([0, 5, 3, 8]));
        $this->assertEquals(8, $iterator->max());
    }

    function testMinEmpty() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([]));
        $this->assertNull($iterator->min(function() {return 1;}));
    }

    function testMin() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([0, 5, 3, 8]));
        $this->assertEquals(0, $iterator->min());
    }

    function testNoneMatchedSome() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([0, 5, 3, -5]));
        $none = $iterator->none(function ($value, $key) {
            return $value < 3;
        });
        $this->assertFalse($none);
    }

    function testNoneFalse() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([0, 5, 3, -5]));
        $none = $iterator->none(function ($value, $key) {
            return $value < 0;
        });
        $this->assertFalse($none);
    }

    function testNoneTrue() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([0, 5, 3, 8]));
        $none = $iterator->none(function ($value, $key) {
            return $value < 0;
        });
        $this->assertTrue($none);
    }

    function testReduceEmpty() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([]));
        $max = $iterator->reduce(10, 'max');
        $this->assertEquals(10, $max);
    }

    function testReduceInitialIsMax() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([0, 5]));
        $max = $iterator->reduce(10, 'max');
        $this->assertEquals(10, $max);
    }

    function testReduce() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([0, 5]));
        $max = $iterator->reduce(-5, 'max');
        $this->assertEquals(5, $max);
    }

    function testSlice() {
        $iterator = new \ArrayIterator();
        $collection = new IteratorToCollectionAdapter($iterator);
        $driver = new CollectionTestDriver();
        $driver->doSliceTests($collection, [$iterator, 'append']);
    }

    function testSkip() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([0]));
        $this->assertInstanceOf(
            'Collections\\SkippingIterator',
            $iterator->skip(0)
        );
    }

    function testFilter() {
        $iterator = new IteratorToCollectionAdapter(new ArrayIterator([0]));
        $this->assertInstanceOf(
            'Collections\\FilteringIterator',
            $iterator->filter(function () {})
        );
    }

}

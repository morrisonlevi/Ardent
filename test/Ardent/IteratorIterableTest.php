<?php

namespace Ardent;

class IteratorIterableTest extends \PHPUnit_Framework_TestCase {

    function testIteration() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterable($array);
        $iterator = new IteratorIterable($inner);

        $i = 0;
        foreach ($iterator as $key => $value) {
            $this->assertEquals($i, $key);
            $this->assertEquals($array[$i++], $value);
        }
        $this->assertEquals(5, $i);
    }

    function testEach() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterable($array);
        $iterator = new IteratorIterable($inner);

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
        $inner = new ArrayIterable($array);
        $iterator = new IteratorIterable($inner);

        $this->assertFalse($iterator->every(function ($value) {
            return $value < 0;
        }));
    }

    function testEveryMatchesSome() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterable($array);
        $iterator = new IteratorIterable($inner);

        $this->assertFalse($iterator->every(function ($value) {
            return $value < 3;
        }));
    }

    function testEveryMatchesAll() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterable($array);
        $iterator = new IteratorIterable($inner);

        $this->assertTrue($iterator->every(function ($value) {
            return $value < PHP_INT_MAX;
        }));
    }

    function testContainsFalse() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterable($array);
        $iterator = new IteratorIterable($inner);

        $this->assertFalse($iterator->contains(function ($value) {
            return $value === NULL;
        }));
    }

    function testContains() {
        $array = [1, 2, 3, 4, 5];
        $inner = new ArrayIterable($array);
        $iterator = new IteratorIterable($inner);

        $this->assertTrue($iterator->contains(function ($value) {
            return $value === 3;
        }));
    }

    function testCountCountable() {
        $iterator = new IteratorIterable(new \ArrayIterator([0, 2, 4]));
        $this->assertCount(3, $iterator);
    }

    function testCountEmptyNotCountable() {
        $iterator = new IteratorIterable(new \EmptyIterator());
        $this->assertCount(0, $iterator);
    }

    function testCountNotCountable() {
        $iterator = new IteratorIterable(new \IteratorIterator(new \ArrayIterator([1])));
        $this->assertCount(1, $iterator);
    }

    function testJoinEmpty() {
        $iterator = new IteratorIterable(new ArrayIterable([]));
        $this->assertEquals('', $iterator->join(','));
    }

    function testJoinSingle() {
        $iterator = new IteratorIterable(new ArrayIterable([0]));
        $this->assertEquals('0', $iterator->join(','));
    }

    function testJoinMultiple() {
        $iterator = new IteratorIterable( new ArrayIterable([0, 2, 4]));
        $this->assertEquals('0, 2, 4', $iterator->join(', '));
    }

    function testLimit() {
        $iterator = new IteratorIterable(new ArrayIterable([0]));
        $this->assertInstanceOf(
            'Ardent\\LimitingIterable',
            $iterator->limit(0)
        );
    }

    function testMap() {
        $iterator = new IteratorIterable(new ArrayIterable([0]));
        $this->assertInstanceOf(
            'Ardent\\MappingIterable',
            $iterator->map(function() {})
        );
    }

    function testMaxEmpty() {
        $iterator = new IteratorIterable(new ArrayIterable([]));
        $this->assertNull($iterator->max(function() {return 1;}));
    }

    function testMax() {
        $iterator = new IteratorIterable(new ArrayIterable([0, 5, 3, 8]));
        $this->assertEquals(8, $iterator->max());
    }

    function testMinEmpty() {
        $iterator = new IteratorIterable(new ArrayIterable([]));
        $this->assertNull($iterator->min(function() {return 1;}));
    }

    function testMin() {
        $iterator = new IteratorIterable(new ArrayIterable([0, 5, 3, 8]));
        $this->assertEquals(0, $iterator->min());
    }

    function testNoneMatchedSome() {
        $iterator = new IteratorIterable(new ArrayIterable([0, 5, 3, -5]));
        $none = $iterator->none(function ($value, $key) {
            return $value < 3;
        });
        $this->assertFalse($none);
    }

    function testNoneFalse() {
        $iterator = new IteratorIterable(new ArrayIterable([0, 5, 3, -5]));
        $none = $iterator->none(function ($value, $key) {
            return $value < 0;
        });
        $this->assertFalse($none);
    }

    function testNoneTrue() {
        $iterator = new IteratorIterable(new ArrayIterable([0, 5, 3, 8]));
        $none = $iterator->none(function ($value, $key) {
            return $value < 0;
        });
        $this->assertTrue($none);
    }

    function testReduceEmpty() {
        $iterator = new IteratorIterable(new ArrayIterable([]));
        $max = $iterator->reduce(10, 'max');
        $this->assertEquals(10, $max);
    }

    function testReduceInitialIsMax() {
        $iterator = new IteratorIterable(new ArrayIterable([0, 5]));
        $max = $iterator->reduce(10, 'max');
        $this->assertEquals(10, $max);
    }

    function testReduce() {
        $iterator = new IteratorIterable(new ArrayIterable([0, 5]));
        $max = $iterator->reduce(-5, 'max');
        $this->assertEquals(5, $max);
    }

    function testSlice() {
        $iterator = new IteratorIterable(new ArrayIterable([0, 5]));
        $slicer = $iterator->slice(0, 1);
        $this->assertInstanceOf('Ardent\\SlicingIterable', $slicer);
    }

    function testSkip() {
        $iterator = new IteratorIterable(new ArrayIterable([0]));
        $this->assertInstanceOf(
            'Ardent\\SkippingIterable',
            $iterator->skip(0)
        );
    }

    function testWhere() {
        $iterator = new IteratorIterable(new ArrayIterable([0]));
        $this->assertInstanceOf(
            'Ardent\\FilteringIterable',
            $iterator->where(function () {})
        );
    }

}

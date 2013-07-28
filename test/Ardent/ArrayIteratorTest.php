<?php

namespace Ardent;

use Ardent\Iterator\ArrayIterator;

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

    function testisEmptyTrue() {
        $iterator = new ArrayIterator([]);
        $this->assertTrue($iterator->isEmpty());
    }

    function testisEmptyFalse() {
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
        $this->assertEquals(array_values($map), $iterator->toArray());
        $this->assertEquals($map, $iterator->toArray(TRUE));
    }

    function testAnyEmpty() {
        $iterator = new ArrayIterator([]);
        $this->assertFalse($iterator->any(function () {
            return TRUE;
        }));
    }

    function testAnyFalse() {
        $iterator = new ArrayIterator([1, 2, 3, 4, 5]);
        $this->assertFalse($iterator->any(function ($val) {
            return $val === 0;
        }));
    }

    function testAnyFirst() {
        $iterator = new ArrayIterator([1, 2, 3, 4, 5]);
        $this->assertTrue($iterator->any(function ($val) {
            return $val === 1;
        }));
    }

    function testAnyMiddle() {
        $iterator = new ArrayIterator([1, 2, 3, 4, 5]);
        $this->assertTrue($iterator->any(function ($val) {
            return $val === 3;
        }));
    }

    function testAnyLast() {
        $iterator = new ArrayIterator([1, 2, 3, 4, 5]);
        $this->assertTrue($iterator->any(function ($val) {
            return $val === 5;
        }));
    }

    function testEachEmpty() {
        $iterator = new ArrayIterator([]);
        $i = 0;
        $iterator->each(function ($val) use (&$i) {
            $i += $val;
        });

        $this->assertEquals(0, $i);
    }

    function testEach() {
        $iterator = new ArrayIterator([1, 2, 3]);

        $k = '';
        $v = 0;
        $iterator->each(function ($val, $key) use (&$v, &$k) {
            $k .= $key;
            $v += $val;
        });

        $this->assertEquals('012', $k);
        $this->assertEquals(6, $v);
    }

    function testEveryEmpty() {
        $iterator = new ArrayIterator([]);
        $this->assertTrue($iterator->every('is_null'));
    }

    function testEverySome() {
        $iterator = new ArrayIterator([NULL, NULL, 0]);
        $this->assertFalse($iterator->every(function ($val) { return $val === NULL;}));
    }

    function testEvery() {
        $iterator = new ArrayIterator([NULL, NULL, NULL]);
        $this->assertTrue($iterator->every(function ($val) { return $val === NULL;}));
    }

    function testJoinEmpty() {
        $iterator = new ArrayIterator([]);
        $this->assertEquals('', $iterator->join(','));
    }

    function testJoinSingle() {
        $iterator = new ArrayIterator([0]);
        $this->assertEquals('0', $iterator->join(','));
    }

    function testJoinMultiple() {
        $iterator = new ArrayIterator([0, 2, 4]);
        $this->assertEquals('0, 2, 4', $iterator->join(', '));
    }

    function testLimit() {
        $iterator = new ArrayIterator([0]);
        $this->assertInstanceOf(
            'Ardent\\Iterator\\LimitingIterator',
            $iterator->limit(0)
        );
    }

    function testMaxEmpty() {
        $iterator = new ArrayIterator([]);
        $this->assertNull($iterator->max(function() {return 1;}));
    }

    function testMax() {
        $iterator = new ArrayIterator([0, 5, 3, 8]);
        $this->assertEquals(8, $iterator->max());
    }

    function testMinEmpty() {
        $iterator = new ArrayIterator([]);
        $this->assertNull($iterator->min(function() {return 1;}));
    }

    function testMin() {
        $iterator = new ArrayIterator([0, 5, 3, 8]);
        $this->assertEquals(0, $iterator->min());
    }

    function testMap() {
        $iterator = new ArrayIterator([0]);
        $this->assertInstanceOf(
            'Ardent\\Iterator\\MappingIterator',
            $iterator->map(function() {})
        );
    }

    function testNoneMatchedSome() {
        $iterator = new ArrayIterator([0, 5, 3, -5]);
        $none = $iterator->none(function ($value, $key) {
            return $value < 3;
        });
        $this->assertFalse($none);
    }

    function testNoneFalse() {
        $iterator = new ArrayIterator([0, 5, 3, -5]);
        $none = $iterator->none(function ($value, $key) {
            return $value < 0;
        });
        $this->assertFalse($none);
    }

    function testNoneTrue() {
        $iterator = new ArrayIterator([0, 5, 3, 8]);
        $none = $iterator->none(function ($value, $key) {
            return $value < 0;
        });
        $this->assertTrue($none);
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
        $this->assertInstanceOf('Ardent\\Iterator\\SlicingIterator', $slicer);
    }

    function testSkip() {
        $iterator = new ArrayIterator([0]);
        $this->assertInstanceOf(
            'Ardent\\Iterator\\SkippingIterator',
            $iterator->skip(0)
        );
    }

    function testFilter() {
        $iterator = new ArrayIterator([0]);
        $this->assertInstanceOf(
            'Ardent\\Iterator\\FilteringIterator',
            $iterator->filter(function () {})
        );
    }

}

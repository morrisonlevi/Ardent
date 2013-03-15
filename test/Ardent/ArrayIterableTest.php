<?php

namespace Ardent;

class ArrayIterableTest extends \PHPUnit_Framework_TestCase {

    function testCount() {
        $array = [];
        $iterator = new ArrayIterable($array);

        $this->assertCount(count($array), $iterator);

        $array = [0];
        $iterator = new ArrayIterable($array);

        $this->assertCount(count($array), $iterator);

        $array = [0, 2, 4, 6];
        $iterator = new ArrayIterable($array);

        $this->assertCount(count($array), $iterator);
    }

    function testIteration() {
        $array = [0, 2, 4, 8];
        $iterator = new ArrayIterable($array);

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
        $iterator = new ArrayIterable($array);

        $this->assertEquals($array, $iterator->toArray());
    }

    function testMapToArray() {
        $map = [
            'a' => '1',
            'b' => '2',
            'c' => '3',
            'd' => '4',
        ];
        $iterator = new ArrayIterable($map);
        $this->assertEquals(array_values($map), $iterator->toArray());
        $this->assertEquals($map, $iterator->toArray(TRUE));
    }

    function testContainsEmpty() {
        $iterator = new ArrayIterable([]);
        $this->assertFalse($iterator->contains(function () {
            return TRUE;
        }));
    }

    function testContainsFalse() {
        $iterator = new ArrayIterable([1, 2, 3, 4, 5]);
        $this->assertFalse($iterator->contains(function ($val) {
            return $val === 0;
        }));
    }

    function testContainsFirst() {
        $iterator = new ArrayIterable([1, 2, 3, 4, 5]);
        $this->assertTrue($iterator->contains(function ($val) {
            return $val === 1;
        }));
    }

    function testContainsMiddle() {
        $iterator = new ArrayIterable([1, 2, 3, 4, 5]);
        $this->assertTrue($iterator->contains(function ($val) {
            return $val === 3;
        }));
    }

    function testContainsLast() {
        $iterator = new ArrayIterable([1, 2, 3, 4, 5]);
        $this->assertTrue($iterator->contains(function ($val) {
            return $val === 5;
        }));
    }

    function testEachEmpty() {
        $iterator = new ArrayIterable([]);
        $i = 0;
        $iterator->each(function ($val) use (&$i) {
            $i += $val;
        });

        $this->assertEquals(0, $i);
    }

    function testEach() {
        $iterator = new ArrayIterable([1, 2, 3]);

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
        $iterator = new ArrayIterable([]);
        $this->assertTrue($iterator->every('is_null'));
    }

    function testEverySome() {
        $iterator = new ArrayIterable([NULL, NULL, 0]);
        $this->assertFalse($iterator->every(function ($val) { return $val === NULL;}));
    }

    function testEvery() {
        $iterator = new ArrayIterable([NULL, NULL, NULL]);
        $this->assertTrue($iterator->every(function ($val) { return $val === NULL;}));
    }

    function testJoinEmpty() {
        $iterator = new ArrayIterable([]);
        $this->assertEquals('', $iterator->join(','));
    }

    function testJoinSingle() {
        $iterator = new ArrayIterable([0]);
        $this->assertEquals('0', $iterator->join(','));
    }

    function testJoinMultiple() {
        $iterator = new ArrayIterable([0, 2, 4]);
        $this->assertEquals('0, 2, 4', $iterator->join(', '));
    }

    function testLimit() {
        $iterator = new ArrayIterable([0]);
        $this->assertInstanceOf(
            'Ardent\\LimitingIterable',
            $iterator->limit(0)
        );
    }

    function testMaxEmpty() {
        $iterator = new ArrayIterable([]);
        $this->assertNull($iterator->max(function() {return 1;}));
    }

    function testMax() {
        $iterator = new ArrayIterable([0, 5, 3, 8]);
        $this->assertEquals(8, $iterator->max());
    }

    function testMinEmpty() {
        $iterator = new ArrayIterable([]);
        $this->assertNull($iterator->min(function() {return 1;}));
    }

    function testMin() {
        $iterator = new ArrayIterable([0, 5, 3, 8]);
        $this->assertEquals(0, $iterator->min());
    }

    function testMap() {
        $iterator = new ArrayIterable([0]);
        $this->assertInstanceOf(
            'Ardent\\MappingIterable',
            $iterator->map(function() {})
        );
    }

    function testNoneMatchedSome() {
        $iterator = new ArrayIterable([0, 5, 3, -5]);
        $none = $iterator->none(function ($value, $key) {
            return $value < 3;
        });
        $this->assertFalse($none);
    }

    function testNoneFalse() {
        $iterator = new ArrayIterable([0, 5, 3, -5]);
        $none = $iterator->none(function ($value, $key) {
            return $value < 0;
        });
        $this->assertFalse($none);
    }

    function testNoneTrue() {
        $iterator = new ArrayIterable([0, 5, 3, 8]);
        $none = $iterator->none(function ($value, $key) {
            return $value < 0;
        });
        $this->assertTrue($none);
    }

    function testReduceEmpty() {
        $iterator = new ArrayIterable([]);
        $max = $iterator->reduce(10, 'max');
        $this->assertEquals(10, $max);
    }

    function testReduceInitialIsMax() {
        $iterator = new ArrayIterable([0, 5]);
        $max = $iterator->reduce(10, 'max');
        $this->assertEquals(10, $max);
    }

    function testReduce() {
        $iterator = new ArrayIterable([0, 5]);
        $max = $iterator->reduce(-5, 'max');
        $this->assertEquals(5, $max);
    }

    function testSlice() {
        $iterator = new ArrayIterable([0, 5]);
        $slicer = $iterator->slice(0, 1);
        $this->assertInstanceOf('Ardent\\SlicingIterable', $slicer);
    }

    function testSkip() {
        $iterator = new ArrayIterable([0]);
        $this->assertInstanceOf(
            'Ardent\\SkippingIterable',
            $iterator->skip(0)
        );
    }

    function testWhere() {
        $iterator = new ArrayIterable([0]);
        $this->assertInstanceOf(
            'Ardent\\FilteringIterable',
            $iterator->where(function () {})
        );
    }

}

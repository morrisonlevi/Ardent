<?php

namespace Ardent;

class FilteringIterableTest extends \PHPUnit_Framework_TestCase {

    function testEmpty() {
        $iterator = new FilteringIterable(
            new ArrayIterable([]),
            function ($a) {
                return $a & 1;
            }
        );

        $i = 0;
        foreach ($iterator as $value) {
            $i++;
        }
        $this->assertEquals(0, $i);
    }

    function testMatchNone() {
        $iterator = new FilteringIterable(
            new ArrayIterable([0, 2, 4]),
            function ($a) {
                return $a & 1;
            }
        );

        $i = 0;
        foreach ($iterator as $value) {
            $i++;
        }
        $this->assertEquals(0, $i);
    }

    function testMatchAll() {
        $array = [0, 2, 4];
        $iterator = new FilteringIterable(
            new ArrayIterable($array),
            function () {
                return TRUE;
            }
        );

        $i = 0;
        foreach ($iterator as $key => $value) {
            $this->assertEquals(
                $i,
                $key
            );
            $this->assertEquals(
                $array[$i++],
                $value
            );
        }
        $this->assertEquals(3, $i);
    }

    function testMatchSome() {
        $array = [0, 1, 2, 3, 4];
        $iterator = new FilteringIterable(
            new ArrayIterable($array),
            function ($val) {
                return $val & 1;
            }
        );

        $i = 0;
        $matched = [1, 3];
        foreach ($iterator as $key => $value) {
            $this->assertEquals($i, $key);
            $this->assertEquals($matched[$i++], $value);
        }
        $this->assertEquals(2, $i);
    }

    function testCount() {
        $array = [0, 1, 2, 3, 4];
        $iterator = new FilteringIterable(
            new ArrayIterable($array),
            function ($val) {
                return $val & 1;
            }
        );

        $this->assertCount(2, $iterator);
    }
}

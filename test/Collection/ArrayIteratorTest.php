<?php

namespace Ardent\Collection;

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


    function testMapToArray() {
        $map = [
            'a' => '1',
            'b' => '2',
            'c' => '3',
            'd' => '4',
        ];
        $iterator = new ArrayIterator($map);
        $this->assertEquals($map, iterator_to_array($iterator));
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


}


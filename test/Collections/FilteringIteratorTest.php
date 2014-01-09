<?php

namespace Collections;

class FilteringIteratorTest extends \PHPUnit_Framework_TestCase {

    function provideCases() {
        $and1 = function ($a) {
            return $a & 1;
        };
        return [
            'empty' => [
                'insert' => [],
                'filter' => $and1,
                'expect' => [],
                'expectPreserveKeys' => [],
            ],
            'match none' => [
                'insert' => [0,2,4],
                'filter' => $and1,
                'expect' => [],
                'expectPreserveKeys' => [],
            ],
            'match all' => [
                'insert' => [1,3],
                'filter' => $and1,
                'expect' => [1,3],
                'expectPreserveKeys' => [0 => 1, 1 => 3],
            ],
            'match some' => [
                'insert' => [0,1,2,3,4],
                'filter' => $and1,
                'expect' => [1,3],
                'expectPreserveKeys' => [1 => 1, 3 => 3],
            ],
        ];
    }

    /**
     * @dataProvider provideCases
     */
    function testCases(array $insert, callable $filter, array $expect, array $expectPreserveKeys) {
        $iterator = new ArrayIterator($insert);
        $filtered = $iterator->filter($filter);

        $this->assertCount(count($expect), $filtered);
        $this->assertEquals($expect, $filtered->values()->toArray());
        $this->assertEquals($expectPreserveKeys, $filtered->toArray());
    }
}

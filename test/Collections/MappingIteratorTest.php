<?php

namespace Collections;

class MappingIteratorTest extends \PHPUnit_Framework_TestCase {

    function provideCases() {
        $multiplyBy2 = function($i) {
            return $i * 2;
        };
        return [
            'empty' => [
                'insert' => [],
                'map'    => $multiplyBy2,
                'expect' => [],
            ],
            'map' => [
                'insert' => [0,1,2,3],
                'map'    => $multiplyBy2,
                'expect' => [0,2,4,6],
            ],
        ];
    }

    /**
     * @dataProvider provideCases
     */
    function testCases(array $insert, callable $map, array $expect) {
        $iterator = new ArrayIterator($insert);
        $mapped = $iterator->map($map);

        $this->assertCount(count($expect), $mapped);
        $this->assertEquals($expect, $mapped->toArray());
    }

}

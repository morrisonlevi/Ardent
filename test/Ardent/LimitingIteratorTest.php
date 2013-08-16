<?php

namespace Ardent;

use Ardent\Iterator\ArrayIterator;

class LimitingIteratorTest extends \PHPUnit_Framework_TestCase {

    function provideCases() {
        return [
            'empty' => [
                'insert' => [],
                'limit'  => 2,
                'expect' => []
            ],
            'zero' => [
                'insert' => [1,4,5],
                'limit'  => 0,
                'expect' => []
            ],
            'limit' => [
                'insert' => [1,4,5],
                'limit'  => 2,
                'expect' => [1,4]
            ],
            'limit extra' => [
                'insert' => [1,4,5],
                'limit'  => 4,
                'expect' => [1,4,5]
            ],
        ];
    }

    /**
     * @dataProvider provideCases
     */
    function testCases(array $insert, $limit, array $expect) {
        $iterator = new ArrayIterator($insert);
        $limited = $iterator->limit($limit);

        $this->assertCount(count($expect), $limited);
        $this->assertEquals($expect, $limited->toArray());
    }

}
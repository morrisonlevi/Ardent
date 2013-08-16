<?php

namespace Ardent;

use Ardent\Iterator\ArrayIterator;

class SkippingIteratorTest extends \PHPUnit_Framework_TestCase {

    function provideCases() {
        $insert = [1,2,3,4,5];
        return [
            'empty' => [
                'insert' => [],
                'skip'   => 2,
                'expect' => [],
            ],
            '2' => [
                'insert' => $insert,
                'skip'   => 2,
                'expect' => [
                    2 => 3,
                    3 => 4,
                    4 => 5
                ],
            ],
            '-2' => [
                'insert' => $insert,
                'skip'   => -2,
                'expect' => $insert,
            ],
        ];
    }

    /**
     * @dataProvider provideCases
     */
    function testCases(array $insert, $skip, array $expect) {
        $iterator = new ArrayIterator($insert);
        $skipped = $iterator->skip($skip);

        $this->assertCount(count($expect), $skipped);
        $this->assertEquals($expect, $skipped->toArray(TRUE));

    }

}

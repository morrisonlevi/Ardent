<?php

namespace Collections;


class FilterTest extends \PHPUnit_Framework_TestCase {

    function odd($value, $key = null) {
        return $value % 2 > 0;
    }

    function test_filterable() {
        $set = new HashSet();
        $set->add(1);
        $set->add(2);
        $set->add(3);

        $output = filter([$this, 'odd'], $set);
        $expect = [1, 3];
        $actual = iterator_to_array($output, false);
        $this->assertEquals($expect, $actual);
    }


    function test_iterator() {
        $in = new \ArrayIterator([1, 2, 3]);

        $output = filter([$this, 'odd'], $in);
        $expect = [1, 3];
        $actual = iterator_to_array($output, false);
        $this->assertEquals($expect, $actual);
    }
    

}

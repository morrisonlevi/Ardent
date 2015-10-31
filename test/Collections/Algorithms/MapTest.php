<?php

namespace Collections;


class MapTest extends \PHPUnit_Framework_TestCase {

    /**
     * Helper because strlen will fatal if provided 2 parameters
     */
    function strlen($value, $key = null) {
        return \strlen($value);
    }

    function test_mappable() {
        $set = new HashSet();
        $set->add('hello');

        $output = map([$this, 'strlen'], $set);
        $expect = [$this->strlen('hello')];
        $actual = iterator_to_array($output);
        $this->assertEquals($expect, $actual);
    }


    function test_iterator() {
        $in = new \ArrayIterator(['hello']);

        $out = map([$this, 'strlen'], $in);
        $expect = [$this->strlen('hello')];
        $actual = iterator_to_array($out);
        $this->assertEquals($expect, $actual);
    }


}

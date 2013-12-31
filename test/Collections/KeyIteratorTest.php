<?php

namespace Collections;

class KeyIteratorTest extends \PHPUnit_Framework_TestCase {

    function tests() {
        $array = [0 => 0, 2=>4, 4=>8];
        $iterator = new KeyIterator(new \ArrayIterator($array));

        $this->assertCount(count($array), $iterator);
        $keys = [0, 1, 2];
        $vals = [0, 2, 4];
        $i = 0;
        foreach ($iterator as $key => $value) {
            $this->assertEquals($keys[$i], $key);
            $this->assertEquals($vals[$i], $value);
            $i++;
        }
    }

}
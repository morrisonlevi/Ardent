<?php

namespace Collections;


class TestCase extends \PHPUnit_Framework_TestCase {


    function provide_rangeN() {
        $cases = [];
        for ($i = 0; $i < 5; ++$i) {
            $cases[] = [range(0, $i)];
        }
        return $cases;
    }


} 
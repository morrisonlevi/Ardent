<?php

namespace Ardent\Collection;


class TestCase extends \PHPUnit_Framework_TestCase {


    function provide_rangeZeroToN() {
        $cases = [];
        for ($i = 0; $i < 5; ++$i) {
            $cases[] = [range(0, $i)];
        }
        return $cases;
    }


    function provide_rangeOneToN() {
        $cases = [];
        for ($i = 1; $i < 5; ++$i) {
            $cases[] = [range(0, $i)];
        }
        return $cases;
    }


} 
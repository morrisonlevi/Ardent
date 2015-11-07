<?php

namespace Ardent\Collection;

class SortedSetTest extends SetTest {


    function instance() {
        return new SortedSet();
    }


    function testFindFirstRoot() {
        $set = new SortedSet();
        $set->add(1);

        $expected = 1;
        $actual = $set->first();
        $this->assertEquals($expected, $actual);
    }


    function testFindFirst() {
        $set = new SortedSet();
        $set->add(2);
        $set->add(1);
        $set->add(3);

        $expected = 1;
        $actual = $set->first();
        $this->assertEquals($expected, $actual);
    }


    function testFindLastRoot() {
        $set = new SortedSet();
        $set->add(1);

        $expected = 1;
        $actual = $set->last();
        $this->assertEquals($expected, $actual);
    }


    function testFindLast() {
        $set = new SortedSet();
        $set->add(2);
        $set->add(1);
        $set->add(3);

        $expected = 3;
        $actual = $set->last();
        $this->assertEquals($expected, $actual);
    }


    function test__construct() {
        $set = new SortedSet(null, new AvlTree());
        // assert?

        $set = new SortedSet(function ($a, $b) {
            if ($a < $b) {
                return 1;
            } elseif ($b < $a) {
                return -1;
            } else {
                return 0;
            }
        }, new AvlTree());

        $set->add(1);
        $set->add(0);
        $set->add(2);

        $expected = [2, 1, 0];
        $actual = iterator_to_array($set->getIterator());
        $this->assertEquals($expected, $actual);
    }


    function test_getIterator_empty_returnsIterator() {
        $set = new SortedSet();
        $iterator = $set->getIterator();

        $this->assertInstanceOf(__NAMESPACE__ . '\\SortedSetIterator', $iterator);
    }


    function test_getIterator_notEmpty_returnsIterator() {
        $set = new SortedSet();
        $set->add(0);
        $iterator = $set->getIterator();

        $this->assertInstanceOf(__NAMESPACE__ . '\\SortedSetIterator', $iterator);
    }


}

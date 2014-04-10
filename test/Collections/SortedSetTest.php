<?php

namespace Collections;

class SortedSetTest extends \PHPUnit_Framework_TestCase {

    function testAdd() {
        $set = new SortedSet();
        $this->assertCount(0, $set);

        $set->add(0);
        $this->assertCount(1, $set);

        $set->add(0);
        $set->add(1);
        $set->add(2);
        $set->add(3);
        $this->assertCount(4, $set);
    }

    /**
     * @depends testAdd
     */
    function testRemove() {
        $set = new SortedSet();
        $set->remove(0);
        $this->assertCount(0, $set);

        $set->add(0);
        $set->add(-1);
        $set->add(1);
        $this->count(3, $set);

        $set->remove(0);
        $this->assertCount(2, $set);

    }

    /**
     * @depends testRemove
     */
    function testContains() {
        $set = new SortedSet();
        $this->assertFalse($set->has(0));
        $this->assertFalse($set->has(-1));
        $this->assertFalse($set->has(1));

        $set->add(0);
        $set->add(-1);
        $set->add(1);
        $this->assertTrue($set->has(0));
        $this->assertTrue($set->has(-1));
        $this->assertTrue($set->has(1));

        $set->remove(0);
        $this->assertFalse($set->has(0));
        $this->assertTrue($set->has(-1));
        $this->assertTrue($set->has(1));

    }

    /**
     * @depends testRemove
     */
    function testIsEmpty() {
        $set = new SortedSet();

        $this->assertTrue($set->isEmpty());

        $set->add(0);
        $this->assertFalse($set->isEmpty());

        $set->remove(0);
        $this->assertTrue($set->isEmpty());
    }

    /**
     * @depends testIsEmpty
     */
    function testClear() {
        $set = new SortedSet();
        $set->clear();

        $this->assertCount(0, $set);
        $this->assertTrue($set->isEmpty());

        $set->add(0);
        $set->clear();
        $this->assertCount(0, $set);
        $this->assertTrue($set->isEmpty());
    }

    /**
     * @depends testAdd
     */
    function testDifferenceSelf() {
        $a = new SortedSet();
        $a->add(0);

        $diff = $a->difference($a);
        $this->assertInstanceOf('Collections\\SortedSet', $diff);
        $this->assertNotSame($diff, $a);
        $this->assertCount(0, $diff);
    }

    /**
     * @expectedException \Collections\EmptyException
     */
    function testFindFirstEmpty() {
        $set = new SortedSet();
        $set->first();
    }

    /**
     * @depends testAdd
     */
    function testFindFirstRoot() {
        $set = new SortedSet();
        $set->add(1);

        $expected = 1;
        $actual = $set->first();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends testAdd
     */
    function testFindFirst() {
        $set = new SortedSet();
        $set->add(2);
        $set->add(1);
        $set->add(3);

        $expected = 1;
        $actual = $set->first();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Collections\EmptyException
     */
    function testFindLastEmpty() {
        $set = new SortedSet();
        $set->last();
    }

    /**
     * @depends testAdd
     */
    function testFindLastRoot() {
        $set = new SortedSet();
        $set->add(1);

        $expected = 1;
        $actual = $set->last();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends testAdd
     */
    function testFindLast() {
        $set = new SortedSet();
        $set->add(2);
        $set->add(1);
        $set->add(3);

        $expected = 3;
        $actual = $set->last();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends testAdd
     */
    function testIteratorEmpty() {
        $set = new SortedSet();
        $iterator = $set->getIterator();
        $this->assertInstanceOf('Collections\\SortedSetIterator', $iterator);
        $this->assertCount(0, $iterator);
    }

    /**
     * @depends testIteratorEmpty
     */
    function testIterator() {
        $set = new SortedSet();
        $set->add(0);
        $set->add(2);
        $set->add(4);
        $set->add(6);

        $iterator = $set->getIterator();
        $this->assertInstanceOf('Collections\\SortedSetIterator', $iterator);
        $this->assertCount(4, $iterator);

        $iterator->rewind();

        for ($i = 0; $i < count($set); $i++) {
            $this->assertTrue($iterator->valid());

            $this->assertEquals($i, $iterator->key());
            $this->assertEquals($i * 2, $iterator->current());

            $iterator->next();
        }

        $this->assertFalse($iterator->valid());
    }

    /**
     * @depends testAdd
     */
    function test__construct() {
        $set = new SortedSet(NULL, new AvlTree());
        // assert?

        $set = new SortedSet(function($a, $b){
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
        $actual = $set->getIterator()->toArray();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Collections\StateException
     */
    function test__constructException() {
        $tree = new SplayTree();
        $tree->add(1);

        new SortedSet(NULL, $tree);
    }

}

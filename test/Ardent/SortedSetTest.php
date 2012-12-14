<?php

namespace Ardent;

class SortedSetTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Ardent\SortedSet::add
     * @covers \Ardent\SortedSet::count
     * @covers \Ardent\SortedSet::__construct
     */
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
     * @covers \Ardent\SortedSet::remove
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
     * @covers \Ardent\SortedSet::add
     * @covers \Ardent\SortedSet::contains
     * @covers \Ardent\SortedSet::remove
     */
    function testContains() {
        $set = new SortedSet();
        $this->assertFalse($set->contains(0));
        $this->assertFalse($set->contains(-1));
        $this->assertFalse($set->contains(1));

        $set->add(0);
        $set->add(-1);
        $set->add(1);
        $this->assertTrue($set->contains(0));
        $this->assertTrue($set->contains(-1));
        $this->assertTrue($set->contains(1));

        $set->remove(0);
        $this->assertFalse($set->contains(0));
        $this->assertTrue($set->contains(-1));
        $this->assertTrue($set->contains(1));

    }

    /**
     * @depends testRemove
     * @covers \Ardent\SortedSet::isEmpty
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
     * @covers \Ardent\SortedSet::clear
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
     * @depends testContains
     * @covers \Ardent\SortedSet::addAll
     */
    function testAddAll() {
        $set = new SortedSet();
        $set->add(-1);
        $toAdd = new ArrayIterator(array(0,1,2,3,3,2,1,0));

        $set->addAll($toAdd);

        $this->assertCount(5, $set);

        for ($i = -1; $i < 4; $i++) {
            $this->assertTrue($set->contains($i));
        }
    }

    /**
     * @depends testRemove
     * @covers \Ardent\SortedSet::removeAll
     */
    function testRemoveAll() {
        $set = new SortedSet();

        for ($i = 0; $i < 4; $i++) {
            $set->add($i);
        }

        $set->removeAll(new ArrayIterator(array(1, 3)));

        $this->assertCount(2, $set);
        $this->assertTrue($set->contains(0));
        $this->assertTrue($set->contains(2));
        $this->assertFalse($set->contains(1));
        $this->assertFalse($set->contains(3));
    }

    /**
     * @depends testAdd
     * @covers \Ardent\SortedSet::cloneEmpty
     * @covers \Ardent\SortedSet::difference
     */
    function testDifferenceSelf() {
        $a = new SortedSet();
        $a->add(0);

        $diff = $a->difference($a);
        $this->assertInstanceOf('Ardent\\SortedSet', $diff);
        $this->assertNotSame($diff, $a);
        $this->assertCount(0, $diff);
    }

    /**
     * @depends testAdd
     * @covers \Ardent\SortedSet::getIterator
     * @covers \Ardent\SortedSetIterator::__construct
     * @covers \Ardent\SortedSetIterator::count
     * @covers \Ardent\SortedSetIterator::rewind
     */
    function testIteratorEmpty() {
        $set = new SortedSet();
        $iterator = $set->getIterator();
        $this->assertInstanceOf('Ardent\\SortedSetIterator', $iterator);
        $this->assertCount(0, $iterator);
    }

    /**
     * @depends testIteratorEmpty
     * @covers \Ardent\SortedSet::getIterator
     * @covers \Ardent\SortedSetIterator::__construct
     * @covers \Ardent\SortedSetIterator::count
     * @covers \Ardent\SortedSetIterator::rewind
     * @covers \Ardent\SortedSetIterator::valid
     * @covers \Ardent\SortedSetIterator::key
     * @covers \Ardent\SortedSetIterator::current
     * @covers \Ardent\SortedSetIterator::next
     */
    function testIterator() {
        $set = new SortedSet();
        $set->add(0);
        $set->add(2);
        $set->add(4);
        $set->add(6);

        $iterator = $set->getIterator();
        $this->assertInstanceOf('Ardent\\SortedSetIterator', $iterator);
        $this->assertCount(4, $iterator);

        $iterator->rewind();

        for ($i = 0; $i < count($set); $i++) {
            $this->assertTrue($iterator->valid());

            $this->assertEquals($i, $iterator->key());
            $this->assertEquals($i * 2, $iterator->current());

            $iterator->next();
        }

        $this->assertFalse($iterator->valid());
        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());

        $iterator->next();
        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());
    }

}

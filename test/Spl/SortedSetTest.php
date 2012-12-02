<?php

namespace Spl;

class SortedSetTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Spl\SortedSet::add
     * @covers \Spl\SortedSet::count
     * @covers \Spl\SortedSet::__construct
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
     * @covers \Spl\SortedSet::remove
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
     * @covers \Spl\SortedSet::contains
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
     * @covers \Spl\SortedSet::isEmpty
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
     * @covers \Spl\SortedSet::clear
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
     * @covers \Spl\SortedSet::addAll
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
     * @covers \Spl\SortedSet::removeAll
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
     * @covers \Spl\SortedSet::difference
     */
    function testDifferenceSelf() {
        $a = new SortedSet();
        $a->add(0);
        $a->add(1);
        $a->add(2);
        $a->add(3);

        $diff = $a->difference($a);
        $this->assertInstanceOf('Spl\\SortedSet', $diff);
        $this->assertNotSame($diff, $a);
        $this->assertCount(0, $diff);
    }

    /**
     * @depends testAdd
     * @covers \Spl\SortedSet::difference
     */
    function testDifferenceNone() {
        $a = new SortedSet();
        $a->add(0);
        $a->add(1);
        $a->add(2);
        $a->add(3);


        $b = new SortedSet();
        $b->add(0);
        $b->add(1);
        $b->add(2);
        $b->add(3);

        $diff = $a->difference($b);
        $this->assertInstanceOf('Spl\\SortedSet', $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);
        $this->assertCount(0, $diff);
    }

    /**
     * @depends testDifferenceNone
     * @covers \Spl\SortedSet::difference
     */
    function testDifferenceAll() {
        $a = new SortedSet();
        $a->add(0);
        $a->add(1);
        $a->add(2);
        $a->add(3);


        $b = new SortedSet();

        $diff = $a->difference($b);
        $this->assertCount(4, $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);

        for ($i = 0; $i < 4; $i++) {
            $this->assertTrue($a->contains($i));
        }
    }

    /**
     * @depends testDifferenceNone
     * @covers \Spl\SortedSet::difference
     */
    function testDifferenceSome() {
        $a = new SortedSet();
        $a->add(0);
        $a->add(1);
        $a->add(2);
        $a->add(3);


        $b = new SortedSet();
        $b->add(1);
        $b->add(3);
        $b->add(5);
        $b->add(7);

        $diff = $a->difference($b);
        $this->assertCount(2, $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);

        $this->assertTrue($diff->contains(0));
        $this->assertTrue($diff->contains(2));

        $diff = $b->difference($a);
        $this->assertCount(2, $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);

        $this->assertTrue($diff->contains(5));
        $this->assertTrue($diff->contains(7));
    }

    /**
     * @depends testAdd
     * @covers \Spl\SortedSet::getIterator
     * @covers \Spl\SortedSetIterator::__construct
     * @covers \Spl\SortedSetIterator::count
     * @covers \Spl\SortedSetIterator::rewind
     */
    function testIteratorEmpty() {
        $set = new SortedSet();
        $iterator = $set->getIterator();
        $this->assertInstanceOf('Spl\\SortedSetIterator', $iterator);
        $this->assertCount(0, $iterator);
    }

    /**
     * @depends testIteratorEmpty
     * @covers \Spl\SortedSet::getIterator
     * @covers \Spl\SortedSetIterator::__construct
     * @covers \Spl\SortedSetIterator::count
     * @covers \Spl\SortedSetIterator::rewind
     * @covers \Spl\SortedSetIterator::valid
     * @covers \Spl\SortedSetIterator::key
     * @covers \Spl\SortedSetIterator::current
     * @covers \Spl\SortedSetIterator::next
     */
    function testIterator() {
        $set = new SortedSet();
        $set->add(0);
        $set->add(2);
        $set->add(4);
        $set->add(6);

        $iterator = $set->getIterator();
        $this->assertInstanceOf('Spl\\SortedSetIterator', $iterator);
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

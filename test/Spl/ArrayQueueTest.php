<?php

namespace Spl;

class ArrayQueueTest extends \PHPUnit_Framework_TestCase {


    /**
     * @covers \Spl\ArrayQueue::count
     * @covers \Spl\ArrayQueue::getArrayList
     * @covers \Spl\ArrayQueue::pushBack
     */
    function testPushBack() {
        $queue = new ArrayQueue();

        $queue->pushBack(0);
        $this->assertCount(1, $queue);
        $list = $queue->getArrayList();
        $this->assertEquals(0, $list[0]);

        $queue->pushBack(1);
        $this->assertCount(2, $queue);
        $list = $queue->getArrayList();
        $this->assertEquals(0, $list[0]);
        $this->assertEquals(1, $list[count($list) - 1]);
    }

    /**
     * @covers \Spl\ArrayQueue::peekFront
     * @expectedException \Spl\EmptyException
     */
    function testPeekFrontEmpty() {
        $queue = new ArrayQueue();
        $queue->peekFront();
    }

    /**
     * @covers \Spl\ArrayQueue::popFront
     * @expectedException \Spl\EmptyException
     */
    function testPopFrontEmpty() {
        $queue = new ArrayQueue();
        $queue->popFront();
    }

    /**
     * @depends testPushBack
     * @covers \Spl\ArrayQueue::peekFront
     */
    function testPeekFront() {
        $queue = new ArrayQueue();
        $queue->pushBack(0);

        $peek = $queue->peekFront();
        $this->assertCount(1, $queue);
        $this->assertEquals(0, $peek);

        $queue->pushBack(1);
        $peek = $queue->peekFront();
        $this->assertCount(2, $queue);
        $this->assertEquals(0, $peek);

        $list = $queue->getArrayList();
        $this->assertEquals(0, $list[0]);
        $this->assertEquals(1, $list[1]);
    }

    /**
     * @depends testPushBack
     * @covers \Spl\ArrayQueue::popFront
     */
    function testPopFront() {
        $queue = new ArrayQueue();
        $queue->pushBack(0);

        $popped = $queue->popFront();
        $this->assertCount(0, $queue);
        $this->assertEquals(0, $popped);

        $queue->pushBack(0);
        $queue->pushBack(1);
        $this->assertEquals(0, $queue->popFront());
        $this->assertCount(1, $queue);
        $this->assertEquals(1, $queue->popFront());
        $this->assertCount(0, $queue);
    }

    /**
     * @depends testPushBack
     * @covers \Spl\ArrayQueue::clear
     */
    function testClear() {
        $queue = new ArrayQueue();

        $queue->clear();
        $this->assertCount(0, $queue);

        $queue->pushBack(0);
        $queue->clear();
        $this->assertCount(0, $queue);
    }

    /**
     * @depends testPushBack
     * @covers \Spl\ArrayQueue::isEmpty
     */
    function testIsEmpty() {
        $queue = new ArrayQueue();

        $this->assertTrue($queue->isEmpty());

        $queue->pushBack(0);

        $this->assertFalse($queue->isEmpty());
    }

    /**
     * @depends testPushBack
     * @covers \Spl\ArrayQueue::contains
     */
    function testContains() {
        $queue = new ArrayQueue();

        $this->assertFalse($queue->contains(1));

        $queue->pushBack(0);
        $this->assertFalse($queue->contains(1));

        $queue->pushBack(1);
        $this->assertTrue($queue->contains(1));
    }

    /**
     * @depends testPushBack
     * @covers \Spl\ArrayQueue::getIterator
     * @covers \Spl\ArrayQueueIterator::__construct
     * @covers \Spl\ArrayQueueIterator::rewind
     * @covers \Spl\ArrayQueueIterator::valid
     * @covers \Spl\ArrayQueueIterator::key
     * @covers \Spl\ArrayQueueIterator::current
     * @covers \Spl\ArrayQueueIterator::next
     * @covers \Spl\ArrayQueueIterator::count
     */
    function testIterator() {
        $queue = new ArrayQueue();
        $queue->pushBack(0);
        $queue->pushBack(1);
        $queue->pushBack(2);
        $queue->pushBack(3);

        $iterator = $queue->getIterator();

        $this->assertCount(count($queue), $iterator);

        $this->assertTrue($iterator->valid());
        $this->assertEquals(0, $iterator->key());
        $this->assertEquals(0, $iterator->current());

        $iterator->rewind();

        for ($i = 0; $i < count($queue); $i++) {
            $this->assertTrue($iterator->valid());
            $this->assertEquals($i, $iterator->key());
            $this->assertEquals($i, $iterator->current());
            $iterator->next();
        }

        $this->assertFalse($iterator->valid());
        $iterator->next();
        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());
    }

}

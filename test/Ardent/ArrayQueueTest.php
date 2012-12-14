<?php

namespace Ardent;

class ArrayQueueTest extends \PHPUnit_Framework_TestCase {


    /**
     * @covers \Ardent\ArrayQueue::count
     * @covers \Ardent\ArrayQueue::getArrayList
     * @covers \Ardent\ArrayQueue::pushBack
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
     * @covers \Ardent\ArrayQueue::peekFront
     * @expectedException \Ardent\EmptyException
     */
    function testPeekFrontEmpty() {
        $queue = new ArrayQueue();
        $queue->peekFront();
    }

    /**
     * @covers \Ardent\ArrayQueue::popFront
     * @expectedException \Ardent\EmptyException
     */
    function testPopFrontEmpty() {
        $queue = new ArrayQueue();
        $queue->popFront();
    }

    /**
     * @depends testPushBack
     * @covers \Ardent\ArrayQueue::peekFront
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
     * @covers \Ardent\ArrayQueue::popFront
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
     * @covers \Ardent\ArrayQueue::clear
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
     * @covers \Ardent\ArrayQueue::isEmpty
     */
    function testIsEmpty() {
        $queue = new ArrayQueue();

        $this->assertTrue($queue->isEmpty());

        $queue->pushBack(0);

        $this->assertFalse($queue->isEmpty());
    }

    /**
     * @depends testPushBack
     * @covers \Ardent\ArrayQueue::contains
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
     * @covers \Ardent\ArrayQueue::getIterator
     * @covers \Ardent\ArrayQueueIterator::__construct
     * @covers \Ardent\ArrayQueueIterator::rewind
     * @covers \Ardent\ArrayQueueIterator::valid
     * @covers \Ardent\ArrayQueueIterator::key
     * @covers \Ardent\ArrayQueueIterator::current
     * @covers \Ardent\ArrayQueueIterator::next
     * @covers \Ardent\ArrayQueueIterator::count
     */
    function testIterator() {
        $queue = new ArrayQueue();
        $queue->pushBack(1);
        $queue->pushBack(2);
        $queue->pushBack(3);
        $queue->pushBack(4);

        $iterator = $queue->getIterator();

        $this->assertCount(count($queue), $iterator);

        $this->assertTrue($iterator->valid());
        $this->assertEquals(0, $iterator->key());
        $this->assertEquals(1, $iterator->current());

        $iterator->rewind();

        for ($i = 0; $i < count($queue); $i++) {
            $this->assertTrue($iterator->valid());
            $this->assertEquals($i, $iterator->key());
            $this->assertEquals($i + 1, $iterator->current());
            $iterator->next();
        }

        $this->assertFalse($iterator->valid());
        $iterator->next();
        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());
    }

}

<?php

namespace Spl;

class LinkedQueueTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Spl\LinkedQueue::__construct
     * @covers \Spl\LinkedQueue::count
     * @covers \Spl\LinkedQueue::getLinkedList
     * @covers \Spl\LinkedQueue::pushBack
     */
    function testPushBack() {
        $queue = new LinkedQueue();

        $queue->pushBack(0);
        $this->assertCount(1, $queue);
        $list = $queue->getLinkedList();
        $this->assertEquals(0, $list->peekBack());

        $queue->pushBack(1);
        $this->assertCount(2, $queue);
        $list = $queue->getLinkedList();
        $this->assertEquals(0, $list->peekFront());
        $this->assertEquals(1, $list->peekBack());
    }

    /**
     * @depends testPushBack
     * @covers \Spl\LinkedQueue::contains
     */
    function testContains() {
        $queue = new LinkedQueue();

        $this->assertFalse($queue->contains(0));

        $queue->pushBack(0);
        $this->assertTrue($queue->contains(0));

        $queue->pushBack(1);
        $this->assertTrue($queue->contains(0));
        $this->assertTrue($queue->contains(1));

        $this->assertFalse($queue->contains(-1));
    }

    /**
     * @depends testPushBack
     * @covers \Spl\LinkedQueue::isEmpty
     */
    function testIsEmpty() {
        $queue = new LinkedQueue();
        $this->assertTrue($queue->isEmpty());

        $queue->pushBack(0);
        $this->assertFalse($queue->isEmpty());
    }

    /**
     * @covers \Spl\LinkedQueue::peekFront
     * @expectedException \Spl\EmptyException
     */
    function testPeekFrontEmpty() {
        $queue = new LinkedQueue();
        $queue->peekFront();
    }

    /**
     * @depends testPushBack
     * @covers \Spl\LinkedQueue::peekFront
     */
    function testPeekFront() {
        $queue = new LinkedQueue();
        $queue->pushBack(0);

        $this->assertEquals(0, $queue->peekFront());
        $this->assertCount(1, $queue);

        $queue->pushBack(1);

        $this->assertEquals(0, $queue->peekFront());
        $this->assertCount(2, $queue);

        $list = $queue->getLinkedList();
        $this->assertEquals(0, $list->peekFront());
        $this->assertEquals(1, $list->peekBack());
    }

    /**
     * @covers \Spl\LinkedQueue::popFront
     * @expectedException \Spl\EmptyException
     */
    function testPopFrontEmpty() {
        $queue = new LinkedQueue();
        $queue->popFront();
    }

    /**
     * @depends testPushBack
     * @covers \Spl\LinkedQueue::popFront
     */
    function testPushFront() {
        $queue = new LinkedQueue();
        $queue->pushBack(0);

        $this->assertEquals(0, $queue->popFront());
        $this->assertCount(0, $queue);

        $queue->pushBack(0);
        $queue->pushBack(1);

        $this->assertEquals(0, $queue->popFront());
        $this->assertCount(1, $queue);

        $list = $queue->getLinkedList();
        $this->assertEquals(1, $list->peekFront());
    }

    /**
     * @covers \Spl\LinkedQueue::getIterator
     */
    function testGetIteratorEmpty() {
        $queue = new LinkedQueue();
        $iterator = $queue->getIterator();
        $this->assertInstanceOf('Spl\\QueueIterator', $iterator);
    }

    /**
     * @covers \Spl\LinkedQueue::getIterator
     * @covers \Spl\LinkedQueueIterator::__construct
     * @covers \Spl\LinkedQueueIterator::rewind
     * @covers \Spl\LinkedQueueIterator::valid
     * @covers \Spl\LinkedQueueIterator::key
     * @covers \Spl\LinkedQueueIterator::current
     * @covers \Spl\LinkedQueueIterator::next
     * @covers \Spl\LinkedQueueIterator::count
     */
    function testIteratorForeach() {
        $queue = new LinkedQueue();
        $queue->pushBack(0);
        $queue->pushBack(1);
        $queue->pushBack(2);
        $queue->pushBack(3);

        $iterator = $queue->getIterator();
        $this->assertInstanceOf('Spl\\LinkedQueueIterator', $iterator);

        $this->assertCount(4, $iterator);

        $expectedKey = 0;
        $expectedValue = 0;
        $iterator->rewind();

        for ($i = 0; $i < 4; $i++) {
            $this->assertTrue($iterator->valid());
            $this->assertEquals($expectedKey++, $iterator->key());
            $this->assertEquals($expectedValue++, $iterator->current());
            $iterator->next();
        }

        $this->assertFalse($iterator->valid());

        $iterator->next();
        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());

    }
}

<?php

namespace Ardent;

class LinkedQueueTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Ardent\LinkedQueue::count
     * @covers \Ardent\LinkedQueue::isEmpty
     * @covers \Ardent\LinkedQueue::push
     */
    function testPushOne() {
        $queue = new LinkedQueue();
        $this->assertCount(0, $queue);
        $this->assertTrue($queue->isEmpty());

        $queue->push(0);
        $this->assertCount(1, $queue);
        $this->assertFalse($queue->isEmpty());
    }

    /**
     * @depends testPushOne
     * @covers \Ardent\LinkedQueue::count
     * @covers \Ardent\LinkedQueue::isEmpty
     * @covers \Ardent\LinkedQueue::peek
     * @covers \Ardent\LinkedQueue::push
     */
    function testPeekOne() {
        $queue = new LinkedQueue();
        $queue->push(1);
        $this->assertCount(1, $queue);
        $this->assertFalse($queue->isEmpty());

        $peek = $queue->peek();
        $this->assertCount(1, $queue);
        $this->assertFalse($queue->isEmpty());
        $this->assertEquals(1, $peek);
    }

    /**
     * @depends testPeekOne
     * @covers \Ardent\LinkedQueue::count
     * @covers \Ardent\LinkedQueue::isEmpty
     * @covers \Ardent\LinkedQueue::pop
     * @covers \Ardent\LinkedQueue::push
     */
    function testPopOne() {
        $queue = new LinkedQueue();
        $queue->push(1);
        $this->assertCount(1, $queue);
        $this->assertFalse($queue->isEmpty());

        $pop = $queue->pop();
        $this->assertCount(0, $queue);
        $this->assertTrue($queue->isEmpty());
        $this->assertEquals(1, $pop);
    }

    /**
     * @depends testPopOne
     */
    function testMultiplePushPeekPop() {
        $queue = new LinkedQueue();
        $queue->push(1);
        $queue->push(3);
        $queue->push(5);

        $peek = $queue->peek();
        $this->assertEquals(1, $peek);
        $this->assertCount(3, $queue);
        $this->assertFalse($queue->isEmpty());

        $pop = $queue->pop();
        $this->assertEquals(1, $pop);
        $this->assertCount(2, $queue);
        $this->assertFalse($queue->isEmpty());

        $peek = $queue->peek();
        $this->assertEquals(3, $peek);
        $this->assertCount(2, $queue);
        $this->assertFalse($queue->isEmpty());

        $pop = $queue->pop();
        $this->assertEquals(3, $pop);
        $this->assertCount(1, $queue);
        $this->assertFalse($queue->isEmpty());

        $peek = $queue->peek();
        $this->assertEquals(5, $peek);
        $this->assertCount(1, $queue);
        $this->assertFalse($queue->isEmpty());

        $pop = $queue->pop();
        $this->assertEquals(5, $pop);
        $this->assertCount(0, $queue);
        $this->assertTrue($queue->isEmpty());
    }

    /**
     * @covers \Ardent\LinkedQueue::peek
     * @expectedException \Ardent\EmptyException
     */
    function testPeekEmpty() {
        $queue = new LinkedQueue();
        $queue->peek();
    }

    /**
     * @covers \Ardent\LinkedQueue::pop
     * @expectedException \Ardent\EmptyException
     */
    function testPopEmpty() {
        $queue = new LinkedQueue();
        $queue->pop();
    }

    /**
     * @covers \Ardent\LinkedQueue::clonePair
     * @covers \Ardent\LinkedQueue::getIterator
     */
    function testGetIteratorEmpty() {
        $queue = new LinkedQueue();
        $iterator = $queue->getIterator();
        $this->assertInstanceOf('Ardent\\LinkedQueueIterator', $iterator);
    }

    /**
     * @depends testMultiplePushPeekPop
     * @covers \Ardent\LinkedQueue::clonePair
     * @covers \Ardent\LinkedQueue::getIterator
     * @covers \Ardent\LinkedQueueIterator::__construct
     * @covers \Ardent\LinkedQueueIterator::rewind
     * @covers \Ardent\LinkedQueueIterator::valid
     * @covers \Ardent\LinkedQueueIterator::key
     * @covers \Ardent\LinkedQueueIterator::current
     * @covers \Ardent\LinkedQueueIterator::next
     * @covers \Ardent\LinkedQueueIterator::count
     */
    function testIteratorForeach() {
        $queue = new LinkedQueue();
        $queue->push(1);
        $queue->push(2);
        $queue->push(3);
        $queue->push(4);

        $iterator = $queue->getIterator();
        $this->assertInstanceOf('Ardent\\LinkedQueueIterator', $iterator);

        $this->assertCount(4, $iterator);

        $expectedKey = 0;
        $expectedValue = 1;
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

<?php

namespace Ardent;

class LinkedQueueTest extends \PHPUnit_Framework_TestCase {

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
     * @expectedException \Ardent\Exception\EmptyException
     */
    function testPeekEmpty() {
        $queue = new LinkedQueue();
        $queue->peek();
    }

    /**
     * @expectedException \Ardent\Exception\EmptyException
     */
    function testPopEmpty() {
        $queue = new LinkedQueue();
        $queue->pop();
    }

    function testGetIteratorEmpty() {
        $queue = new LinkedQueue();
        $iterator = $queue->getIterator();
        $this->assertInstanceOf('Ardent\\Iterator\\LinkedQueueIterator', $iterator);
    }

    /**
     * @depends testMultiplePushPeekPop
     */
    function testIteratorForeach() {
        $queue = new LinkedQueue();
        $queue->push(1);
        $queue->push(2);
        $queue->push(3);
        $queue->push(4);

        $iterator = $queue->getIterator();
        $this->assertInstanceOf('Ardent\\Iterator\\LinkedQueueIterator', $iterator);

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

    /**
     * @depends testPopOne
     */
    function testAny() {
        $equalsZero = function($item) {
            return $item === 0;
        };
        $queue = new LinkedQueue();
        $this->assertFalse($queue->any($equalsZero));

        $queue->push(1);
        $this->assertFalse($queue->any($equalsZero));

        $queue->push(0);
        $this->assertTrue($queue->any($equalsZero));

        $queue->pop();
        $this->assertTrue($queue->any($equalsZero));

        $queue->pop();
        $this->assertFalse($queue->any($equalsZero));
    }

    /**
     * @depends testPopOne
     */
    function testContains() {
        $queue = new LinkedQueue();
        $this->assertFalse($queue->contains(0));

        $queue->push(1);
        $this->assertFalse($queue->contains(0));

        $queue->push(0);
        $this->assertTrue($queue->contains(0));

        $queue->pop();
        $this->assertTrue($queue->contains(0));

        $queue->pop();
        $this->assertFalse($queue->contains(0));
    }

    function testMap() {
        $queue = new LinkedQueue();
        $driver = new CollectionTestDriver();
        $driver->doMapTests($queue, [$queue, 'push']);
    }

}

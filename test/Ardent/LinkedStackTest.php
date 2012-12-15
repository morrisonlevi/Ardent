<?php

namespace Ardent;

class LinkedStackTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Ardent\LinkedStack::count
     * @covers \Ardent\LinkedStack::isEmpty
     * @covers \Ardent\LinkedStack::push
     */
    function testPushOne() {
        $stack = new LinkedStack();
        $this->assertTrue($stack->isEmpty());
        $this->assertCount(0, $stack);

        $stack->push(0);
        $this->assertFalse($stack->isEmpty());
        $this->assertCount(1, $stack);
    }

    /**
     * @depends testPushOne
     * @covers \Ardent\LinkedStack::peek
     * @covers \Ardent\LinkedStack::push
     */
    function testPeekOne() {
        $stack = new LinkedStack();
        $stack->push(1);

        $item = $stack->peek();
        $this->assertEquals(1, $item);
        $this->assertFalse($stack->isEmpty());
        $this->assertCount(1, $stack);
    }

    /**
     * @depends testPushOne
     * @covers \Ardent\LinkedStack::pop
     * @covers \Ardent\LinkedStack::push
     */
    function testPopOne() {
        $stack = new LinkedStack();
        $stack->push(1);

        $item = $stack->pop();
        $this->assertEquals(1, $item);
        $this->assertTrue($stack->isEmpty());
        $this->assertCount(0, $stack);
    }

    /**
     * @depends testPopOne
     */
    function testMultiplePushPeekPop() {
        $stack = new LinkedStack();
        $stack->push(1);
        $stack->push(3);
        $stack->push(5);

        $this->assertCount(3, $stack);
        $this->assertFalse($stack->isEmpty());

        $peek = $stack->peek();
        $this->assertEquals(5, $peek);
        $this->assertCount(3, $stack);
        $this->assertFalse($stack->isEmpty());

        $pop = $stack->pop();
        $this->assertEquals(5, $pop);
        $this->assertCount(2, $stack);
        $this->assertFalse($stack->isEmpty());

        $peek = $stack->peek();
        $this->assertEquals(3, $peek);
        $this->assertCount(2, $stack);
        $this->assertFalse($stack->isEmpty());

        $pop = $stack->pop();
        $this->assertEquals(3, $pop);
        $this->assertCount(1, $stack);
        $this->assertFalse($stack->isEmpty());

        $peek = $stack->peek();
        $this->assertEquals(1, $peek);
        $this->assertCount(1, $stack);
        $this->assertFalse($stack->isEmpty());

        $pop = $stack->pop();
        $this->assertEquals(1, $pop);
        $this->assertCount(0, $stack);
        $this->assertTrue($stack->isEmpty());
    }

    /**
     * @covers \Ardent\LinkedStack::peek
     * @expectedException \Ardent\EmptyException
     */
    function testPeekEmpty() {
        $stack = new LinkedStack();
        $stack->peek();
    }

    /**
     * @covers \Ardent\LinkedStack::pop
     * @expectedException \Ardent\EmptyException
     */
    function testPopEmpty() {
        $stack = new LinkedStack();
        $stack->pop();
    }

    /**
     * @covers \Ardent\LinkedStack::getIterator
     */
    function testGetIteratorEmpty() {
        $stack = new LinkedStack();
        $iterator = $stack->getIterator();
        $this->assertInstanceOf('Ardent\\StackIterator', $iterator);
    }

    /**
     * @covers \Ardent\LinkedStack::getIterator
     * @covers \Ardent\LinkedStackIterator::__construct
     * @covers \Ardent\LinkedStackIterator::rewind
     * @covers \Ardent\LinkedStackIterator::valid
     * @covers \Ardent\LinkedStackIterator::key
     * @covers \Ardent\LinkedStackIterator::current
     * @covers \Ardent\LinkedStackIterator::next
     * @covers \Ardent\LinkedStackIterator::count
     */
    function testIteratorForeach() {
        $stack = new LinkedStack();
        $stack->push(1);
        $stack->push(2);
        $stack->push(3);
        $stack->push(4);

        $iterator = $stack->getIterator();
        $this->assertInstanceOf('Ardent\\LinkedStackIterator', $iterator);

        $this->assertCount(4, $iterator);

        $expectedKey = 0;
        $expectedValue = 4;
        $iterator->rewind();

        for ($i = 0; $i < 4; $i++) {
            $this->assertTrue($iterator->valid());
            $this->assertEquals($expectedKey++, $iterator->key());
            $this->assertEquals($expectedValue--, $iterator->current());
            $iterator->next();
        }

        $this->assertFalse($iterator->valid());
        $iterator->next();
        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());

    }

}

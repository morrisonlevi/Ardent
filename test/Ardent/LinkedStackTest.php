<?php

namespace Ardent;

class LinkedStackTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Ardent\LinkedStack::__construct
     * @covers \Ardent\LinkedStack::count
     * @covers \Ardent\LinkedStack::getLinkedList
     * @covers \Ardent\LinkedStack::push
     */
    function testPushBack() {
        $stack = new LinkedStack();

        $stack->push(0);
        $this->assertCount(1, $stack);
        $list = $stack->getLinkedList();
        $this->assertEquals(0, $list->peekBack());

        $stack->push(1);
        $this->assertCount(2, $stack);
        $list = $stack->getLinkedList();
        $this->assertEquals(0, $list->peekFront());
        $this->assertEquals(1, $list->peekBack());
    }

    /**
     * @depends testPushBack
     * @covers \Ardent\LinkedStack::contains
     */
    function testContains() {
        $stack = new LinkedStack();

        $this->assertFalse($stack->contains(0));

        $stack->push(0);
        $this->assertTrue($stack->contains(0));

        $stack->push(1);
        $this->assertTrue($stack->contains(0));
        $this->assertTrue($stack->contains(1));

        $this->assertFalse($stack->contains(-1));
    }

    /**
     * @depends testPushBack
     * @covers \Ardent\LinkedStack::isEmpty
     */
    function testIsEmpty() {
        $stack = new LinkedStack();
        $this->assertTrue($stack->isEmpty());

        $stack->push(0);
        $this->assertFalse($stack->isEmpty());
    }

    /**
     * @covers \Ardent\LinkedStack::peek
     * @expectedException \Ardent\EmptyException
     */
    function testPeekBackEmpty() {
        $stack = new LinkedStack();
        $stack->peek();
    }

    /**
     * @depends testPushBack
     * @covers \Ardent\LinkedStack::peek
     */
    function testPeekBack() {
        $stack = new LinkedStack();
        $stack->push(0);

        $this->assertEquals(0, $stack->peek());
        $this->assertCount(1, $stack);

        $stack->push(1);

        $this->assertEquals(1, $stack->peek());
        $this->assertCount(2, $stack);

        $list = $stack->getLinkedList();
        $this->assertEquals(0, $list->peekFront());
        $this->assertEquals(1, $list->peekBack());
    }

    /**
     * @covers \Ardent\LinkedStack::pop
     * @expectedException \Ardent\EmptyException
     */
    function testPopBackEmpty() {
        $stack = new LinkedStack();
        $stack->pop();
    }

    /**
     * @depends testPushBack
     * @covers \Ardent\LinkedStack::pop
     */
    function testPopBack() {
        $stack = new LinkedStack();
        $stack->push(0);

        $popped = $stack->pop();
        $this->count(0, $stack);
        $this->assertEquals(0, $popped);

        $stack->push(0);
        $stack->push(1);
        $this->count(2, $stack);

        $popped = $stack->pop();
        $this->assertEquals(1, $popped);

        $popped = $stack->pop();
        $this->assertEquals(0, $popped);
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

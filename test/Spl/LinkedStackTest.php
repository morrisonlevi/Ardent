<?php

namespace Spl;

class LinkedStackTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Spl\LinkedStack::__construct
     * @covers \Spl\LinkedStack::count
     * @covers \Spl\LinkedStack::getLinkedList
     * @covers \Spl\LinkedStack::pushBack
     */
    function testPushBack() {
        $stack = new LinkedStack();

        $stack->pushBack(0);
        $this->assertCount(1, $stack);
        $list = $stack->getLinkedList();
        $this->assertEquals(0, $list->peekBack());

        $stack->pushBack(1);
        $this->assertCount(2, $stack);
        $list = $stack->getLinkedList();
        $this->assertEquals(0, $list->peekFront());
        $this->assertEquals(1, $list->peekBack());
    }

    /**
     * @depends testPushBack
     * @covers \Spl\LinkedStack::contains
     */
    function testContains() {
        $stack = new LinkedStack();

        $this->assertFalse($stack->contains(0));

        $stack->pushBack(0);
        $this->assertTrue($stack->contains(0));

        $stack->pushBack(1);
        $this->assertTrue($stack->contains(0));
        $this->assertTrue($stack->contains(1));

        $this->assertFalse($stack->contains(-1));
    }

    /**
     * @depends testPushBack
     * @covers \Spl\LinkedStack::isEmpty
     */
    function testIsEmpty() {
        $stack = new LinkedStack();
        $this->assertTrue($stack->isEmpty());

        $stack->pushBack(0);
        $this->assertFalse($stack->isEmpty());
    }

    /**
     * @covers \Spl\LinkedStack::peekBack
     * @expectedException \Spl\EmptyException
     */
    function testPeekBackEmpty() {
        $stack = new LinkedStack();
        $stack->peekBack();
    }

    /**
     * @depends testPushBack
     * @covers \Spl\LinkedStack::peekBack
     */
    function testPeekBack() {
        $stack = new LinkedStack();
        $stack->pushBack(0);

        $this->assertEquals(0, $stack->peekBack());
        $this->assertCount(1, $stack);

        $stack->pushBack(1);

        $this->assertEquals(1, $stack->peekBack());
        $this->assertCount(2, $stack);

        $list = $stack->getLinkedList();
        $this->assertEquals(0, $list->peekFront());
        $this->assertEquals(1, $list->peekBack());
    }

    /**
     * @covers \Spl\LinkedStack::popBack
     * @expectedException \Spl\EmptyException
     */
    function testPopBackEmpty() {
        $stack = new LinkedStack();
        $stack->popBack();
    }

    /**
     * @depends testPushBack
     * @covers \Spl\LinkedStack::popBack
     */
    function testPopBack() {
        $stack = new LinkedStack();
        $stack->pushBack(0);

        $popped = $stack->popBack();
        $this->count(0, $stack);
        $this->assertEquals(0, $popped);

        $stack->pushBack(0);
        $stack->pushBack(1);
        $this->count(2, $stack);

        $popped = $stack->popBack();
        $this->assertEquals(1, $popped);

        $popped = $stack->popBack();
        $this->assertEquals(0, $popped);
    }

    /**
     * @covers \Spl\LinkedStack::getIterator
     */
    function testGetIteratorEmpty() {
        $stack = new LinkedStack();
        $iterator = $stack->getIterator();
        $this->assertInstanceOf('Spl\\StackIterator', $iterator);
    }

    /**
     * @covers \Spl\LinkedStack::getIterator
     * @covers \Spl\LinkedStackIterator::__construct
     * @covers \Spl\LinkedStackIterator::rewind
     * @covers \Spl\LinkedStackIterator::valid
     * @covers \Spl\LinkedStackIterator::key
     * @covers \Spl\LinkedStackIterator::current
     * @covers \Spl\LinkedStackIterator::next
     * @covers \Spl\LinkedStackIterator::count
     */
    function testIteratorForeach() {
        $stack = new LinkedStack();
        $stack->pushBack(1);
        $stack->pushBack(2);
        $stack->pushBack(3);
        $stack->pushBack(4);

        $iterator = $stack->getIterator();
        $this->assertInstanceOf('Spl\\LinkedStackIterator', $iterator);

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

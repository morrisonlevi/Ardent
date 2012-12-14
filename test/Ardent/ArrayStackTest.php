<?php

namespace Ardent;

class ArrayStackTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ArrayStack
     */
    protected $stack;

    protected function setUp() {
        $this->stack = new ArrayStack;
    }

    /**
     * @covers \Ardent\ArrayStack::pushBack
     * @covers \Ardent\ArrayStack::count
     */
    function testPush() {
        $this->stack->pushBack(0);
        $this->assertCount(1, $this->stack);

        $this->stack->pushBack(0);
        $this->assertCount(2, $this->stack);
    }

    /**
     * @covers \Ardent\ArrayStack::popBack
     * @depends testPush
     */
    function testPop() {
        $inItem = 0;
        $this->stack->pushBack($inItem);

        $outItem = $this->stack->popBack();

        $this->assertEquals($inItem, $outItem);
        $this->assertCount(0, $this->stack);
    }

    /**
     * @covers \Ardent\ArrayStack::popBack
     * @expectedException \Ardent\EmptyException
     */
    function testPopException() {
        $this->stack->popBack();
    }

    /**
     * @covers \Ardent\ArrayStack::peekBack
     * @depends testPush
     */
    function testPeek() {
        $inItem = 0;
        $this->stack->pushBack($inItem);

        $peekedItem = $this->stack->peekBack();

        $this->assertEquals($inItem, $peekedItem);
        $this->assertCount(1, $this->stack);

        $secondInItem = 1;
        $this->stack->pushBack($secondInItem);
        $secondPeekedItem = $this->stack->peekBack();

        $this->assertEquals($secondInItem, $secondPeekedItem);
        $this->assertCount(2, $this->stack);
    }

    /**
     * @covers \Ardent\ArrayStack::peekBack
     * @expectedException \Ardent\EmptyException
     */
    function testPeekException() {
        $this->stack->peekBack();
    }

    /**
     * @covers \Ardent\ArrayStack::clear
     * @depends testPush
     */
    function testClear() {
        $this->stack->pushBack(0);

        $this->stack->clear();
        $this->assertCount(0, $this->stack);
    }

    /**
     * @covers \Ardent\ArrayStack::contains
     * @depends testPush
     */
    function testContains() {
        $item = 0;
        $this->assertFalse($this->stack->contains($item));
        $this->stack->pushBack($item);
        $this->assertTrue($this->stack->contains($item));
    }

    /**
     * @covers \Ardent\ArrayStack::isEmpty
     * @depends testPush
     */
    function testIsEmpty() {
        $this->assertTrue($this->stack->isEmpty());
        $this->stack->pushBack(0);
        $this->assertFalse($this->stack->isEmpty());
    }

    /**
     * @covers \Ardent\ArrayStack::getIterator
     * @covers \Ardent\ArrayStackIterator::__construct
     * @covers \Ardent\ArrayStackIterator::rewind
     */
    function testIteratorEmpty() {
        $iterator = $this->stack->getIterator();

        $this->assertInstanceOf('Ardent\\ArrayStackIterator', $iterator);
    }

    /**
     * @covers \Ardent\ArrayStackIterator::rewind
     * @covers \Ardent\ArrayStackIterator::valid
     * @covers \Ardent\ArrayStackIterator::key
     * @covers \Ardent\ArrayStackIterator::current
     * @covers \Ardent\ArrayStackIterator::next
     */
    function testIteratorForeach() {
        for ($i = 0; $i < $size = 5; $i++) {
            $this->stack->pushBack($i);
        }

        $expectedKey = 0;
        $expectedValue = 4;
        foreach ($this->stack as $key => $value) {
            $this->assertEquals($expectedKey++, $key);
            $this->assertEquals($expectedValue--, $value);
        }

    }

    /**
     * @covers \Ardent\ArrayStackIterator::count
     */
    function testIteratorCount() {
        $emptyIterator = $this->stack->getIterator();

        $this->assertCount(0, $emptyIterator);

        $this->stack->pushBack(0);
        $iterator = $this->stack->getIterator();
        $this->assertCount(1, $iterator);
    }

    /**
     * @covers \Ardent\ArrayStack::getIterator
     * @covers \Ardent\ArrayStackIterator::__construct
     */
    function testMultipleIterators() {
        $this->stack->pushBack(2);
        $this->stack->pushBack(4);
        $this->stack->pushBack(6);
        $this->stack->pushBack(8);

        $secondIterator = $this->stack->getIterator();

        $i = 0;
        $j = 0;
        foreach ($this->stack as $outer) {
            foreach ($secondIterator as $inner) {
                $j++;
                $i += $inner;
            }
            $i += $outer;
        }
        $this->assertEquals($this->stack->count() * 4, $j);
        $this->assertEquals(100, $i);

    }

}

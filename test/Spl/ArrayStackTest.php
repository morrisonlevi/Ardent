<?php

namespace Spl;

class ArrayStackTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ArrayStack
     */
    protected $stack;

    protected function setUp() {
        $this->stack = new ArrayStack;
    }

    /**
     * @covers \Spl\ArrayStack::pushBack
     * @covers \Spl\ArrayStack::count
     */
    function testPush() {
        $this->stack->pushBack(0);
        $this->assertCount(1, $this->stack);

        $this->stack->pushBack(0);
        $this->assertCount(2, $this->stack);
    }

    /**
     * @covers \Spl\ArrayStack::popBack
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
     * @covers \Spl\ArrayStack::popBack
     * @expectedException \Spl\EmptyException
     */
    function testPopException() {
        $this->stack->popBack();
    }

    /**
     * @covers \Spl\ArrayStack::peekBack
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
     * @covers \Spl\ArrayStack::peekBack
     * @expectedException \Spl\EmptyException
     */
    function testPeekException() {
        $this->stack->peekBack();
    }

    /**
     * @covers \Spl\ArrayStack::clear
     * @depends testPush
     */
    function testClear() {
        $this->stack->pushBack(0);

        $this->stack->clear();
        $this->assertCount(0, $this->stack);
    }

    /**
     * @covers \Spl\ArrayStack::contains
     * @depends testPush
     */
    function testContains() {
        $item = 0;
        $this->assertFalse($this->stack->contains($item));
        $this->stack->pushBack($item);
        $this->assertTrue($this->stack->contains($item));
    }

    /**
     * @covers \Spl\ArrayStack::isEmpty
     * @depends testPush
     */
    function testIsEmpty() {
        $this->assertTrue($this->stack->isEmpty());
        $this->stack->pushBack(0);
        $this->assertFalse($this->stack->isEmpty());
    }

    /**
     * @covers \Spl\ArrayStack::getIterator
     * @covers \Spl\ArrayStackIterator::__construct
     * @covers \Spl\ArrayStackIterator::rewind
     */
    function testIteratorEmpty() {
        $iterator = $this->stack->getIterator();

        $this->assertInstanceOf('Spl\\ArrayStackIterator', $iterator);
    }

    /**
     * @covers \Spl\ArrayStackIterator::rewind
     * @covers \Spl\ArrayStackIterator::valid
     * @covers \Spl\ArrayStackIterator::key
     * @covers \Spl\ArrayStackIterator::current
     * @covers \Spl\ArrayStackIterator::next
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
     * @covers \Spl\ArrayStackIterator::count
     */
    function testIteratorCount() {
        $emptyIterator = $this->stack->getIterator();

        $this->assertCount(0, $emptyIterator);

        $this->stack->pushBack(0);
        $iterator = $this->stack->getIterator();
        $this->assertCount(1, $iterator);
    }

    /**
     * @covers \Spl\ArrayStack::getIterator
     * @covers \Spl\ArrayStackIterator::__construct
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

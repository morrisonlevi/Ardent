<?php

namespace Collections;

class LinkedStackTest extends \PHPUnit_Framework_TestCase {

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
     */
    function testPeekOne() {
        $stack = new LinkedStack();
        $stack->push(1);

        $item = $stack->last();
        $this->assertEquals(1, $item);
        $this->assertFalse($stack->isEmpty());
        $this->assertCount(1, $stack);
    }

    /**
     * @depends testPushOne
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

        $peek = $stack->last();
        $this->assertEquals(5, $peek);
        $this->assertCount(3, $stack);
        $this->assertFalse($stack->isEmpty());

        $pop = $stack->pop();
        $this->assertEquals(5, $pop);
        $this->assertCount(2, $stack);
        $this->assertFalse($stack->isEmpty());

        $peek = $stack->last();
        $this->assertEquals(3, $peek);
        $this->assertCount(2, $stack);
        $this->assertFalse($stack->isEmpty());

        $pop = $stack->pop();
        $this->assertEquals(3, $pop);
        $this->assertCount(1, $stack);
        $this->assertFalse($stack->isEmpty());

        $peek = $stack->last();
        $this->assertEquals(1, $peek);
        $this->assertCount(1, $stack);
        $this->assertFalse($stack->isEmpty());

        $pop = $stack->pop();
        $this->assertEquals(1, $pop);
        $this->assertCount(0, $stack);
        $this->assertTrue($stack->isEmpty());
    }

    /**
     * @expectedException \Collections\EmptyException
     */
    function testPeekEmpty() {
        $stack = new LinkedStack();
        $stack->last();
    }

    /**
     * @expectedException \Collections\EmptyException
     */
    function testPopEmpty() {
        $stack = new LinkedStack();
        $stack->pop();
    }

    function testGetIteratorEmpty() {
        $stack = new LinkedStack();
        $iterator = $stack->getIterator();
        $this->assertInstanceOf('Collections\\StackIterator', $iterator);
    }

    function testIteratorForeach() {
        $stack = new LinkedStack();
        $stack->push(1);
        $stack->push(2);
        $stack->push(3);
        $stack->push(4);

        $iterator = $stack->getIterator();
        $this->assertInstanceOf('Collections\\LinkedStackIterator', $iterator);

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

    }


    function testClear() {
        $stack = new LinkedStack();
        $stack->push(1);
        $stack->clear();
        $this->assertCount(0, $stack);
    }


    function testToArrayEmpty() {
        $stack = new LinkedStack();
        $array = $stack->toArray();
        $this->assertTrue(is_array($array));
        $this->assertCount(0, $array);
    }


    function testToArray() {
        $stack = new LinkedStack();
        for ($i = 0; $i < 3; $i++) {
            $stack->push($i);
        }
        $array = $stack->toArray();
        $this->assertTrue(is_array($array));
        $this->assertCount(3, $array);
        $i = 2;
        foreach ($array as $item) {
            $this->assertEquals($i--, $item);
        }
    }

}

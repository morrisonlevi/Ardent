<?php

namespace Spl;

class LinkedListMock extends LinkedList {
    public function getCurrentOffset() {
        return parent::getCurrentOffset();
    }
}

class CallableMock {
    function __invoke($a, $b) {
        return $a[0] == $b[0];
    }
}

class LinkedListTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Spl\LinkedList::count
     * @covers \Spl\LinkedList::getCurrentOffset
     * @covers \Spl\LinkedList::offsetSet
     * @covers \Spl\LinkedList::offsetGet
     * @covers \Spl\LinkedList::pushBack
     * @covers \Spl\LinkedList::__seek
     */
    function testOffsetGetAndSet() {
        $list = new LinkedListMock();

        $list[] = 0;
        $this->assertEquals(0, $list->getCurrentOffset());
        $this->assertCount(1, $list);
        $this->assertEquals(0, $list[0]);

        $list[] = 1;
        $this->assertEquals(1, $list->getCurrentOffset());
        $this->assertCount(2, $list);
        $this->assertEquals(0, $list[0]);
        $this->assertEquals(1, $list[1]);

        $list[0] = 2;
        $this->assertEquals(0, $list->getCurrentOffset());
        $this->assertCount(2, $list);
        $this->assertEquals(2, $list[0]);
        $this->assertEquals(1, $list[1]);
        $this->assertEquals(1, $list->getCurrentOffset());
    }

    /**
     * @covers \Spl\LinkedList::offsetSet
     * @expectedException \Spl\IndexException
     */
    function testOffsetSetIndexException() {
        $list = new LinkedList();
        $list[0] = 0;
    }

    /**
     * @covers \Spl\LinkedList::offsetGet
     * @expectedException \Spl\IndexException
     */
    function testOffsetGetIndexException() {
        $list = new LinkedList();
        $list[0];
    }

    /**
     * @covers \Spl\LinkedList::offsetUnset
     */
    function testOffsetUnsetNonExistent() {
        $list = new LinkedList();
        unset($list[0]);
    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Spl\LinkedList::offsetUnset
     * @covers \Spl\LinkedList::removeNode
     */
    function testOffsetUnsetOneItem() {
        $list = new LinkedList();
        $list[] = 0;
        unset($list[0]);

        $this->assertCount(0, $list);
    }

    /**
     * @covers \Spl\LinkedList::offsetUnset
     * @covers \Spl\LinkedList::removeNode
     * @covers \Spl\LinkedList::seek
     */
    function testOffsetUnsetHead() {
        $list = new LinkedListMock();
        $list[] = 0;
        $list[] = 1;
        unset($list[0]);
        $this->assertEquals(0, $list->getCurrentOffset());

        $this->assertCount(1, $list);
        $this->assertEquals(1, $list[0]);
    }

    /**
     * @covers \Spl\LinkedList::offsetUnset
     * @covers \Spl\LinkedList::removeNode
     * @covers \Spl\LinkedList::seek
     */
    function testOffsetUnsetTail() {
        $list = new LinkedListMock();
        $list[] = 0;
        $list[] = 1;
        unset($list[1]);

        $this->assertEquals(0, $list->getCurrentOffset());

        $this->assertCount(1, $list);
        $this->assertEquals(0, $list[0]);
    }

    /**
     * @covers \Spl\LinkedList::offsetUnset
     * @covers \Spl\LinkedList::removeNode
     * @covers \Spl\LinkedList::seek
     */
    function testOffsetUnsetMiddle() {
        $list = new LinkedListMock();
        $list[] = 0;
        $list[] = 1;
        $list[] = 2;
        unset($list[1]);

        $this->assertEquals(1, $list->getCurrentOffset());

        $this->assertCount(2, $list);
        $this->assertEquals(0, $list[0]);
        $this->assertEquals(2, $list[1]);
    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Spl\LinkedList::offsetExists
     */
    function testOffsetExists() {
        $list = new LinkedListMock();

        $this->assertFalse($list->offsetExists(0));
        $this->assertFalse($list->offsetExists(-1));

        $list[] = 0;
        $this->assertTrue($list->offsetExists(0));

    }

    /**
     * @depends testOffsetGetAndSet
     * @depends testOffsetUnsetOneItem
     * @covers \Spl\LinkedList::isEmpty
     * @covers \Spl\LinkedList::offsetUnset
     * @covers \Spl\LinkedList::offsetSet
     * @covers \Spl\LinkedList::pushBack
     */
    function testIsEmpty() {
        $list = new LinkedListMock();

        $this->assertTrue($list->isEmpty());

        $list[] = 0;
        $this->assertFalse($list->isEmpty());

        unset($list[0]);

        $this->assertTrue($list->isEmpty());

    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Spl\LinkedList::indexOf
     * @covers \Spl\LinkedList::__equals
     */
    function testIndexOf() {
        $list = new LinkedListMock();
        $valueA = 0;

        $this->assertEquals(-1, $list->indexOf($valueA));

        $list[] = $valueA;
        $this->assertEquals(0, $list->indexOf($valueA));

        $valueB = 1;
        $this->assertEquals(-1, $list->indexOf($valueB));

        $list[] = $valueB;

        // reset the internal pointer so it actually searches the whole list.

        $list->seek(0);
        $this->assertEquals(1, $list->indexOf($valueB));


        $this->assertEquals(-1, $list->indexOf($valueThatDoesNotExist=PHP_INT_MAX));

    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Spl\LinkedList::indexOf
     */
    function testIndexOfCallback() {
        $list = new LinkedListMock();
        $callback = $this->getMock(
            'Spl\\CallableMock',
            array('__invoke')
        );

        $callback->expects($this->exactly(9))
            ->method('__invoke')
            ->will($this->returnCallback(function($a, $b) {
                return $a[0] == $b[0];
            }));

        $valueA = array(0);

        $this->assertEquals(-1, $list->indexOf($valueA, $callback));

        $list[] = $valueA;
        $this->assertEquals(0, $list->indexOf($valueA, $callback));

        $valueB = array(1);
        $this->assertEquals(-1, $list->indexOf($valueB, $callback));

        $list[] = $valueB;

        // reset the internal pointer so it actually searches the whole list.

        $list->seek(0);
        $this->assertEquals(1, $list->indexOf($valueB, $callback));


        $this->assertEquals(
            -1,
            $list->indexOf(
                $valueThatDoesNotExist = array(PHP_INT_MAX),
                $callback
            )
        );
    }

    /**
     * @depends testIndexOf
     * @covers \Spl\LinkedList::contains
     */
    function testContains() {
        $list = new LinkedList();

        $this->assertFalse($list->contains(0));

        $list[] = 1;

        $this->assertTrue($list->contains(1));
    }

    /**
     * @depends testIndexOf
     * @covers \Spl\LinkedList::contains
     */
    function testContainsCallback() {
        $list = new LinkedList();

        $abs = function($a, $b) {
            return abs($a) == abs($b);
        };

        $this->assertFalse($list->contains(0), $abs);

        $list[] = 1;

        $this->assertTrue($list->contains(1, $abs));
        $this->assertTrue($list->contains(-1, $abs));

        $list[] = 2;
        $list->seek(0);
        $this->assertTrue($list->contains(2, $abs));
        $this->assertTrue($list->contains(-2, $abs));
    }

    /**
     * @covers \Spl\LinkedList::seek
     * @covers \Spl\LinkedList::__seek
     */
    function testSeek() {
        $list = new LinkedListMock();

        $list[] = 0;
        $this->assertEquals(0, $list->getCurrentOffset());

        $list[] = 1;
        $this->assertEquals(1, $list->getCurrentOffset());

        $list[] = 2;
        $this->assertEquals(2, $list->getCurrentOffset());

        $list->seek(1);
        $this->assertEquals(1, $list->getCurrentOffset());

        $list->seek(0);
        $list->seek(1);
        $this->assertEquals(1, $list->getCurrentOffset());

    }

    /**
     * @covers \Spl\LinkedList::seek
     * @expectedException \Spl\IndexException
     */
    function testSeekIndexException() {
        $list = new LinkedListMock();
        $list->seek(0);
    }

    /**
     * @covers \Spl\LinkedList::seek
     * @expectedException \Spl\TypeException
     */
    function testSeekTypeException() {
        $list = new LinkedListMock();
        $list->seek(array());
    }

    /**
     * @covers \Spl\LinkedList::popFront
     * @covers \Spl\LinkedList::removeNode
     */
    function testPopFront() {
        $list = new LinkedListMock();
        $list[] = 0;

        $popped = $list->popFront();

        $this->assertEquals(0, $popped);
        $this->assertCount(0, $list);
        $this->assertTrue($list->isEmpty());

        $list[] = 1;
        $list[] = 2;

        $popped = $list->popFront();

        $this->assertEquals(1, $popped);
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());
    }

    /**
     * @covers \Spl\LinkedList::popFront
     * @expectedException \Spl\EmptyException
     */
    function testPopFrontEmpty() {
        $list = new LinkedListMock();
        $list->popFront();;
    }

    /**
     * @covers \Spl\LinkedList::popBack
     * @covers \Spl\LinkedList::removeNode
     */
    function testPopBack() {
        $list = new LinkedListMock();
        $list[] = 0;

        $popped = $list->popBack();

        $this->assertEquals(0, $popped);
        $this->assertCount(0, $list);
        $this->assertTrue($list->isEmpty());

        $list[] = 1;
        $list[] = 2;

        $popped = $list->popBack();

        $this->assertEquals(2, $popped);
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());
    }

    /**
     * @covers \Spl\LinkedList::popBack
     * @expectedException \Spl\EmptyException
     */
    function testPopBackEmpty() {
        $list = new LinkedListMock();
        $list->popBack();;
    }

    /**
     * @covers \Spl\LinkedList::peekFront
     */
    function testPeekFront() {
        $list = new LinkedListMock();
        $list[] = 0;

        $popped = $list->peekFront();

        $this->assertEquals(0, $popped);
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());

        $list[] = 1;
        $list[] = 2;

        $popped = $list->peekFront();

        $this->assertEquals(0, $popped);
        $this->assertCount(3, $list);
        $this->assertFalse($list->isEmpty());
    }

    /**
     * @covers \Spl\LinkedList::peekFront
     * @expectedException \Spl\EmptyException
     */
    function testPeekFrontEmpty() {
        $list = new LinkedListMock();
        $list->peekFront();
    }

    /**
     * @covers \Spl\LinkedList::peekBack
     * @covers \Spl\LinkedList::pushBack
     */
    function testPeekBack() {
        $list = new LinkedListMock();
        $list[] = 0;

        $popped = $list->peekBack();

        $this->assertEquals(0, $popped);
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());

        $list[] = 1;
        $list[] = 2;

        $popped = $list->peekBack();

        $this->assertEquals(2, $popped);
        $this->assertCount(3, $list);
        $this->assertFalse($list->isEmpty());
    }

    /**
     * @covers \Spl\LinkedList::peekBack
     * @expectedException \Spl\EmptyException
     */
    function testPeekBackEmpty() {
        $list = new LinkedListMock();
        $list->peekBack();
    }

    /**
     * @depends testPeekBack
     * @depends testPeekFront
     * @covers \Spl\LinkedList::pushFront
     */
    function testPushFront() {
        $list = new LinkedListMock();

        $list->pushFront(0);

        $this->assertEquals(0, $list->peekFront());
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());

        $list->pushFront(1);
        $list->pushFront(2);

        $this->assertEquals(2, $list->peekFront(0));
        $this->assertEquals(0, $list->peekBack(0));
        $this->assertCount(3, $list);
        $this->assertFalse($list->isEmpty());
    }

    /**
     * @covers \Spl\LinkedList::insertAfter
     */
    function testInsertAfter() {
        $list = new LinkedListMock();
        $list->pushBack(0);

        $list->insertAfter(0, 2);
        $this->assertEquals(0, $list->getCurrentOffset());
        $this->assertEquals(0, $list->offsetGet(0));
        $this->assertEquals(2, $list->offsetGet(1));

        $list->insertAfter(0, 1);
        $this->assertEquals(0, $list->getCurrentOffset());
        $this->assertEquals(0, $list->offsetGet(0));
        $this->assertEquals(1, $list->offsetGet(1));
        $this->assertEquals(2, $list->offsetGet(2));
    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Spl\LinkedList::insertBefore
     */
    function testInsertBefore() {
        $list = new LinkedListMock();
        $list->pushBack(2);

        $list->insertBefore(0, 0);
        $this->assertEquals(1, $list->getCurrentOffset());
        $this->assertEquals(0, $list->offsetGet(0));
        $this->assertEquals(2, $list->offsetGet(1));

        $list->insertBefore(1, 1);
        $this->assertEquals(2, $list->getCurrentOffset());
        $this->assertEquals(0, $list->offsetGet(0));
        $this->assertEquals(1, $list->offsetGet(1));
        $this->assertEquals(2, $list->offsetGet(2));
    }

    /**
     * @covers \Spl\LinkedList::insertAfter
     * @expectedException \Spl\EmptyException
     */
    function testInsertAfterEmpty() {
        $list = new LinkedList();
        $list->insertAfter(0, 0);
    }

    /**
     * @covers \Spl\LinkedList::insertBefore
     * @expectedException \Spl\EmptyException
     */
    function testInsertBeforeEmpty() {
        $list = new LinkedList();
        $list->insertBefore(0, 0);
    }

    /**
     * @covers \Spl\LinkedList::insertAfter
     * @expectedException \Spl\IndexException
     */
    function testInsertAfterNegativeIndex() {
        $list = new LinkedList();
        $list->pushBack(0);
        $list->insertAfter(-1, 0);
    }

    /**
     * @covers \Spl\LinkedList::insertAfter
     * @expectedException \Spl\IndexException
     */
    function testInsertAfterOverMaxIndex() {
        $list = new LinkedList();
        $list->pushBack(0);
        $list->insertAfter(1, 0);
    }

    /**
     * @covers \Spl\LinkedList::insertBefore
     * @expectedException \Spl\IndexException
     */
    function testInsertBeforeNegativeIndex() {
        $list = new LinkedList();
        $list->pushBack(0);
        $list->insertBefore(-1, 0);
    }

    /**
     * @covers \Spl\LinkedList::insertBefore
     * @expectedException \Spl\IndexException
     */
    function testInsertBeforeOverMaxIndex() {
        $list = new LinkedList();
        $list->pushBack(0);
        $list->insertBefore(1, 0);
    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Spl\LinkedList::__clone
     * @covers \Spl\LinkedList::copyRange
     */
    function testClone() {
        $list = new LinkedList();
        $size = 5;
        for ($i = 0; $i < $size; $i++) {
            $list->pushBack($i);
        }

        $copy = clone $list;
        for ($i = 0; $i < $size; $i++) {
            $list->offsetSet($i, $i + $size);

            //the copy should not change
            $copyValue = $copy->offsetGet($i);
            $this->assertEquals($i, $copyValue);
            $this->assertNotEquals($copyValue, $list->offsetGet($i));
        }
    }

    /**
     * @depends testClone
     * @covers \Spl\LinkedList::__clone
     * @covers \Spl\LinkedList::getIterator
     */
    function testGetIterator() {
        $list = new LinkedList();

        $iterator = $list->getIterator();

        $this->assertInstanceOf('Spl\\LinkedListIterator', $iterator);

        $size = 5;
        for ($i = 0; $i < $size; $i++) {
            $list->pushBack($i);
        }

        $this->assertInstanceOf('Spl\\LinkedListIterator', $iterator);

    }

    /**
     * @covers \Spl\LinkedList::slice
     */
    function testSliceNoCount() {
        $list = new LinkedList();
        $size = 10;
        for ($i = 0; $i < $size; $i++) {
            $list->pushBack($i);
        }

        $slice = $list->slice(0);
        $this->assertInstanceOf('\\Spl\\LinkedList', $slice);
        $this->assertCount(10, $slice);

        for ($i = $size - 1; $i >= 0; $i--) {
            $value = $slice->popBack($i);
            $this->assertEquals($i, $value);
        }

        $slice = $list->slice(5);
        $this->assertInstanceOf('\\Spl\\LinkedList', $slice);
        $this->assertCount(5, $slice);

        for ($i = 0; $i < 5; $i++) {
            $value = $slice->offsetGet($i);
            $this->assertEquals($i + 5, $value);
        }
    }

    /**
     * @covers \Spl\LinkedList::slice
     */
    function testSliceNormal() {
        $list = new LinkedList();
        $size = 10;
        for ($i = 0; $i < $size; $i++) {
            $list->pushBack($i);
        }

        $slice = $list->slice(5, 2);
        $this->assertInstanceOf('\\Spl\\LinkedList', $slice);
        $this->assertCount(2, $slice);

        for ($i = 0; $i < 2; $i++) {
            $value = $slice->offsetGet($i);
            $this->assertEquals($i + 5, $value);
        }

    }

    /**
     * @covers \Spl\LinkedList::slice
     */
    function testSliceCountOvershoots() {
        $list = new LinkedList();
        $size = 10;
        for ($i = 0; $i < $size; $i++) {
            $list->pushBack($i);
        }

        $slice = $list->slice(0, $size + 1);
        $this->assertInstanceOf('\\Spl\\LinkedList', $slice);
        $this->assertCount(10, $slice);

        for ($i = 0; $i < 10; $i++) {
            $value = $slice->offsetGet($i);
            $this->assertEquals($i, $value);
        }

    }

    /**
     * @covers \Spl\LinkedList::slice
     */
    function testSliceNegativeCountOvershoots() {
        $list = new LinkedList();
        $size = 10;
        for ($i = 0; $i < $size; $i++) {
            $list->pushBack($i);
        }

        $slice = $list->slice(0, (-1 * ($size + 1)));
        $this->assertInstanceOf('\\Spl\\LinkedList', $slice);
        $this->assertCount(0, $slice);

    }

    /**
     * @covers \Spl\LinkedList::slice
     */
    function testSliceNegativeStartNoCount() {
        $list = new LinkedList();
        $size = 10;
        for ($i = 0; $i < $size; $i++) {
            $list->pushBack($i);
        }

        $slice = $list->slice(-3);
        $this->assertInstanceOf('\\Spl\\LinkedList', $slice);
        $this->assertCount(3, $slice);

        for ($i = 0; $i < 3; $i++) {
            $value = $slice->offsetGet($i);
            $this->assertEquals($i + 7, $value);
        }
    }

    /**
     * @covers \Spl\LinkedList::slice
     * @expectedException \Spl\EmptyException
     */
    function testSliceEmpty() {
        $list = new LinkedList();
        $list->slice(0);
    }

    /**
     * @covers \Spl\LinkedList::slice
     * @expectedException \Spl\TypeException
     */
    function testSliceNonIntegerStart() {
        $list = new LinkedList();
        $list->pushBack(0);
        $list->slice(array());
    }

    /**
     * @covers \Spl\LinkedList::slice
     * @expectedException \Spl\TypeException
     */
    function testSliceNonIntegerCount() {
        $list = new LinkedList();
        $list->pushBack(0);
        $list->slice(0, array());
    }

    /**
     * @covers \Spl\LinkedList::slice
     * @expectedException \Spl\IndexException
     */
    function testSliceStartGreaterThanMax() {
        $list = new LinkedList();
        $list->pushBack(0);
        $list->slice(1);
    }

    /**
     * @covers \Spl\LinkedList::slice
     * @expectedException \Spl\IndexException
     */
    function testSliceStartLessThanNegativeMax() {
        $list = new LinkedList();
        $list->pushBack(0);
        $list->slice(-2);
    }

}

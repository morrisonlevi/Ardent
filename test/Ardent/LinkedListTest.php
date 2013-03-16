<?php

namespace Ardent;

class CallableMock {
    function __invoke($a, $b) {
        return $a[0] == $b[0];
    }
}

class LinkedListTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Ardent\LinkedList::count
     * @covers \Ardent\LinkedList::key
     * @covers \Ardent\LinkedList::offsetSet
     * @covers \Ardent\LinkedList::offsetGet
     * @covers \Ardent\LinkedList::push
     * @covers \Ardent\LinkedList::__seek
     */
    function testOffsetGetAndSet() {
        $list = new LinkedList();

        $list->offsetSet(NULL, 0);
        $this->assertEquals(0, $list->key());
        $this->assertCount(1, $list);
        $this->assertEquals(0, $list[0]);

        $list->offsetSet(NULL, 1);
        $this->assertEquals(1, $list->key());
        $this->assertCount(2, $list);
        $this->assertEquals(0, $list[0]);
        $this->assertEquals(1, $list[1]);

        $list->offsetSet(0, 2);
        $this->assertEquals(0, $list->key());
        $this->assertCount(2, $list);
        $this->assertEquals(2, $list[0]);
        $this->assertEquals(1, $list[1]);
        $this->assertEquals(1, $list->key());
    }

    /**
     * @covers \Ardent\LinkedList::offsetSet
     * @expectedException \Ardent\IndexException
     */
    function testOffsetSetIndexException() {
        $list = new LinkedList();
        $list->offsetSet(0, 0);
    }

    /**
     * @covers \Ardent\LinkedList::offsetGet
     * @expectedException \Ardent\IndexException
     */
    function testOffsetGetIndexException() {
        $list = new LinkedList();
        $list->offsetGet(0);
    }

    /**
     * @covers \Ardent\LinkedList::offsetUnset
     */
    function testOffsetUnsetNonExistent() {
        $list = new LinkedList();
        $list->offsetUnset(0);
    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Ardent\LinkedList::offsetUnset
     * @covers \Ardent\LinkedList::removeNode
     */
    function testOffsetUnsetOneItem() {
        $list = new LinkedList();
        $list->push(0);
        $list->offsetUnset(0);

        $this->assertCount(0, $list);
    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Ardent\LinkedList::offsetUnset
     * @covers \Ardent\LinkedList::removeNode
     * @covers \Ardent\LinkedList::seek
     */
    function testOffsetUnsetHead() {
        $list = new LinkedList();
        $list->push(0);
        $list->push(1);

        $list->offsetUnset(0);
        $this->assertEquals(0, $list->key());
        $this->assertEquals(1, $list->current());
        $this->assertCount(1, $list);
        $this->assertEquals(1, $list->offsetGet(0));
    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Ardent\LinkedList::offsetUnset
     * @covers \Ardent\LinkedList::removeNode
     * @covers \Ardent\LinkedList::seek
     */
    function testOffsetUnsetTail() {
        $list = new LinkedList();
        $list->push(1);
        $list->push(2);

        $list->offsetUnset(1);
        $this->assertEquals(0, $list->key());
        $this->assertEquals(1, $list->current());
        $this->assertCount(1, $list);
        $this->assertEquals(1, $list->offsetGet(0));
    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Ardent\LinkedList::offsetUnset
     * @covers \Ardent\LinkedList::removeNode
     * @covers \Ardent\LinkedList::seek
     */
    function testOffsetUnsetMiddle() {
        $list = new LinkedList();
        $list->push(0);
        $list->push(2);
        $list->push(4);

        $list->offsetUnset(1);
        $this->assertEquals(1, $list->key());
        $this->assertEquals(4, $list->current());
        $this->assertCount(2, $list);
        $this->assertEquals(0, $list->offsetGet(0));
        $this->assertEquals(4, $list->offsetGet(1));
    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Ardent\LinkedList::offsetExists
     */
    function testOffsetExists() {
        $list = new LinkedList();

        $this->assertFalse($list->offsetExists(0));
        $this->assertFalse($list->offsetExists(-1));

        $list->push(0);
        $this->assertTrue($list->offsetExists(0));

    }

    /**
     * @depends testOffsetGetAndSet
     * @depends testOffsetUnsetOneItem
     * @covers \Ardent\LinkedList::isEmpty
     * @covers \Ardent\LinkedList::offsetUnset
     * @covers \Ardent\LinkedList::offsetSet
     * @covers \Ardent\LinkedList::push
     */
    function testIsEmpty() {
        $list = new LinkedList();

        $this->assertTrue($list->isEmpty());

        $list->push(0);
        $this->assertFalse($list->isEmpty());

        unset($list[0]);

        $this->assertTrue($list->isEmpty());

    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Ardent\LinkedList::indexOf
     * @covers \Ardent\LinkedList::__equals
     */
    function testIndexOf() {
        $list = new LinkedList();
        $valueA = 0;

        $this->assertEquals(-1, $list->indexOf($valueA));

        $list->push($valueA);
        $this->assertEquals(0, $list->indexOf($valueA));

        $valueB = 1;
        $this->assertEquals(-1, $list->indexOf($valueB));

        $list->push($valueB);

        // reset the internal pointer so it actually searches the whole list.

        $list->seek(0);
        $this->assertEquals(1, $list->indexOf($valueB));


        $this->assertEquals(-1, $list->indexOf($valueThatDoesNotExist=PHP_INT_MAX));

    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Ardent\LinkedList::indexOf
     */
    function testIndexOfCallback() {
        $list = new LinkedList();
        $callback = $this->getMock(
            'Ardent\\CallableMock',
            array('__invoke')
        );

        $callback->expects($this->exactly(9))
            ->method('__invoke')
            ->will($this->returnCallback(function($a, $b) {
                return $a[0] == $b[0];
            }));

        /**
         * @var callable $callback
         */

        $valueA = array(0);

        $this->assertEquals(-1, $list->indexOf($valueA, $callback));

        $list->push($valueA);
        $this->assertEquals(0, $list->indexOf($valueA, $callback));

        $valueB = array(1);
        $this->assertEquals(-1, $list->indexOf($valueB, $callback));

        $list->push($valueB);

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
     * @depends testOffsetUnsetOneItem
     */
    function testContains() {
        $list = new LinkedList();
        $this->assertFalse($list->containsItem(0));

        $list->push(1);
        $this->assertTrue($list->containsItem(1));
        $this->assertFalse($list->containsItem(0));

        $list->offsetUnset(0);
        $this->assertFalse($list->containsItem(1));

        $list->push(0);
        $list->push(2);
        $list->push(4);

        for ($i = 0; $i < 2; $i++) {
            $this->assertTrue($list->containsItem($i * 2));
        }
    }

    /**
     * @depends testOffsetGetAndSet
     */
    function testContainsCallback() {
        $list = new LinkedList();

        $abs = function($a, $b) {
            return abs($a) == abs($b);
        };

        $this->assertFalse($list->containsItem(0), $abs);

        $list->push(1);

        $this->assertTrue($list->containsItem(1, $abs));
        $this->assertTrue($list->containsItem(-1, $abs));

        $list->push(2);
        $list->seek(0);
        $this->assertTrue($list->containsItem(2, $abs));
        $this->assertTrue($list->containsItem(-2, $abs));
    }

    /**
     * @covers \Ardent\LinkedList::seek
     * @covers \Ardent\LinkedList::__seek
     */
    function testSeek() {
        $list = new LinkedList();

        $list->push(1);
        $this->assertEquals(0, $list->key());
        $this->assertEquals(1, $list->current());

        $list->push(2);
        $this->assertEquals(1, $list->key());
        $this->assertEquals(2, $list->current());

        $list->push(3);
        $this->assertEquals(2, $list->key());
        $this->assertEquals(3, $list->current());

        $list->seek(1);
        $this->assertEquals(1, $list->key());
        $this->assertEquals(2, $list->current());

        $list->seek(0);
        $this->assertEquals(0, $list->key());
        $this->assertEquals(1, $list->current());

        $list->seek(1);
        $this->assertEquals(1, $list->key());
        $this->assertEquals(2, $list->current());

    }

    /**
     * @covers \Ardent\LinkedList::seek
     * @expectedException \Ardent\IndexException
     */
    function testSeekIndexException() {
        $list = new LinkedList();
        $list->seek(0);
    }

    /**
     * @covers \Ardent\LinkedList::seek
     * @expectedException \Ardent\TypeException
     */
    function testSeekTypeException() {
        $list = new LinkedList();
        $list->seek(array());
    }

    /**
     * @covers \Ardent\LinkedList::shift
     * @covers \Ardent\LinkedList::removeNode
     */
    function testshift() {
        $list = new LinkedList();
        $list->push(0);

        $popped = $list->shift();

        $this->assertEquals(0, $popped);
        $this->assertCount(0, $list);
        $this->assertTrue($list->isEmpty());

        $list->push(1);
        $list->push(2);

        $popped = $list->shift();

        $this->assertEquals(1, $popped);
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());
    }

    /**
     * @covers \Ardent\LinkedList::shift
     * @expectedException \Ardent\EmptyException
     */
    function testshiftEmpty() {
        $list = new LinkedList();
        $list->shift();
    }

    /**
     * @covers \Ardent\LinkedList::pop
     * @covers \Ardent\LinkedList::removeNode
     */
    function testpop() {
        $list = new LinkedList();
        $list->push(0);

        $popped = $list->pop();

        $this->assertEquals(0, $popped);
        $this->assertCount(0, $list);
        $this->assertTrue($list->isEmpty());

        $list->push(1);
        $list->push(2);

        $popped = $list->pop();

        $this->assertEquals(2, $popped);
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());
    }

    /**
     * @covers \Ardent\LinkedList::pop
     * @expectedException \Ardent\EmptyException
     */
    function testpopEmpty() {
        $list = new LinkedList();
        $list->pop();;
    }

    /**
     * @covers \Ardent\LinkedList::peekFront
     */
    function testPeekFront() {
        $list = new LinkedList();
        $list->push(0);

        $popped = $list->peekFront();

        $this->assertEquals(0, $popped);
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());

        $list->push(1);
        $list->push(2);

        $popped = $list->peekFront();

        $this->assertEquals(0, $popped);
        $this->assertCount(3, $list);
        $this->assertFalse($list->isEmpty());
    }

    /**
     * @covers \Ardent\LinkedList::peekFront
     * @expectedException \Ardent\EmptyException
     */
    function testPeekFrontEmpty() {
        $list = new LinkedList();
        $list->peekFront();
    }

    /**
     * @covers \Ardent\LinkedList::peekBack
     * @covers \Ardent\LinkedList::push
     */
    function testPeekBack() {
        $list = new LinkedList();
        $list->push(0);

        $popped = $list->peekBack();

        $this->assertEquals(0, $popped);
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());

        $list->push(1);
        $list->push(2);

        $popped = $list->peekBack();

        $this->assertEquals(2, $popped);
        $this->assertCount(3, $list);
        $this->assertFalse($list->isEmpty());
    }

    /**
     * @covers \Ardent\LinkedList::peekBack
     * @expectedException \Ardent\EmptyException
     */
    function testPeekBackEmpty() {
        $list = new LinkedList();
        $list->peekBack();
    }

    /**
     * @depends testPeekBack
     * @depends testPeekFront
     * @covers \Ardent\LinkedList::unshift
     */
    function testunshift() {
        $list = new LinkedList();

        $list->unshift(0);

        $this->assertEquals(0, $list->peekFront());
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());

        $list->unshift(1);
        $list->unshift(2);

        $this->assertEquals(2, $list->peekFront(0));
        $this->assertEquals(0, $list->peekBack(0));
        $this->assertCount(3, $list);
        $this->assertFalse($list->isEmpty());
    }

    /**
     * @covers \Ardent\LinkedList::insertAfter
     */
    function testInsertAfter() {
        $list = new LinkedList();
        $list->push(0);

        $list->insertAfter(0, 2);
        $this->assertEquals(0, $list->key());
        $this->assertEquals(0, $list->offsetGet(0));
        $this->assertEquals(2, $list->offsetGet(1));

        $list->insertAfter(0, 1);
        $this->assertEquals(0, $list->key());
        $this->assertEquals(0, $list->offsetGet(0));
        $this->assertEquals(1, $list->offsetGet(1));
        $this->assertEquals(2, $list->offsetGet(2));
    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Ardent\LinkedList::insertBefore
     */
    function testInsertBefore() {
        $list = new LinkedList();
        $list->push(2);

        $list->insertBefore(0, 0);
        $this->assertEquals(1, $list->key());
        $this->assertEquals(0, $list->offsetGet(0));
        $this->assertEquals(2, $list->offsetGet(1));

        $list->insertBefore(1, 1);
        $this->assertEquals(2, $list->key());
        $this->assertEquals(0, $list->offsetGet(0));
        $this->assertEquals(1, $list->offsetGet(1));
        $this->assertEquals(2, $list->offsetGet(2));
    }

    /**
     * @covers \Ardent\LinkedList::insertAfter
     * @expectedException \Ardent\EmptyException
     */
    function testInsertAfterEmpty() {
        $list = new LinkedList();
        $list->insertAfter(0, 0);
    }

    /**
     * @covers \Ardent\LinkedList::insertBefore
     * @expectedException \Ardent\EmptyException
     */
    function testInsertBeforeEmpty() {
        $list = new LinkedList();
        $list->insertBefore(0, 0);
    }

    /**
     * @covers \Ardent\LinkedList::insertAfter
     * @expectedException \Ardent\IndexException
     */
    function testInsertAfterNegativeIndex() {
        $list = new LinkedList();
        $list->push(0);
        $list->insertAfter(-1, 0);
    }

    /**
     * @covers \Ardent\LinkedList::insertAfter
     * @expectedException \Ardent\IndexException
     */
    function testInsertAfterOverMaxIndex() {
        $list = new LinkedList();
        $list->push(0);
        $list->insertAfter(1, 0);
    }

    /**
     * @covers \Ardent\LinkedList::insertBefore
     * @expectedException \Ardent\IndexException
     */
    function testInsertBeforeNegativeIndex() {
        $list = new LinkedList();
        $list->push(0);
        $list->insertBefore(-1, 0);
    }

    /**
     * @covers \Ardent\LinkedList::insertBefore
     * @expectedException \Ardent\IndexException
     */
    function testInsertBeforeOverMaxIndex() {
        $list = new LinkedList();
        $list->push(0);
        $list->insertBefore(1, 0);
    }

    /**
     * @depends testOffsetGetAndSet
     * @covers \Ardent\LinkedList::__clone
     * @covers \Ardent\LinkedList::copyRange
     */
    function testClone() {
        $list = new LinkedList();
        $size = 5;
        for ($i = 0; $i < $size; $i++) {
            $list->push($i);
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
     * @covers \Ardent\LinkedList::__clone
     * @covers \Ardent\LinkedList::getIterator
     * @covers \Ardent\LinkedListIterator::__construct
     */
    function testGetIterator() {
        $list = new LinkedList();

        $iterator = $list->getIterator();

        $this->assertInstanceOf('Ardent\\LinkedListIterator', $iterator);

        $size = 5;
        for ($i = 0; $i < $size; $i++) {
            $list->push($i);
        }

        $this->assertInstanceOf('Ardent\\LinkedListIterator', $iterator);

    }

    /**
     * @covers \Ardent\LinkedList::getIterator
     * @covers \Ardent\LinkedListIterator::__construct
     * @covers \Ardent\LinkedListIterator::rewind
     * @covers \Ardent\LinkedListIterator::valid
     * @covers \Ardent\LinkedListIterator::key
     * @covers \Ardent\LinkedListIterator::current
     * @covers \Ardent\LinkedListIterator::next
     * @covers \Ardent\LinkedListIterator::prev
     * @covers \Ardent\LinkedList::rewind
     * @covers \Ardent\LinkedList::valid
     * @covers \Ardent\LinkedList::key
     * @covers \Ardent\LinkedList::current
     * @covers \Ardent\LinkedList::next
     * @covers \Ardent\LinkedList::prev
     */
    function testIteratorForeach() {
        $list = new LinkedList();
        $list->push(0);
        $list->push(1);
        $list->push(2);
        $list->push(3);

        $expectedKey = 0;
        $expectedValue = 0;

        $iterator = $list->getIterator();
        foreach ($iterator as $key => $value) {
            $this->assertEquals($expectedKey++, $key);
            $this->assertEquals($expectedValue++, $value);
        }

        $iterator->rewind();
        $iterator->next();
        $iterator->prev();
        $this->assertEquals(0, $iterator->key());
        $this->assertEquals(0, $iterator->current());

        $iterator->next();

        $iterator->next();
        $iterator->prev();
        $this->assertEquals(1, $iterator->key());
        $this->assertEquals(1, $iterator->current());
    }

    function testIteratorEmpty() {
        $list = new LinkedList();
        $iterator = $list;

        $iterator->rewind();
        $this->assertFalse($iterator->valid());
        $this->assertEquals(NULL, $iterator->key());
        $this->assertEquals(NULL, $iterator->current());

        $iterator->next();
        $this->assertEquals(NULL, $iterator->key());
        $this->assertEquals(NULL, $iterator->current());

        $iterator->prev();
        $this->assertEquals(NULL, $iterator->key());
        $this->assertEquals(NULL, $iterator->current());
    }

    /**
     * @covers \Ardent\LinkedListIterator::key
     * @covers \Ardent\LinkedListIterator::current
     * @covers \Ardent\LinkedListIterator::seek
     * @covers \Ardent\LinkedListIterator::end
     * @covers \Ardent\LinkedList::key
     * @covers \Ardent\LinkedList::current
     * @covers \Ardent\LinkedList::seek
     * @covers \Ardent\LinkedList::end
     */
    function testIteratorSeek() {
        $list = new LinkedList();
        $list->push(1);
        $list->push(2);
        $list->push(3);
        $list->push(4);

        $iterator = $list->getIterator();

        $iterator->seek(0);
        $this->assertEquals(0, $iterator->key());
        $this->assertEquals(1, $iterator->current());

        $iterator->seek(2);
        $this->assertEquals(2, $iterator->key());
        $this->assertEquals(3, $iterator->current());

        $iterator->seek(1);
        $this->assertEquals(1, $iterator->key());
        $this->assertEquals(2, $iterator->current());

        $iterator->seek(3);
        $this->assertEquals(3, $iterator->key());
        $this->assertEquals(4, $iterator->current());

        $iterator->rewind();
        $iterator->end();
        $this->assertEquals(3, $iterator->key());
        $this->assertEquals(4, $iterator->current());
    }

    /**
     * @covers \Ardent\LinkedList::count
     * @covers \Ardent\LinkedListIterator::count
     */
    function testCount() {
        $list = new LinkedList();
        $iterator = $list->getIterator();
        $this->assertCount(0, $list);
        $this->assertCount(0, $iterator);


        $list->push(0);
        $iterator = $list->getIterator();
        $this->assertCount(1, $list);
        $this->assertCount(1, $iterator);
    }

    /**
     * @covers \Ardent\LinkedList::slice
     */
    function testSliceNoCount() {
        $list = new LinkedList();
        $size = 10;
        for ($i = 0; $i < $size; $i++) {
            $list->push($i);
        }

        $slice = $list->slice(0);
        $this->assertInstanceOf('\\Ardent\\LinkedList', $slice);
        $this->assertCount(10, $slice);

        for ($i = $size - 1; $i >= 0; $i--) {
            $value = $slice->pop($i);
            $this->assertEquals($i, $value);
        }

        $slice = $list->slice(5);
        $this->assertInstanceOf('\\Ardent\\LinkedList', $slice);
        $this->assertCount(5, $slice);

        for ($i = 0; $i < 5; $i++) {
            $value = $slice->offsetGet($i);
            $this->assertEquals($i + 5, $value);
        }
    }

    /**
     * @covers \Ardent\LinkedList::slice
     */
    function testSliceNormal() {
        $list = new LinkedList();
        $size = 10;
        for ($i = 0; $i < $size; $i++) {
            $list->push($i);
        }

        $slice = $list->slice(5, 2);
        $this->assertInstanceOf('\\Ardent\\LinkedList', $slice);
        $this->assertCount(2, $slice);

        for ($i = 0; $i < 2; $i++) {
            $value = $slice->offsetGet($i);
            $this->assertEquals($i + 5, $value);
        }

    }

    /**
     * @covers \Ardent\LinkedList::slice
     */
    function testSliceCountOvershoots() {
        $list = new LinkedList();
        $size = 10;
        for ($i = 0; $i < $size; $i++) {
            $list->push($i);
        }

        $slice = $list->slice(0, $size + 1);
        $this->assertInstanceOf('\\Ardent\\LinkedList', $slice);
        $this->assertCount(10, $slice);

        for ($i = 0; $i < 10; $i++) {
            $value = $slice->offsetGet($i);
            $this->assertEquals($i, $value);
        }

    }

    /**
     * @covers \Ardent\LinkedList::slice
     */
    function testSliceNegativeCountOvershoots() {
        $list = new LinkedList();
        $size = 10;
        for ($i = 0; $i < $size; $i++) {
            $list->push($i);
        }

        $slice = $list->slice(0, (-1 * ($size + 1)));
        $this->assertInstanceOf('\\Ardent\\LinkedList', $slice);
        $this->assertCount(0, $slice);

    }

    /**
     * @covers \Ardent\LinkedList::slice
     */
    function testSliceNegativeStartNoCount() {
        $list = new LinkedList();
        $size = 10;
        for ($i = 0; $i < $size; $i++) {
            $list->push($i);
        }

        $slice = $list->slice(-3);
        $this->assertInstanceOf('\\Ardent\\LinkedList', $slice);
        $this->assertCount(3, $slice);

        for ($i = 0; $i < 3; $i++) {
            $value = $slice->offsetGet($i);
            $this->assertEquals($i + 7, $value);
        }
    }

    /**
     * @covers \Ardent\LinkedList::slice
     * @expectedException \Ardent\EmptyException
     */
    function testSliceEmpty() {
        $list = new LinkedList();
        $list->slice(0);
    }

    /**
     * @covers \Ardent\LinkedList::slice
     * @expectedException \Ardent\TypeException
     */
    function testSliceNonIntegerStart() {
        $list = new LinkedList();
        $list->push(0);
        $list->slice(array());
    }

    /**
     * @covers \Ardent\LinkedList::slice
     * @expectedException \Ardent\TypeException
     */
    function testSliceNonIntegerCount() {
        $list = new LinkedList();
        $list->push(0);
        $list->slice(0, array());
    }

    /**
     * @covers \Ardent\LinkedList::slice
     * @expectedException \Ardent\IndexException
     */
    function testSliceStartGreaterThanMax() {
        $list = new LinkedList();
        $list->push(0);
        $list->slice(1);
    }

    /**
     * @covers \Ardent\LinkedList::slice
     * @expectedException \Ardent\IndexException
     */
    function testSliceStartLessThanNegativeMax() {
        $list = new LinkedList();
        $list->push(0);
        $list->slice(-2);
    }

}

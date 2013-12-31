<?php

namespace Collections;

class CallableMock {
    function __invoke($a, $b) {
        return $a[0] == $b[0];
    }
}

class LinkedListTest extends \PHPUnit_Framework_TestCase {

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
     * @expectedException \Collections\IndexException
     */
    function testOffsetSetIndexException() {
        $list = new LinkedList();
        $list->offsetSet(0, 0);
    }

    /**
     * @expectedException \Collections\IndexException
     */
    function testOffsetGetIndexException() {
        $list = new LinkedList();
        $list->offsetGet(0);
    }

    function testOffsetUnsetNonExistent() {
        $list = new LinkedList();
        $list->offsetUnset(0);
    }

    /**
     * @depends testOffsetGetAndSet
     */
    function testOffsetUnsetOneItem() {
        $list = new LinkedList();
        $list->push(0);
        $list->offsetUnset(0);

        $this->assertCount(0, $list);
    }

    /**
     * @depends testOffsetGetAndSet
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
     */
    function testIndexOfCallback() {
        $list = new LinkedList();
        $callback = $this->getMock(
            'Collections\\CallableMock',
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
        $this->assertFalse($list->contains(0));

        $list->push(1);
        $this->assertTrue($list->contains(1));
        $this->assertFalse($list->contains(0));

        $list->offsetUnset(0);
        $this->assertFalse($list->contains(1));

        $list->push(0);
        $list->push(2);
        $list->push(4);

        for ($i = 0; $i < 2; $i++) {
            $this->assertTrue($list->contains($i * 2));
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

        $this->assertFalse($list->contains(0), $abs);

        $list->push(1);

        $this->assertTrue($list->contains(1, $abs));
        $this->assertTrue($list->contains(-1, $abs));

        $list->push(2);
        $list->seek(0);
        $this->assertTrue($list->contains(2, $abs));
        $this->assertTrue($list->contains(-2, $abs));
    }

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
     * @expectedException \Collections\IndexException
     */
    function testSeekIndexException() {
        $list = new LinkedList();
        $list->seek(0);
    }

    /**
     * @expectedException \Collections\TypeException
     */
    function testSeekTypeException() {
        $list = new LinkedList();
        $list->seek(array());
    }

    function testShift() {
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
     * @expectedException \Collections\EmptyException
     */
    function testshiftEmpty() {
        $list = new LinkedList();
        $list->shift();
    }

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
     * @expectedException \Collections\EmptyException
     */
    function testpopEmpty() {
        $list = new LinkedList();
        $list->pop();;
    }

    function testFirst() {
        $list = new LinkedList();
        $list->push(0);

        $popped = $list->first();

        $this->assertEquals(0, $popped);
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());

        $list->push(1);
        $list->push(2);

        $popped = $list->first();

        $this->assertEquals(0, $popped);
        $this->assertCount(3, $list);
        $this->assertFalse($list->isEmpty());
    }

    /**
     * @expectedException \Collections\EmptyException
     */
    function testFirstEmpty() {
        $list = new LinkedList();
        $list->first();
    }

    function testLast() {
        $list = new LinkedList();
        $list->push(0);

        $popped = $list->last();

        $this->assertEquals(0, $popped);
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());

        $list->push(1);
        $list->push(2);

        $popped = $list->last();

        $this->assertEquals(2, $popped);
        $this->assertCount(3, $list);
        $this->assertFalse($list->isEmpty());
    }

    /**
     * @expectedException \Collections\EmptyException
     */
    function testLastEmpty() {
        $list = new LinkedList();
        $list->last();
    }

    /**
     * @depends testLast
     * @depends testFirst
     */
    function testUnshift() {
        $list = new LinkedList();

        $list->unshift(0);

        $this->assertEquals(0, $list->first());
        $this->assertCount(1, $list);
        $this->assertFalse($list->isEmpty());

        $list->unshift(1);
        $list->unshift(2);

        $this->assertEquals(2, $list->first(0));
        $this->assertEquals(0, $list->last(0));
        $this->assertCount(3, $list);
        $this->assertFalse($list->isEmpty());
    }

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
     * @expectedException \Collections\EmptyException
     */
    function testInsertAfterEmpty() {
        $list = new LinkedList();
        $list->insertAfter(0, 0);
    }

    /**
     * @expectedException \Collections\EmptyException
     */
    function testInsertBeforeEmpty() {
        $list = new LinkedList();
        $list->insertBefore(0, 0);
    }

    /**
     * @expectedException \Collections\IndexException
     */
    function testInsertAfterNegativeIndex() {
        $list = new LinkedList();
        $list->push(0);
        $list->insertAfter(-1, 0);
    }

    /**
     * @expectedException \Collections\IndexException
     */
    function testInsertAfterOverMaxIndex() {
        $list = new LinkedList();
        $list->push(0);
        $list->insertAfter(1, 0);
    }

    /**
     * @expectedException \Collections\IndexException
     */
    function testInsertBeforeNegativeIndex() {
        $list = new LinkedList();
        $list->push(0);
        $list->insertBefore(-1, 0);
    }

    /**
     * @expectedException \Collections\IndexException
     */
    function testInsertBeforeOverMaxIndex() {
        $list = new LinkedList();
        $list->push(0);
        $list->insertBefore(1, 0);
    }

    /**
     * @expectedException \Collections\EmptyException
     */
    function testTailEmpty() {
        $list = new LinkedList();
        $list->tail();
    }

    /**
     * @depends testShift
     */
    function testTail() {
        $list = new LinkedList();
        $expected = [];
        for ($i = 0; $i < 3; $i++) {
            if ($i > 0) {
                $expected[] = $i;
            }
            $list->push($i);
        }

        $head = $list->tail();
        $this->assertCount(2, $head);
        for ($i = 0; $i < 2; $i++) {
            $actual = $head->shift();
            $this->assertEquals($expected[$i], $actual);
        }
    }

    /**
     * @depends testOffsetGetAndSet
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
     */
    function testGetIterator() {
        $list = new LinkedList();

        $iterator = $list->getIterator();

        $this->assertInstanceOf('Collections\\LinkedListIterator', $iterator);

        $size = 5;
        for ($i = 0; $i < $size; $i++) {
            $list->push($i);
        }

        $this->assertInstanceOf('Collections\\LinkedListIterator', $iterator);

    }

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

}

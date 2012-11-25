<?php

namespace Spl;

class LinkedListMock extends LinkedList {
    public function getCurrentOffset() {
        return parent::getCurrentOffset();
    }
}

class LinkedListTest extends \PHPUnit_Framework_TestCase {

    /**
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
     * @covers \Spl\LinkedList::offsetUnset
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

}

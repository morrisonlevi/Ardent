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
     */
    function testOffsetAppendEmpty() {
        $list = new LinkedList();
        $list[] = 0;

        $this->assertCount(1, $list);
        $this->assertEquals(0, $list[0]);
    }

}

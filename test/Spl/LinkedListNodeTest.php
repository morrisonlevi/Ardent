<?php

namespace Spl;

class LinkedListNodeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Spl\LinkedListNode::__construct
     */
    function test__construct() {
        $node = new LinkedListNode(0);

        $this->assertEquals(0, $node->value);
    }

    /**
     * @depends test__construct
     * @covers \Spl\LinkedListNode::__clone
     */
    function test__clone() {
        $node = new LinkedListNode(0);
        $node->prev = new LinkedListNode(-1);
        $node->next = new LinkedListNode(1);

        $clone = clone $node;

        $this->assertNotSame($node->prev, $clone->prev);
        $this->assertNotSame($node->next, $clone->next);

        $this->assertEquals($node->value, $clone->value);
        $this->assertEquals($node->prev, $clone->prev);
        $this->assertEquals($node->next, $clone->next);
    }

}

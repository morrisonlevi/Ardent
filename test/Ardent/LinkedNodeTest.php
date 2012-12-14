<?php

namespace Ardent;

class LinkedNodeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Ardent\LinkedNode::__construct
     */
    function test__construct() {
        $node = new LinkedNode(0);

        $this->assertEquals(0, $node->value);
    }

    /**
     * @depends test__construct
     * @covers \Ardent\LinkedNode::__clone
     */
    function test__clone() {
        $node = new LinkedNode(0);
        $node->prev = new LinkedNode(-1);
        $node->next = new LinkedNode(1);

        $clone = clone $node;

        $this->assertNotSame($node->prev, $clone->prev);
        $this->assertNotSame($node->next, $clone->next);

        $this->assertEquals($node->value, $clone->value);
        $this->assertEquals($node->prev, $clone->prev);
        $this->assertEquals($node->next, $clone->next);
    }

}

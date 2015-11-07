<?php

namespace Ardent\Collection;


abstract class BinaryTreeIteratorTest extends TestCase {


    /**
     * @return BinaryTreeIterator
     */
    abstract function instance();


    function test_isEmpty_empty_returnsTrue() {
        $iterator = $this->instance();
        $this->assertTrue($iterator->isEmpty());
    }


    function test_count_empty_returnsZero() {
        $iterator = $this->instance();
        $this->assertCount(0, $iterator);
    }

} 
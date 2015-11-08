<?php

namespace Ardent\Collection;

use Iterator;

class SortedSetIterator implements SetIterator {

    use OuterIteratorTrait;

    private $inner;
    private $size = 0;


    /**
     * @return Iterator
     */
   private function getInnerIterator() {
        return $this->inner;
    }


    function __construct(BinaryTreeIterator $iterator, $size) {
        $this->inner = $iterator;
        $this->size = $size;
        $this->rewind();
    }


    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->size;
    }


    /**
     * @return bool
     */
    function isEmpty() {
        return $this->count() === 0;
    }


}

<?php

namespace Collections;

class SortedSetIterator extends IteratorCollectionAdapter implements SetIterator {

    private $size = 0;


    function __construct(BinaryTreeIterator $iterator, $size) {
        parent::__construct(new ValueIterator($iterator));
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

}

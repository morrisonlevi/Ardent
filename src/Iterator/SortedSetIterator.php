<?php

namespace Collections;

class SortedSetIterator extends IteratorCollectionAdapter implements SetIterator {

    private $key = 0;

    function __construct(BinaryTreeIterator $iterator, $size) {
        parent::__construct($iterator);
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
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        parent::rewind();
        $this->key = 0;
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        parent::next();
        $this->key++;
    }

}

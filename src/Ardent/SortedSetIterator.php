<?php

namespace Ardent;

class SortedSetIterator implements CountableIterator {

    private $key = 0;

    function __construct(BinaryTreeIterator $iterator, $size) {
        $this->iterator = $iterator;
        $this->size = $size;
        $this->iterator->rewind();
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
        $this->iterator->rewind();
        $this->key = 0;
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->iterator->valid();
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        return $this->valid() ? $this->key : NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->valid() ? $this->iterator->current() : NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->iterator->next();
        $this->key++;
    }

}

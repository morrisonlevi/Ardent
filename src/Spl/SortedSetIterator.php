<?php

namespace Spl;

class SortedSetIterator implements CountableIterator {

    private $key = 0;

    public function __construct(BinaryTreeIterator $iterator, $size) {
        $this->iterator = $iterator;
        $this->size = $size;
        $this->iterator->rewind();
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count() {
        return $this->size;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    public function current() {
        return $this->iterator->current();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    public function next() {
        $this->iterator->next();
        $this->key++;
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    public function key() {
        return $this->size > 0
            ? $this->key
            : NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid() {
        return $this->iterator->valid();
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind() {
        $this->iterator->rewind();
        $this->key = 0;
    }

}

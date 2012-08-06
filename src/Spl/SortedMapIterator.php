<?php

namespace Spl;

use Iterator;

class SortedMapIterator implements Iterator {

    /**
     * @var BinaryTreeIterator
     */
    private $iterator;

    public function __construct(BinaryTreeIterator $iterator) {
        $this->iterator = $iterator;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @throws TypeException
     * @return mixed
     */
    public function current() {
        /**
         * @var Pair $pair
         */
        $pair = $this->iterator->current();

        if (!($pair instanceof Pair)) {
            throw new TypeException(
                __CLASS__ . ' only works with a BinarySearchTreeIterator that returns pair objects as values'
            );
        }

        return $pair->first();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    public function next() {
        $this->iterator->next();
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    public function key() {
        return NULL;
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
    }

}

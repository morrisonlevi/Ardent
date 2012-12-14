<?php

namespace Ardent;

class SortedMapIterator implements MapIterator {

    /**
     * @var BinaryTreeIterator
     */
    private $iterator;

    private $size;

    function __construct(BinaryTreeIterator $iterator, $size) {
        $this->iterator = $iterator;
        $this->size = $size;
        $this->rewind();
    }

    function count() {
        return $this->size;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->iterator->rewind();
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->iterator->valid();
    }

    /**
     * @link https://bugs.php.net/bug.php?id=45684
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     * @throws TypeException
     */
    function key() {
        if (!$this->iterator->valid()) {
            return NULL;
        }

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
     * @link http://php.net/manual/en/iterator.current.php
     * @throws TypeException
     * @return mixed
     */
    function current() {
        if (!$this->iterator->valid()) {
            return NULL;
        }
        /**
         * @var Pair $pair
         */
        $pair = $this->iterator->current();

        if (!($pair instanceof Pair)) {
            throw new TypeException(
                __CLASS__ . ' only works with a BinarySearchTreeIterator that returns pair objects as values'
            );
        }

        return $pair->second();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->iterator->next();
    }

}

<?php

namespace Spl;

class SortedMapIterator implements CountableIterator {

    /**
     * @var BinaryTreeIterator
     */
    private $iterator;

    private $size;

    public function __construct(BinaryTreeIterator $iterator, $size) {
        $this->iterator = $iterator;
        $this->size = $size;
    }

    public function count() {
        return $this->size;
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

        return $pair->second();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    public function next() {
        $this->iterator->next();
    }

    /**
     * @link https://bugs.php.net/bug.php?id=45684
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     * @throws TypeException
     */
    public function key() {
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

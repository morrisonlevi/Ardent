<?php

namespace Collections;

class SortedMapIterator extends IteratorCollectionAdapter implements MapIterator {

    private $size = 0;

    function __construct(BinaryTreeIterator $iterator, $size) {
        parent::__construct($iterator);
        $this->size = $size;
        $this->rewind();
    }

    function count() {
        return $this->size;
    }

    /**
     * @link https://bugs.php.net/bug.php?id=45684
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     * @throws TypeException
     */
    function key() {
        /**
         * @var Pair $pair
         */
        $pair = $this->inner->current();

        if (!($pair instanceof Pair)) {
            throw new TypeException(
                __CLASS__ . ' only works with a BinarySearchTreeIterator that returns pair objects as values'
            );
        }

        return $pair->first;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @throws TypeException
     * @return mixed
     */
    function current() {
        /**
         * @var Pair $pair
         */
        $pair = $this->inner->current();

        if (!($pair instanceof Pair)) {
            throw new TypeException(
                __CLASS__ . ' only works with a BinarySearchTreeIterator that returns pair objects as values'
            );
        }

        return $pair->second;
    }

}

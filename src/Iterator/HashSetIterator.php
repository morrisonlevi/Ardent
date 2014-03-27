<?php

namespace Collections;

class HashSetIterator extends IteratorCollectionAdapter implements SetIterator {

    private $key = 0;

    private $size = 0;

    function  __construct(array $set) {
        parent::__construct(new \ArrayIterator($set));
        $this->size = count($set);
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

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return string
     */
    function key() {
        return $this->key;
    }

}

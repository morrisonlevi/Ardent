<?php

namespace Collections;

class ArrayIterator extends IteratorCollectionAdapter implements CountableSeekableIterator {

    private $count;

    function __construct(array $array) {
        parent::__construct(new \ArrayIterator($array));
        $this->count = count($array);
    }

    /**
     * @param int $position
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @return void
     */
    function seek($position) {
        $this->getInnerIterator()->seek($position);
    }

    function count() {
        return $this->count;
    }

    /**
     * @return \ArrayIterator
     */
    function getInnerIterator() {
        return parent::getInnerIterator();
    }

}

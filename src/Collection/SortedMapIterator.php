<?php

namespace Ardent\Collection;


use Iterator;

class SortedMapIterator implements MapIterator {

    use OuterIteratorTrait;


    private $inner;
    private $size = 0;


    /**
     * @return Iterator
     */
    private function getInnerIterator() {
        return $this->inner;
    }


    function __construct(InOrderIterator $iterator, $size) {
        $this->inner = $iterator;
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
     */
    function key() {
        return $this->inner->current()->first;
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->inner->current()->second;
    }


    /**
     * @return bool
     */
    function isEmpty() {
        return $this->count() === 0;
    }


}

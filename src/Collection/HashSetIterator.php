<?php

namespace Ardent\Collection;



final class HashSetIterator implements SetIterator {

    use OuterIteratorTrait;

    private $inner;


    function  __construct(Array $set) {
        $this->inner = new \ArrayIterator(array_values($set));
    }


    /**
     * @return \Iterator
     */
    private function getInnerIterator() {
        return $this->inner;
    }


    /**
     * @return Int
     */
    function count() {
        return $this->inner->count();
    }


    /**
     * @return Bool
     */
    function isEmpty() {
        return $this->count() === 0;
    }


}

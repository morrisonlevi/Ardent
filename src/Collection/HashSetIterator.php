<?php

namespace Ardent\Collection;



final class HashSetIterator implements SetIterator {

    use OuterIteratorTrait;

    private $inner;


    function  __construct(array $set) {
        $this->inner = new \ArrayIterator(array_values($set));
    }


    /**
     * @return \Iterator
     */
    private function getInnerIterator() {
        return $this->inner;
    }


    /**
     * @return int
     */
    function count() {
        return $this->inner->count();
    }


    /**
     * @return bool
     */
    function isEmpty() {
        return $this->count() === 0;
    }


}

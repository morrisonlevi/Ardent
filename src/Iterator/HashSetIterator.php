<?php

namespace Collections;

class HashSetIterator extends IteratorCollectionAdapter implements SetIterator {

    private $size = 0;


    function  __construct(array $set) {
        parent::__construct(new ValueIterator(new \ArrayIterator($set)));
        $this->size = count($set);
        $this->rewind();
    }


    function count() {
        return $this->size;
    }

}

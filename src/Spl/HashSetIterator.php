<?php

namespace Spl;

use \ArrayIterator;
use \Spl\Interfaces\SetIterator;
use \IteratorAggregate;

class HashSetIterator implements IteratorAggregate, SetIterator {
    
    private $array;

    public function __construct(HashSet $set) {
        $this->array = $set->toArray();
    }

    public function getIterator() {
        return new ArrayIterator($this->array);
    }

    public function count() {
        return count($this->array);
    }
    
}

<?php

namespace Collections;


class ValueIterator extends IteratorCollectionAdapter {

    private $i = 0;

    function values() {
        return $this;
    }

    function rewind() {
        parent::rewind();
        $this->i = 0;
    }

    function key() {
        return $this->i;
    }

    function next() {
        parent::next();
        $this->i++;
    }
} 
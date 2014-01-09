<?php

namespace Collections;

class MappingIterator extends IteratorCollectionAdapter {

    /**
     * @var callable
     */
    private $mapper;

    function __construct(\Iterator $iterator, callable $map) {
        parent::__construct($iterator);
        $this->mapper = $map;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return call_user_func(
            $this->mapper,
            $this->inner->current(),
            $this->inner->key()
        );
    }

}
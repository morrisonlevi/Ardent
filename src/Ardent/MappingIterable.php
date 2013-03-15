<?php

namespace Ardent;

class MappingIterable extends IteratorIterable {

    /**
     * @var callable
     */
    private $mapper;

    function __construct(Iterable $iterator, callable $map) {
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
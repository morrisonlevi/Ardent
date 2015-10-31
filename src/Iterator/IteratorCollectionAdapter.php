<?php

namespace Collections;

class IteratorCollectionAdapter implements Enumerator, \OuterIterator {

    use IteratorCollection;

    /**
     * @var \Iterator
     */
    protected $inner;


    function __construct($iterable) {

        if (is_array($iterable)) {
            $iterator = new ArrayIterator($iterable);
        } else if ($iterable instanceof \Iterator) {
            $iterator = $iterable;
        } else {
            assert($iterable instanceof \Traversable);
            $iterator = new \IteratorIterator($iterable);
        }

        $this->inner = $iterator;
    }


    function getInnerIterator() {
        return $this->inner;
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->inner->current();
    }


    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->inner->next();
    }


    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        return $this->inner->key();
    }


    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->inner->valid();
    }


    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->inner->rewind();
    }

}
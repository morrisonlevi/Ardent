<?php

namespace Ardent\Iterator;

use Ardent\Collection;

class KeyIterator implements CountableIterator, Collection {

    use IteratorCollection;

    private $i = 0;

    /**
     * @var \Iterator
     */
    private $inner;

    function __construct(\Iterator $iterator) {
        $this->inner = $iterator;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->i = 0;
        $this->inner->rewind();
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->inner->valid();
    }

    /**
     * Calling `key` when iterator is not in a valid state is undefined.
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        return $this->i;
    }

    /**
     * Calling `current` when iterator is not in a valid state is undefined.
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->inner->key();
    }

    /**
     * Calling `next` when iterator is not in a valid state is harmless.
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->inner->next();
        $this->i++;
    }

}
<?php

namespace Ardent\Iterator;

use Ardent\Collection;
use Ardent\CollectionIterator;
use Ardent\Iterator\CountableSeekableIterator;

class ArrayIterator implements CountableSeekableIterator, Collection {

    use CollectionIterator;

    /**
     * @var \ArrayIterator
     */
    private $inner;

    function __construct(array $array) {
        $this->inner = new \ArrayIterator($array);
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

    /**
     * @param int $position
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @return void
     */
    function seek($position) {
        $this->inner->seek($position);
    }

}

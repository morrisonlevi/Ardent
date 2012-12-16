<?php

namespace Ardent;

class HashSetIterator implements SetIterator {

    /**
     * @var array
     */
    private $objects;

    /**
     * @var int
     */
    private $key = 0;

    function  __construct(array $set) {
        $this->objects = $set;
        $this->rewind();
    }

    function count() {
        return count($this->objects);
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        reset($this->objects);
        $this->key = 0;
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return key($this->objects) !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return string
     */
    function key() {
        if (!$this->valid()) {
            return NULL;
        }
        return $this->key;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        if (!$this->valid()) {
            return NULL;
        }
        return current($this->objects);
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        next($this->objects);
        $this->key++;
    }

}

<?php

namespace Spl;

class HashSetIterator implements SetIterator {

    /**
     * @var array
     */
    private $objects;

    function  __construct(array $set) {
        $this->objects = $set;
    }

    function count() {
        return count($this->objects);
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    public function current() {
        return current($this->objects);
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    public function next() {
        next($this->objects);
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return string
     */
    public function key() {
        return key($this->objects);
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid() {
        return key($this->objects) !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind() {
        reset($this->objects);
    }

}

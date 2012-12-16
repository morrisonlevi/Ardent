<?php

namespace Ardent;

class HashMapIterator implements MapIterator {

    protected $storage = array();

    function __construct(array $storage) {
        $this->storage = $storage;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        reset($this->storage);
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return key($this->storage) !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        if (!$this->valid()) {
            return NULL;
        }
        /**
         * @var Pair $pair
         */
        $pair = current($this->storage);
        return $pair->first;
    }
    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        if (!$this->valid()) {
            return NULL;
        }
        /**
         * @var Pair $pair
         */
        $pair = current($this->storage);
        return $pair->second;
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        next($this->storage);
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return count($this->storage);
    }

}

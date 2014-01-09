<?php

namespace Collections;

class HashMapIterator extends IteratorCollectionAdapter implements MapIterator {

    private $size = 0;

    function __construct(array $storage) {
        parent::__construct(new \ArrayIterator($storage));
        $this->size = count($storage);
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        /**
         * @var Pair $pair
         */
        $pair = parent::current();
        return $pair->first;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        /**
         * @var Pair $pair
         */
        $pair = parent::current();
        return $pair->second;
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->size;
    }

}

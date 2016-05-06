<?php

namespace Ardent\Collection;


/**
 * Class HashMapIterator should only be created by HashMap
 */
class HashMapIterator implements MapIterator {

    use OuterIteratorTrait;


    private $inner;


    function __construct(Array $storage) {
        $this->inner = new \ArrayIterator($storage);
    }


    private function getInnerIterator() {
        return $this->inner;
    }


    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return Mixed
     */
    function key() {
        /**
         * @var Pair $pair
         */
        $pair = $this->inner->current();
        return $pair->first;
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return Mixed
     */
    function current() {
        /**
         * @var Pair $pair
         */
        $pair = $this->inner->current();
        return $pair->second;
    }


    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return Int
     */
    function count() {
        return $this->inner->count();
    }


    /**
     * @return Bool
     */
    function isEmpty() {
        return $this->count() === 0;
    }


}

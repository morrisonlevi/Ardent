<?php

namespace Ardent\Collection;


class HashMap implements Map {

    private $storage = [];

    /**
     * @var callable
     */
    private $hashFunction = null;


    /**
     * @param callable $hashingFunction
     */
    function __construct(callable $hashingFunction = null) {
        $this->hashFunction = $hashingFunction
            ?: __NAMESPACE__ . '\\hash';
    }


    /**
     * @return bool
     */
    function isEmpty() {
        return empty($this->storage);
    }


    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return \Ardent\Collection\HashMapIterator
     */
    function getIterator() {
        return new HashMapIterator($this->storage);
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset
     * @return bool
     */
    function offsetExists($offset) {
        return array_key_exists(call_user_func($this->hashFunction, $offset), $this->storage);
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset
     * @return mixed
     */
    function offsetGet($offset) {
        $hash = call_user_func($this->hashFunction, $offset);

        assert(array_key_exists($hash, $this->storage));

        /**
         * @var Pair $pair
         */
        $pair = $this->storage[$hash];
        return $pair->second;
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    function offsetSet($offset, $value) {
        $hash = call_user_func($this->hashFunction, $offset);
        $this->storage[$hash] = new Pair($offset, $value);
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset
     * @return void
     */
    function offsetUnset($offset) {
        $hash = call_user_func($this->hashFunction, $offset);
        if (array_key_exists($hash, $this->storage)) {
            unset($this->storage[$hash]);
        }
    }


    function areEqual($a, $b) {
        return $a === $b;
    }


    /**
     * @param $item
     * @param callable $comparator function($a, $b) returns bool
     *
     * @return bool
     */
    function contains($item, callable $comparator = null) {
        $compare = $comparator ?: [$this, 'areEqual'];

        $storage = $this->storage;
        for (reset($storage); key($storage) !== null; next($storage)) {
            /**
             * @var Pair $pair
             */
            $pair = current($storage);
            if (call_user_func($compare, $item, $pair->second)) {
                return true;
            }
        }
        return false;
    }


    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return count($this->storage);
    }


}

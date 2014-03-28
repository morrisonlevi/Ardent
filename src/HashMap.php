<?php

namespace Collections;

class HashMap implements Map {

    use IteratorCollection;

    private $storage = [];

    /**
     * @var callable
     */
    private $hashFunction = NULL;

    /**
     * @param callable $hashingFunction
     */
    function __construct(callable $hashingFunction = NULL) {
        $this->hashFunction = $hashingFunction ?: [$this, 'hash'];
    }

    function hash($item) {
        if (is_object($item)) {
            return spl_object_hash($item);
        } elseif (is_scalar($item)) {
            return "s_$item";
        } elseif (is_resource($item)) {
            return "r_$item";
        } elseif (is_array($item)) {
            return 'a_' . md5(serialize($item));
        }

        return '0';
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return empty($this->storage);
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return HashMapIterator
     */
    function getIterator() {
        return new HashMapIterator($this->storage);
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset
     * @return boolean
     */
    function offsetExists($offset) {
        return array_key_exists(call_user_func($this->hashFunction, $offset), $this->storage);
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset
     * @return mixed
     * @throws KeyException
     */
    function offsetGet($offset) {
        $hash = call_user_func($this->hashFunction, $offset);
        if (!array_key_exists($hash, $this->storage)) {
            throw new KeyException;
        }

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
     * @throws TypeException when $item is not the correct type.
     */
    function contains($item, callable $comparator = NULL) {
        $compare = $comparator ?: [$this, 'areEqual'];

        $storage = $this->storage;
        for (reset($storage); key($storage) !== NULL; next($storage)) {
            /**
             * @var Pair $pair
             */
            $pair = current($storage);
            if (call_user_func($compare, $item, $pair->second)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * @param $key
     *
     * @return mixed
     * @throws KeyException when the $key does not exist.
     * @throws TypeException when the $key is not the correct type.
     */
    function get($key) {
        $hash = call_user_func($this->hashFunction, $key);
        if (!array_key_exists($hash, $this->storage)) {
            throw new KeyException;
        }

        /**
         * @var Pair $pair
         */
        $pair = $this->storage[$hash];
        return $pair->second;
    }

    /**
     * Note that if the key is considered equal to an already existing key in
     * the map that its value will be replaced with the new one.
     *
     * @param $key
     * @param mixed $value
     *
     * @return void
     * @throws TypeException when the $key or $value is not the correct type.
     */
    function set($key, $value) {
        $this->offsetSet($key, $value);
    }

    /**
     * @param $key
     *
     * @return mixed
     * @throws TypeException when the $key is not the correct type.
     */
    function remove($key) {
        $this->offsetUnset($key);
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return count($this->storage);
    }

}

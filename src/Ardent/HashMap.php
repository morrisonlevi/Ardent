<?php

namespace Ardent;

class HashMap implements Map {

    private $storage = array();

    /**
     * @var callable
     */
    private $hashFunction = NULL;

    /**
     * @param callable $hashingFunction
     */
    function __construct($hashingFunction = NULL) {
        $this->hashFunction = is_callable($hashingFunction)
            ? $hashingFunction
            : array($this, 'hash');
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
        return $this->fetchValue($hash);
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    function offsetSet($offset, $value) {
        $this->insert($offset, $value);
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset
     * @return void
     */
    function offsetUnset($offset) {
        $this->remove($offset);
    }

    function areEqual($a, $b) {
        return $a == $b;
    }

    /**
     * @param $item
     * @param callable $comparator function($a, $b) returns bool
     *
     * @return bool
     * @throws TypeException when $item is not the correct type.
     */
    function contains($item, $comparator = NULL) {
        $compare = is_callable($comparator)
            ? $comparator
            : array($this, 'areEqual');

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
        return $this->fetchValue($hash);
    }

    /**
     * @param $key
     * @param mixed $value
     *
     * @return void
     * @throws TypeException when the $key or $value is not the correct type.
     */
    function insert($key, $value) {
        $this->storage[call_user_func($this->hashFunction, $key)] = new Pair($key, $value);
    }

    /**
     * @param $key
     *
     * @return mixed
     * @throws TypeException when the $key is not the correct type.
     */
    function remove($key) {
        $hash = call_user_func($this->hashFunction, $key);
        if (array_key_exists($hash, $this->storage)) {
            unset($this->storage[$hash]);
        }
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return count($this->storage);
    }

    private function fetchValue($hash) {
        /**
         * @var Pair $pair
         */
        $pair = $this->storage[$hash];
        return $pair->second;
    }

}

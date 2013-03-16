<?php

namespace Ardent;

use Ardent\Exception\IndexException;
use Ardent\Exception\TypeException;
use Ardent\Iterator\VectorIterator;
use ArrayAccess;

class Vector implements ArrayAccess, \IteratorAggregate, Collection {

    use CollectionStructure;

    protected $array = [];

    /**
     * @param mixed,... $varargs
     * @throws TypeException
     */
    function __construct($varargs = NULL) {
        $this->array = func_get_args();
    }

    /**
     * @param \Traversable $traversable
     * @return void
     */
    function appendAll(\Traversable $traversable) {
        foreach ($traversable as $item) {
            $this->array[] = $item;
        }
        $this->cache = NULL;
    }

    /**
     * @return void
     */
    function clear() {
        $this->array = [];
        $this->cache = NULL;
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return count($this->array) === 0;
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param int $offset
     *
     * @return boolean
     */
    function offsetExists($offset) {
        return $offset >= 0 && $offset < $this->count();
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param int $offset
     *
     * @throws IndexException
     * @throws TypeException
     * @return mixed
     */
    function offsetGet($offset) {
        return $this->get($offset);
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param int $offset
     * @param mixed $value
     *
     * @throws IndexException
     * @throws TypeException
     * @return void
     */
    function offsetSet($offset, $value) {
        if ($offset === NULL) {
            $this->append($value);
            return;
        }
        $this->set($offset, $value);
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param int $offset
     *
     * @return void
     */
    function offsetUnset($offset) {
        $this->remove($offset);
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return count($this->array);
    }

    /**
     * @param $item
     *
     * @return void
     * @throws TypeException when $item is not the correct type.
     */
    function append($item) {
        $this->array[] = $item;

        $this->cache = NULL;
    }

    /**
     * @param int $index
     *
     * @return mixed
     * @throws TypeException when $index is not an integer.
     * @throws IndexException when $index < 0 or $index >= count($this).
     */
    function get($index) {
        if (filter_var($index, FILTER_VALIDATE_INT) === FALSE) {
            throw new TypeException;
        }

        if (!$this->offsetExists($index)) {
            throw new IndexException;
        }

        return $this->array[$index];
    }

    /**
     * @param int $index
     * @param $item
     *
     * @return void
     * @throws TypeException when $index is not an integer or when $item is not the correct type.
     * @throws IndexException when $index < 0 or $index >= count($this).
     */
    function set($index, $item) {
        if (filter_var($index, FILTER_VALIDATE_INT) === FALSE) {
            throw new TypeException;
        }

        if (!$this->offsetExists($index)) {
            throw new IndexException;
        }

        $this->array[$index] = $item;

        $this->cache = NULL;
    }

    /**
     * @param int $index
     *
     * @throws TypeException when $index is not an integer.
     * @return void
     */
    function remove($index) {
        if (filter_var($index, FILTER_VALIDATE_INT) === FALSE) {
            throw new TypeException;
        }

        if (!$this->offsetExists($index)) {
            return;
        }

        array_splice($this->array, $index, 1);

        $this->cache = NULL;
    }

    /**
     * @param mixed $object
     *
     * @throws TypeException if $item is the incorrect type for the Vector
     * @return void
     */
    function removeItem($object) {
        $index = array_search($object, $this->array);
        if ($index === FALSE) {
            return;
        }
        array_splice($this->array, $index, 1);

        $this->cache = NULL;
    }

    /**
     * Applies $callable to each item in the vector.
     *
     * @param callable $callable function($value, $key = NULL)
     * @return void
     */
    function apply(callable $callable) {

        foreach ($this->array as $i => $value) {
            $this->array[$i] = call_user_func($callable, $value, $i);
        }

        $this->cache = NULL;
    }

    /**
     * @var Vector
     */
    private $cache = NULL;

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return VectorIterator
     */
    function getIterator() {
        $this->cache = $this->cache ?: clone $this;

        return new VectorIterator($this->cache);
    }

}

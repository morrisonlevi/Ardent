<?php

namespace Spl;

use ArrayAccess,
    IteratorAggregate;

class Vector implements IteratorAggregate, ArrayAccess, Collection {

    private $maxSize = 10;
    private $count = 0;

    private $array;

    /**
     * @param ...
     * @throws TypeException
     */
    function __construct() {
        if (func_num_args() > 0) {
            $this->array = \SplFixedArray::fromArray(func_get_args(), FALSE);
            $this->count = $this->array->getSize();
            $this->maxSize = $this->array->getSize();
        } else {
            $this->array = new \SplFixedArray($this->maxSize);
        }
    }

    /**
     * @return void
     */
    function clear() {
        $this->array = new \SplFixedArray(10);
        $this->count = 0;
    }

    /**
     * @param $object
     *
     * @return bool
     * @throws TypeException when $object is not the correct type.
     */
    function contains($object) {
        foreach ($this->array as $item) {
            if ($object == $item) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return $this->count === 0;
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param int $offset
     *
     * @return boolean
     */
    public function offsetExists($offset) {
        return $offset >= 0 && $offset < $this->count;
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
    public function offsetGet($offset) {
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
    public function offsetSet($offset, $value) {
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
    public function offsetUnset($offset) {
        $this->remove($offset);
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count() {
        return $this->count;
    }

    /**
     * @param $item
     *
     * @return void
     * @throws TypeException when $item is not the correct type.
     */
    function append($item) {
        if ($this->count === $this->maxSize) {
            $this->maxSize *= 2;
            $this->array->setSize($this->maxSize);
        }
        $this->array[$this->count++] = $item;
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

        if ($this->offsetExists($index)) {
            for ($i = 0; $i < $this->count; $i++) {
                if ($i >= $index) {
                    if ($i + 1 < $this->count) {
                        $this->array[$i] = $this->array[$i + 1];
                    } else {
                        $this->array[$i] = NULL;
                    }
                }
            }
            $this->count--;
        }
    }

    /**
     * @param mixed $object
     *
     * @throws TypeException if $object is the incorrect type for the Vector
     * @return void
     */
    function removeObject($object) {
        for ($i = 0; $i < $this->count; $i++) {
            $item = $this->array[$i];
            if ($item === $object) {
                $this->remove($i);
                return;
            }
        }
    }

    /**
     * @param int $startIndex
     * @param int $numberOfItemsToExtract [optional] If not provided, it will extract all items after the $startIndex.
     *
     * @return Vector
     * @throws IndexException when $numberOfItemsToExtract is negative or if it would put the function out of bounds.
     * @throws IndexException when $startIndex is < 0 or >= $this->count()
     * @throws TypeException when $startIndex or $numberOfItemsToExtract are not integers.
     */
    function slice($startIndex, $numberOfItemsToExtract = NULL) {
        if (filter_var($startIndex, FILTER_VALIDATE_INT) === FALSE) {
            throw new TypeException;
        }
        if ($numberOfItemsToExtract !== NULL && filter_var($numberOfItemsToExtract, FILTER_VALIDATE_INT) === FALSE) {
            throw new TypeException;
        }

        if (!$this->offsetExists($startIndex)) {
            throw new IndexException;
        }

        $stopIndex = $numberOfItemsToExtract !== NULL
            ? $numberOfItemsToExtract + $startIndex
            : $this->count - $startIndex;

        if ($numberOfItemsToExtract < 0 || !$this->offsetExists($stopIndex)) {
            throw new IndexException;
        }

        $slice = new Vector($this->maxSize);

        for ($i = $startIndex; $i < $stopIndex; $i++) {
            $slice[] = $this->array[$i];
        }

        return $slice;
    }

    /**
     * Filters elements of the vector using a callback function.
     *
     * @param callable $callable bool function($value, $key = NULL)
     *
     * @throws TypeException if $callable is not callable.
     * @return Vector
     */
    function filter($callable) {
        if (!is_callable($callable)) {
            throw new TypeException;
        }

        $vector = new Vector($this->maxSize);

        foreach ($this->array as $key => $item) {
            if (call_user_func($callable, $item, $key)) {
                $vector->append($item);
            }
        }

        return $vector;
    }

    /**
     * Creates a new vector with the result of calling $callable on each item.
     *
     * @param callable $callable mixed function($value, $key = NULL)
     *
     * @throws TypeException
     * @return Vector
     */
    function map($callable) {
        if (!is_callable($callable)) {
            throw new TypeException;
        }

        $vector = new Vector($this->maxSize);

        foreach ($this->array as $index => $item) {
            $vector->append(call_user_func($callable, $item, $index));
        }

        return $vector;
    }

    /**
     * Applies $callable to each item in the vector.
     *
     * @param callable $callable function($value, $key = NULL)
     *
     * @throws TypeException
     * @return void
     */
    function apply($callable) {
        if (!is_callable($callable)) {
            throw new TypeException;
        }

        foreach ($this->array as $index => $item) {
            $this->set($index, call_user_func($callable, $item, $index));
        }
    }

    /**
     * @return array
     */
    function toArray() {
        return iterator_to_array($this->getIterator());
    }

    /**
     * @return VectorIterator
     */
    function getIterator() {
        return new VectorIterator($this);
    }

}

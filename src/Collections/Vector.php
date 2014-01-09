<?php

namespace Collections;

class Vector implements \ArrayAccess, \Countable, Enumerable {

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
    }

    /**
     * @return void
     */
    function clear() {
        $this->array = [];
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
     */
    function append($item) {
        $this->array[] = $item;
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

        if (!$this->offsetExists($index)) {
            return;
        }

        array_splice($this->array, $index, 1);
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
    }

    /**
     * @param callable $callback function($value, $key = NULL)
     */
    function each(callable $callback) {
        foreach ($this->array as $i => $value) {
            $callback($value, $i);
        }
    }

    /**
     * @param callable $map
     * @return Vector
     */
    function map(callable $map) {
        $vector = new self();
        foreach ($this->array as $key => $value) {
            $vector->array[] = $map($value, $key);
        }
        return $vector;
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return VectorIterator
     */
    function getIterator() {
        return new VectorIterator($this);
    }

    /**
     * @param int $n
     * @return Enumerator
     */
    function limit($n) {
        $v = new Vector();
        $v->array = array_slice($this->array, 0, $n);
        return $v;
    }

    /**
     * @param int $n
     * @return Vector
     */
    function skip($n) {
        $v = new Vector();
        $v->array = array_slice($this->array, $n);
        return $v;
    }

    /**
     * @param int $start
     * @param int $count
     * @return Vector
     */
    function slice($start, $count) {
        $v = new Vector();
        $v->array = array_slice($this->array, $start, $count);
        return $v;
    }

    /**
     * @return array
     */
    function toArray() {
       return $this->array;
    }

    /**
     * @param $initialValue
     * @param callable $combine
     * @return mixed
     */
    function reduce($initialValue, callable $combine) {
        $carry = $initialValue;
        foreach ($this->array as $value) {
            $carry = $combine($carry, $value);
        }
        return $carry;
    }

    /**
     * @param string $separator
     * @return string
     */
    function join($separator) {
        reset($this->array);
        if (key($this->array) === NULL) {
            return '';
        }
        $buff = current($this->array);
        for (next($this->array); key($this->array) !== NULL; next($this->array)) {
            $buff .= $separator . current($this->array);
        }
        return $buff;
    }

    /**
     * @param callable $compare
     * @return bool
     */
    function any(callable $compare) {
        foreach ($this->array as $key => $value) {
            if ($compare($value, $key)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * @param callable $f
     * @return bool
     */
    function every(callable $f) {
        foreach ($this->array as $key => $value) {
            if (!$f($value, $key)) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * @param callable $f
     * @return bool
     */
    function none(callable $f) {
        foreach ($this->array as $key => $value) {
            if ($f($value, $key)) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function max(callable $compare = NULL) {
        $i = new \ArrayIterator($this->array);
        $i->rewind();
        if (!$i->valid()) {
            throw new StateException;
        }
        $compare = $compare ?: 'max';
        $max = $i->current();
        for ($i->next(); $i->valid(); $i->next()) {
            $max = $compare($max, $i->current());
        }
        return $max;
    }

    /**
     * @param callable $compare
     * @return mixed
     * @throws StateException if Collection is empty
     */
    function min(callable $compare = NULL) {
        $i = new \ArrayIterator($this->array);
        $i->rewind();
        if (!$i->valid()) {
            throw new StateException;
        }
        $compare = $compare ?: 'min';
        $min = $i->current();
        for ($i->next(); $i->valid(); $i->next()) {
            $min = $compare($min, $i->current());
        }
        return $min;
    }

    /**
     * @param callable $filter
     * @return Vector
     */
    function filter(callable $filter) {
        $vector = new self();
        foreach ($this->array as $key => $value) {
            if ($filter($value, $key)) {
                $vector[] = $value;
            }
        }
        return $vector;
    }

    /**
     * @return Vector
     */
    function keys() {
        $vector = new Vector();
        $i = new \ArrayIterator($this->array);
        for ($i->rewind(); $i->valid(); $i->next()) {
            $vector->append($i->key());
        }
        return $vector;
    }

    /**
     * @return Vector
     */
    function values() {
        return $this;
    }

}

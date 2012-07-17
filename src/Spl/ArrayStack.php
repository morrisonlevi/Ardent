<?php

namespace Spl;

use Iterator;

class ArrayStack implements Iterator, Stack {

    private $stack = array();

    /**
     * @return void
     */
    public function clear() {
        $this->stack = array();
    }

    /**
     * @param $object
     * @return bool
     * @throws InvalidTypeException when $object is not the correct type.
     */
    public function contains($object) {
        return in_array($object, $this->stack);
    }

    /**
     * @return bool
     */
    public function isEmpty() {
        return count($this->stack) === 0;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count() {
        return count($this->stack);
    }

    /**
     * @param mixed $object
     * @throws InvalidTypeException if $object is not the correct type.
     * @throws OverflowException if the Stack is full.
     * @return void
     */
    public function push($object) {
        array_push($this->stack, $object);
    }

    /**
     * @throws UnderflowException if the Stack is empty.
     * @return mixed
     */
    public function pop() {
        if (count($this->stack) === 0) {
            throw new UnderflowException();
        }

        return array_pop($this->stack);

    }

    /**
     * @throws UnderflowException if the Stack is empty.
     * @return mixed
     */
    public function peek() {
        if (count($this->stack) === 0) {
            throw new UnderflowException();
        }

        return $this->stack[0];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current() {
        return current($this->stack);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next() {
        prev($this->stack);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key() {
        return key($this->stack);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid() {
        return key($this->stack) !== NULL;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind() {
        reset($this->stack);

        //set the internal pointer to the very end.
        for (; key($this->stack) !== NULL; next($this->stack));

    }

}

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
     *
     * @return bool
     * @throws TypeException when $object is not the correct type.
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
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count() {
        return count($this->stack);
    }

    /**
     * @param mixed $object
     *
     * @throws TypeException if $object is not the correct type.
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

        return $this->stack[count($this->stack) - 1];
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    public function current() {
        return current($this->stack);
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    public function next() {
        prev($this->stack);
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    public function key() {
        return key($this->stack);
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid() {
        return key($this->stack) !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind() {
        reset($this->stack);

        //set the internal pointer to the very end.
        for (; key($this->stack) !== NULL; next($this->stack)) ;

    }

}

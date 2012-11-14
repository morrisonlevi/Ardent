<?php

namespace Spl;

use IteratorAggregate;


class ArrayStack implements IteratorAggregate, Stack {

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
     * @throws FullException if the Stack is full.
     * @return void
     */
    public function pushBack($object) {
        array_push($this->stack, $object);
    }

    /**
     * @throws EmptyException if the Stack is empty.
     * @return mixed
     */
    public function popBack() {
        if (count($this->stack) === 0) {
            throw new EmptyException();
        }

        return array_pop($this->stack);

    }

    /**
     * @throws EmptyException if the Stack is empty.
     * @return mixed
     */
    public function peekBack() {
        if (count($this->stack) === 0) {
            throw new EmptyException();
        }

        return $this->stack[count($this->stack) - 1];
    }

    function getIterator() {
        return new ArrayStackIterator($this->stack);
    }

}

<?php

namespace Spl;

class ArrayStack implements Stack {

    private $stack = array();

    /**
     * @return void
     */
    function clear() {
        $this->stack = array();
    }

    /**
     * @param $item
     *
     * @return bool
     * @throws TypeException when $item is not the correct type.
     */
    function contains($item) {
        return in_array($item, $this->stack);
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return count($this->stack) === 0;
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return count($this->stack);
    }

    /**
     * @param mixed $object
     *
     * @throws TypeException if $item is not the correct type.
     * @throws FullException if the Stack is full.
     * @return void
     */
    function pushBack($object) {
        array_push($this->stack, $object);
    }

    /**
     * @throws EmptyException if the Stack is empty.
     * @return mixed
     */
    function popBack() {
        if (count($this->stack) === 0) {
            throw new EmptyException();
        }

        return array_pop($this->stack);

    }

    /**
     * @throws EmptyException if the Stack is empty.
     * @return mixed
     */
    function peekBack() {
        if (count($this->stack) === 0) {
            throw new EmptyException();
        }

        return $this->stack[count($this->stack) - 1];
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return StackIterator
     */
    function getIterator() {
        return new ArrayStackIterator($this->stack);
    }

}

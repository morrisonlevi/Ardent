<?php

namespace Collections;

class LinkedStack implements Stack {

    use IteratorCollection;

    /**
     * @var Pair
     */
    private $top;

    private $size = 0;

    /**
     * @return bool
     */
    function isEmpty() {
        return $this->top === NULL;
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return StackIterator
     */
    function getIterator() {
        return new LinkedStackIterator($this->size, $this->clonePair($this->top));
    }

    private function clonePair(Pair $pair = NULL) {
        if ($pair === NULL) {
            return NULL;
        }

        $new = new Pair($pair->first, $pair->second);
        for ($current = $new; $current->second !== NULL; $current = $current->second) {
            $current->second = new Pair(
                $current->second->first,
                $current->second->second
            );
        }
        return $new;
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->size;
    }

    /**
     * @param mixed $object
     *
     * @throws TypeException if $object is not the correct type.
     * @throws FullException if the Stack is full.
     * @return void
     */
    function push($object) {
        $this->top = new Pair($object, $this->top);
        $this->size++;
    }

    /**
     * @throws EmptyException if the Stack is empty.
     * @return mixed
     */
    function pop() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        $top = $this->top;
        $this->top = $top->second;

        $this->size--;
        return $top->first;
    }

    /**
     * @throws EmptyException if the Stack is empty.
     * @return mixed
     */
    function last() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        return $this->top->first;
    }


    function clear() {
        $this->top = NULL;
        $this->size = 0;
    }


    /**
     * @return array
     */
    function toArray() {
        $a = [];
        $current = $this->top;
        while ($current !== NULL) {
            $a[] = $current->first;
            $current = $current->second;
        }
        return $a;
    }

}

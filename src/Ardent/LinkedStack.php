<?php

namespace Ardent;

use Ardent\Exception\EmptyException;
use Ardent\Exception\FullException;
use Ardent\Exception\TypeException;
use Ardent\Iterator\LinkedStackIterator;
use Ardent\Iterator\StackIterator;

class LinkedStack implements Stack {

    use StructureCollection;

    /**
     * @var Pair
     */
    private $top;

    /**
     * @var int
     */
    private $count;

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
        return new LinkedStackIterator($this->count, $this->clonePair($this->top));
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
        return $this->count;
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
        $this->count++;
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

        $this->count--;
        return $top->first;
    }

    /**
     * @throws EmptyException if the Stack is empty.
     * @return mixed
     */
    function peek() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        return $this->top->first;
    }

}

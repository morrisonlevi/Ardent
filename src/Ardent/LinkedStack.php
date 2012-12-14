<?php

namespace Ardent;

class LinkedStack implements Stack {

    /**
     * @var LinkedList
     */
    private $list;
    
    function __construct() {
        $this->list = new LinkedList();
    }
    
    /**
     * @param $item
     *
     * @return bool
     * @throws TypeException when $item is not the correct type.
     */
    function contains($item) {
        return $this->list->contains($item);
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return $this->list->isEmpty();
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return StackIterator
     */
    function getIterator() {
        return new LinkedStackIterator(clone $this->list);
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->list->count();
    }

    /**
     * @param mixed $object
     *
     * @throws TypeException if $object is not the correct type.
     * @throws FullException if the Stack is full.
     * @return void
     */
    function pushBack($object) {
        $this->list->pushBack($object);
    }

    /**
     * @throws EmptyException if the Stack is empty.
     * @return mixed
     */
    function popBack() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        return $this->list->popBack();
    }

    /**
     * @throws EmptyException if the Stack is empty.
     * @return mixed
     */
    function peekBack() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        return $this->list->peekBack();
    }

    /**
     * @return LinkedList A copy of the stack as a LinkedList
     */
    function getLinkedList() {
        return clone $this->list;
    }

}

<?php

namespace Ardent;

class LinkedQueue implements Queue {

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
    function isEmpty()  {
        return $this->list->isEmpty();
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return QueueIterator
     */
    function getIterator() {
        return new LinkedQueueIterator(clone $this->list);
    }

    /**
     * @param $item
     * @return void
     * @throws FullException if the Queue is full.
     * @throws TypeException when $item is not the correct type.
     */
    function push($item) {
        $this->list->pushBack($item);
    }

    /**
     * @return mixed
     * @throws EmptyException if the Queue is empty.
     */
    function pop() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }
        return $this->list->popFront();
    }

    /**
     * @return mixed
     * @throws EmptyException if the Queue is empty.
     */
    function peek() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }
        return $this->list->peekFront();
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->list->count();
    }

    /**
     * @return LinkedList A copy of the queue as a LinkedList
     */
    function getLinkedList() {
        return clone $this->list;
    }

}

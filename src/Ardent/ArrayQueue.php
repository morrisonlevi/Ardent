<?php

namespace Ardent;

class ArrayQueue implements Queue {

    /**
     * @var array
     */
    protected $queue = array();

    /**
     * @param $item
     *
     * @return void
     * @throws TypeException when $item is not the correct type.
     */
    function pushBack($item) {
        $this->queue[] = $item;
    }

    /**
     * @return mixed
     * @throws EmptyException if the Stack is empty.
     */
    function popFront() {
        if ($this->count() === 0) {
            throw new EmptyException;
        }

        return array_shift($this->queue);
    }

    /**
     * @return mixed
     * @throws EmptyException if the Queue is empty.
     */
    function peekFront() {
        if ($this->count() === 0) {
            throw new EmptyException;
        }

        return $this->queue[0];
    }

    /**
     * @return int
     */
    function count() {
        return count($this->queue);
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return QueueIterator
     */
    function getIterator() {
        return new ArrayQueueIterator($this->queue);
    }

    /**
     * @return void
     */
    function clear() {
        $this->queue = array();
    }

    /**
     * @param $item
     *
     * @return bool
     * @throws TypeException when $item is not the correct type.
     */
    function contains($item) {
        return in_array($item, $this->queue);
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return empty($this->queue);
    }

    /**
     * @return array
     */
    function getArrayList() {
        return $this->queue;
    }

}

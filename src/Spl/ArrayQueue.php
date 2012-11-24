<?php

namespace Spl;

class ArrayQueue implements Queue {

    /**
     * @var array
     */
    protected $queue;

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

        return array_slice($this->queue, 0, 1, $PRESERVE_KEYS = FALSE);
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
     * @return ArrayQueueIterator
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
     * @param $object
     *
     * @return bool
     * @throws TypeException when $object is not the correct type.
     */
    function contains($object) {
        return in_array($object, $this->queue);
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return empty($this->queue);
    }

}

<?php

namespace Collections;

class LinkedQueue implements Queue {

    /**
     * @var Pair
     */
    private $head;

    /**
     * @var Pair
     */
    private $tail;

    private $size = 0;

    /**
     * @return bool
     */
    function isEmpty()  {
        return $this->head === NULL;
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return QueueIterator
     */
    function getIterator() {
        return new LinkedQueueIterator($this->size, $this->head);
    }

    /**
     * @param $item
     * @return void
     * @throws StateException if the Queue is full.
     */
    function enqueue($item) {
        $pair = new Pair($item, NULL);

        if ($this->tail !== NULL) {
            $this->tail = $this->tail->second = $pair;
        } else {
            $this->head = $this->tail = $pair;
        }

        $this->size++;
    }

    /**
     * @return mixed
     * @throws StateException if the Queue is empty.
     */
    function dequeue() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }
        $item = $this->head->first;
        $this->head = $this->head->second;
        $this->size--;

        return $item;
    }

    /**
     * @return mixed
     * @throws StateException if the Queue is empty.
     */
    function first() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }
        return $this->head->first;
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->size;
    }

    /**
     * @param callable $compare
     * @return bool
     */
    function any(callable $compare) {
        for ($n = $this->head; $n !== NULL; $n = $n->second) {
            if ($compare($n->first)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * @param mixed $item
     * @return bool
     */
    function contains($item) {
        for ($n = $this->head; $n !== NULL; $n = $n->second) {
            if ($item == $n->first) {
                return TRUE;
            }
        }
        return FALSE;
    }

    function clear() {
        $this->size = 0;
        $this->head = $this->tail = NULL;
    }

    function toArray() {
        $array = [];
        $current = $this->head;
        while ($current !== NULL) {
            $array[] = $current->first;
            $current = $current->second;
        }
        return $array;
    }

}

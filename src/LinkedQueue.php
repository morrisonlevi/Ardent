<?php

namespace Collections;

class LinkedQueue implements Queue {

    use IteratorCollection;

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
     * Returns the first element in the Queue without removing it.
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


    /**
     * Empties the Queue.
     * @return void
     */
    function clear() {
        $this->size = 0;
        $this->head = $this->tail = NULL;
    }

}

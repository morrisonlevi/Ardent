<?php

namespace Ardent\Collection;

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
    function isEmpty() {
        return $this->head === null;
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
        $pair = new Pair($item, null);

        if ($this->tail !== null) {
            $this->tail = $this->tail->second = $pair;
        } else {
            $this->head = $this->tail = $pair;
        }

        $this->size++;
    }


    /**
     * @return mixed
     */
    function dequeue() {
        assert(!$this->isEmpty());
        $item = $this->head->first;
        $this->head = $this->head->second;
        $this->size--;

        return $item;
    }


    /**
     * Returns the first element in the Queue without removing it.
     * @return mixed
     */
    function first() {
        assert(!$this->isEmpty());
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
        for ($n = $this->head; $n !== null; $n = $n->second) {
            if ($item == $n->first) {
                return true;
            }
        }
        return false;
    }


    /**
     * Empties the Queue.
     * @return void
     */
    function clear() {
        $this->size = 0;
        $this->head = $this->tail = null;
    }

}

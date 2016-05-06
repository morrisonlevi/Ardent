<?php

namespace Ardent\Collection;


class LinkedQueueIterator implements QueueIterator {

    private $size = 0;

    /**
     * @var Pair
     */
    private $head;

    /**
     * @var Pair
     */
    private $current;

    /**
     * @var Int
     */
    private $key = null;


    /**
     * @param Int $count
     * @param Pair $head
     */
    function __construct($count, Pair $head = null) {
        $this->head = $head;
        $this->size = $count;
        $this->rewind();
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return Mixed Can return any type.
     */
    function current() {
        return $this->current->first;
    }


    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->current = $this->current->second;
        $this->key++;
    }


    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return Mixed
     */
    function key() {
        return $this->key;
    }


    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return Bool
     */
    function valid() {
        return $this->current !== null;
    }


    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->current = $this->head;

        $this->key = $this->head !== null
            ? 0
            : null;
    }


    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return Int
     */
    function count() {
        return $this->size;
    }


    /**
     * @return Bool
     */
    function isEmpty() {
        return $this->count() === 0;
    }


}

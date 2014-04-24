<?php

namespace Collections;

class LinkedQueueIterator implements QueueIterator {

    use IteratorCollection;

    private $count = 0;

    /**
     * @var Pair
     */
    private $head;

    /**
     * @var Pair
     */
    private $current;

    /**
     * @var int
     */
    private $key = null;


    /**
     * @param int $count
     * @param Pair $head
     */
    function __construct($count, Pair $head = null) {
        $this->head = $head;
        $this->count = $count;
        $this->rewind();
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
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
     * @return mixed
     */
    function key() {
        return $this->key;
    }


    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
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
     * @return int
     */
    function count() {
        return $this->count;
    }

}

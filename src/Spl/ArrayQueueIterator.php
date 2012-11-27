<?php

namespace Spl;

class ArrayQueueIterator implements QueueIterator {

    /**
     * @var ArrayIterator
     */
    private $iterator;

    function __construct(array $queue) {
        $this->iterator = new \ArrayIterator($queue);
        $this->rewind();
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->iterator->current();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->iterator->next();
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return int
     */
    function key() {
        return $this->iterator->key();
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->iterator->valid();
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->iterator->rewind();
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->iterator->count();
    }

}

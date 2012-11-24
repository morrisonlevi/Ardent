<?php

namespace Spl;

class ArrayQueueIterator implements CountableSeekableIterator, QueueIterator {

    /**
     * @var ArrayIterator
     */
    private $queue;

    function __construct(array $queue) {
        $this->queue = new ArrayIterator($queue);
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->queue->current();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->queue->next();
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return int
     */
    function key() {
        $this->queue->key();
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->queue->valid();
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->queue->rewind();
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->queue->count();
    }

    /**
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @param int $position
     * @return void
     */
    function seek($position) {
        $this->queue->seek($position);
    }

}

<?php

namespace Ardent\Collection;

use Exception;
use Iterator;

class PriorityQueueIterator implements Iterator
{
    /** @var mixed */
    private $current;

    /** @var bool */
    private $done = false;

    /** @var int */
    private $index;

    /** @var PriorityQueue */
    private $queue;

    /**
     * PriorityQueueIterator constructor.
     * @param PriorityQueue $queue
     */
    public function __construct(PriorityQueue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->queue->isEmpty();
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        if (!$this->queue->isEmpty()) {
            $this->current = $this->queue->dequeue();
            $this->index++;
        } else {
            $this->done = true;
        }
    }

    /**
     * @throws Exception
     */
    public function rewind()
    {
        if ($this->done) {
            throw new Exception(__CLASS__ . ' can only be used to iterate once.');
        } else {
            $this->current = $this->queue->dequeue();
            $this->index = 0;
        }
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return !$this->done;
    }
}
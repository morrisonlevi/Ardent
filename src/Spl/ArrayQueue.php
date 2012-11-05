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
     * @throws UnderflowException if the Stack is empty.
     */
    function popFront() {
        if ($this->count() === 0) {
            throw new UnderflowException;
        }

        return array_slice($this->queue, 0, 1, $PRESERVE_KEYS = FALSE);
    }

    /**
     * @return mixed
     * @throws UnderflowException if the Queue is empty.
     */
    function peekFront() {
        if ($this->count() === 0) {
            throw new UnderflowException;
        }

        return $this->queue[0];
    }

    /**
     * @return int
     */
    function count() {
        return count($this->queue);
    }

}

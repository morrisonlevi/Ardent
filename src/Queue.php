<?php

namespace Collections;

interface Queue extends \Countable, Enumerable {

    /**
     * @param $item
     *
     * @return void
     * @throws StateException if the Queue is full.
     */
    function enqueue($item);

    /**
     * @return mixed
     * @throws StateException if the Queue is empty.
     */
    function dequeue();

    /**
     * Returns the next item to be removed without removing it.
     *
     * @return mixed
     * @throws StateException if the Queue is empty.
     */
    function first();


    function getIterator(): QueueIterator;


}

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
     * @return mixed
     * @throws StateException if the Queue is empty.
     */
    function first();

}

<?php

namespace Ardent;

use Ardent\Exception\StateException;
use Countable;
use IteratorAggregate;

interface Queue extends IteratorAggregate, Countable {

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
    function peek();

    /**
     * @return array
     */
    function toArray();

}

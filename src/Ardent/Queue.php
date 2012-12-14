<?php

namespace Ardent;

interface Queue extends Collection {
    /**
     * @param $item
     *
     * @return void
     * @throws FullException if the Queue is full.
     * @throws TypeException when $item is not the correct type.
     */
    function pushBack($item);

    /**
     * @return mixed
     * @throws EmptyException if the Queue is empty.
     */
    function popFront();

    /**
     * @return mixed
     * @throws EmptyException if the Queue is empty.
     */
    function peekFront();
}

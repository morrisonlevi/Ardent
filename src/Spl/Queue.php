<?php

namespace Spl;

interface Queue extends Collection {
    /**
     * @param $item
     *
     * @return void
     * @throws OverflowException if the Queue is full.
     * @throws TypeException when $item is not the correct type.
     */
    function pushBack($item);

    /**
     * @return mixed
     * @throws UnderflowException if the Queue is empty.
     */
    function popFront();

    /**
     * @return mixed
     * @throws UnderflowException if the Queue is empty.
     */
    function peekFront();
}

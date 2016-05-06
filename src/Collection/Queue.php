<?php

namespace Ardent\Collection;


interface Queue extends \Countable, Enumerable {

    /**
     * @param $item
     *
     * @return void
     */
    function enqueue($item);


    /**
     * @return Mixed
     */
    function dequeue();


    /**
     * Returns the next item to be removed without removing it.
     *
     * @return Mixed
     */
    function first();


    /**
     * @return QueueIterator
     */
    function getIterator();

}

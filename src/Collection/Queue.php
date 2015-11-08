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
     * @return mixed
     */
    function dequeue();


    /**
     * Returns the next item to be removed without removing it.
     *
     * @return mixed
     */
    function first();


    /**
     * @return QueueIterator
     */
    function getIterator();

}

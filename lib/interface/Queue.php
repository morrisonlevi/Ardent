<?php
namespace Spl;

require_once 'Collection.php';

interface Queue extends Collection {
    /**
     * Looks at the head item of the queue without removing it.
     * 
     * @return mixed The item at the head of the queue.
     * @throws \UnderflowException if the queue is empty.
     */
    function peek();

    /**
     * Removes the head item from the queue and returns it.
     * 
     * @return mixed The item at the head of the queue.
     * @throws \UnderflowException if the queue is empty.
     */
    function pop();

    /**
     * Puts an item onto the tail of the queue.
     * 
     * @param mixed $item The item to be pushed onto the queue.
     * @return void
     */
    function push($item);
    
}

?>

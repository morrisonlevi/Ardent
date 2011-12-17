<?php

interface Stack extends Countable, Iterator {

    /**
     * Clears out everything from the stack.
     * 
     * @return void
     */
    function clear();

    /**
     * Tests if the stack is empty.
     * 
     * @return bool Returns true if and only if the stack contains no items; false otherwise.
     */
    function isEmpty();

    /**
     * Looks at the top item of the stack without removing it.
     * 
     * @return mixed The item at the top of the stack.
     * @throws \UnderflowException if the stack is empty.
     */
    function peek();

    /**
     * Removes the top item from the stack and returns it.
     * 
     * @return mixed The item at the top of the stack.
     * @throws \UnderflowException if the stack is empty.
     */
    function pop();

    /**
     * Puts an item onto the top of the stack.
     * 
     * @param mixed $item The item to be pushed onto the stack.
     * @return void
     */
    function push($item);
    
}

?>

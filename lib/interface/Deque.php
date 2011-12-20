<?php
namespace Spl;

interface Deque extends \Countable {

    /**
     * Retrieves the head item of the deque without removing it.
     *
     * @return mixed The head item in the deque.
     * @throws \UnderflowException if the deque is empty.
     */
    function peek();

    /**
     * Retrieves the tail item of the deque without removing it.
     *
     * @return mixed The tail item in the deque.
     * @throws \UnderflowException if the deque is empty.
     */
    function peekTail();

    /**
     * Removes the tail item of the deque and returns it.
     *
     * @return mixed The tail item in the deque.
     * @throws \UnderflowException if the deque is empty.
     */
    function pop();

    /**
     * Adds the given item to the tail of the deque.
     *
     * @param mixed $item The item to add.
     * @return void
     */
    function push($item);

    /**
     * Removes the head item of the deque and returns it.
     *
     * @return mixed The head item in the deque.
     * @throws \UnderflowException if the deque is empty.
     */
    function shift();

    /**
     * Adds the given item to the head of the deque.
     *
     * @param mixed $item The item to add.
     * @return void
     */
    function unshift($item);

}

?>

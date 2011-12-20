<?php
namespace Spl;

require_once 'interface/Vector.php';
require_once 'interface/Deque.php';

/**
 * An initial API for LinkedList. A LinkedList is a doubly-linked list that can
 * be used like an array or a double-ended queue (commonly called a Deque).
 */
class LinkedList implements \Iterator, Vector, Deque {

    /**
     * @var \SplDoublyLinkedList
     */
    private $list;

    /**
     * Creates a new LinkedList. A LinkedList is a doubly-linked list that can
     * be used like an array or a double-ended queue (commonly called a Deque).
     */
    public function __construct() {
        $this->list = new \SplDoublyLinkedList();
    }

    /**
     * Returns the number of items in the list.
     * 
     * @return int The size of the list.
     */
    public function count() {
        return $this->list->count();
    }

    /**
     * Returns true if this list contains no items.  This is exactly 
     * equivalent to testing count for 0.
     *
     * @return bool Returns true if the colleciton contains no items.
     */
    public function isEmpty() {
        return $this->list->count() === 0;
    }

    /**
     * Removes all items from the list.
     *
     * @return void
     */
    public function clear() {
        $this->list = new \SplDoublyLinkedList();
    }
    
    /**
     * Returns true if the list contains the given item at least once.
     * 
     * @param mixed $item Item whose presence is to be tested.
     * @return bool Returns true if the list contains the item.
     */
    public function contains($item) {
        foreach($this->list as $value) {
            if ($value == $item) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the value at the specified position.
     *
     * @param int $index The index being obtained.
     * @return mixed The value at the specified position.
     * @throws \OutOfRangeException if the index is not an int.
     * @throws \OutOfBoundsException if the index does not exist.
     */
    public function offsetGet($index) {
        if (filter_var($index, FILTER_VALIDATE_INT) === false) {
            throw new \OutOfRangeException('Invalid index type: expected int.');
        }
        if ($index < 0 || $index >= $this->list->count()) {
            throw new \OutOfBoundsException("Index '$index' is out-of-bounds.");
        }

        return $this->list[$index];
    }

    /**
     * Set the value at the specified position to the given value.
     *
     * @param int $index The index being set.
     * @param mixed $value The new value for the index.
     * @return void
     * @throws \OutOfRangeException if the index is not an int.
     * @throws \OutOfBoundsException if the index does not exist.
     */
    public function offsetSet($index, $value) {
        if ($index === null) {
            $this->list[] = $value;
        } else {
            if (filter_var($index, FILTER_VALIDATE_INT) === false) {
                throw new \OutOfRangeException(
                    'Invalid index type: expected int.'
                );
            }
            if ($index < 0 || $index >= $this->list->count()) {
                throw new \OutOfBoundsException(
                    "Index '$index' is out-of-bounds."
                );
            }

            $this->list[$index] =  $value;
        }

    }

    /**
     * Returns whether the requested index exists.
     *
     * @param int $index The index being checked.
     * @return bool Returns true if the index exists.
     */
    public function offsetExists($index) {
        return filter_var($index, FILTER_VALIDATE_INT) !== false
            && $index >= 0
            && $index < $this->list->count();
    }

    /**
     * Unsets the value at the specified position.
     *
     * @param int $index The index being set.
     * @return void
     * @throws \OutOfRangeException if the index is not an int.
     * @throws \OutOfBoundsException if the index does not exist.
     */
    public function offsetUnset($index) {
        if (filter_var($index, FILTER_VALIDATE_INT) === false) {
            throw new \OutOfRangeException('Invalid index type: expected int');
        }
        if ($index < 0 || $index >= $this->list->count()) {
            throw new \OutOfBoundsException("Index '$index' is out-of-bounds");
        }

        unset($this->list[$index]);
    }

    /**
     * Retrieves the head item of the list without removing it.
     *
     * @return mixed The head item in the list.
     * @throws \UnderflowException if the list is empty.
     */
    public function peek() {
        if ($this->list->count() === 0) {
            throw new \UnderflowException(
                'Cannot peek from an empty structure.'
            );
        }

        return $this->list[0];
    }

    /**
     * Retrieves the tail item of the list without removing it.
     *
     * @return mixed The tail item in the list.
     * @throws \UnderflowException if the list is empty.
     */
    public function peekTail() {
        if ($this->list->count() === 0) {
            throw new \UnderflowException(
                'Cannot peek at the tail of an empty structure.'
            );
        }

        return $this->list->top();
    }

    /**
     * Removes the tail item of the list and returns it.
     *
     * @return mixed The tail item in the list.
     * @throws \UnderflowException if the list is empty.
     */
    public function pop() {
        if ($this->list->count() === 0) {
            throw new \UnderflowException(
                'Cannot pop from an empty structure.'
            );
        }

        return $this->list->pop();
    }

    /**
     * Adds the given item to the tail of the list.
     *
     * @param mixed $item The item to add.
     * @return void
     */
    public function push($item) {
        $this->list->push($item);
    }

    /**
     * Removes the head item of the list and returns it.
     *
     * @return mixed The head item in the list.
     * @throws \UnderflowException if the list is empty.
     */
    public function shift() {
        if ($this->list->count() === 0) {
            throw new \UnderflowException(
                'Cannot shift from an empty structure.'
            );
        }

        return $this->list->shift();
    }

    /**
     * Adds the given item to the head of the list.
     *
     * @param mixed $item The item to add.
     * @return void
     */
    public function unshift($item) {
        $this->list->unshift($item);
    }

    /**
     * Get the value of the current item.
     *
     * @return mixed The value of the current item.
     */
    public function current() {
        return $this->list->current();
    }

    /**
     * Returns the index of the current item.
     *
     * @return int The index of the current item.
     */
    public function key() {
        return $this->list->key();
    }

    /**
     * Move the iterator to the next item.
     *
     * @return void
     */
    public function next() {
        $this->list->next();
    }

    /**
     * Rewinds the iterator to the head of the list.
     *
     * @return void
     */
    public function rewind() {
        $this->list->rewind();
    }

    /**
     * Returns true if the list contains more items.
     *
     * @return bool Returns true if the list contains more items.
     */
    public function valid() {
        return $this->list->valid();
    }

}

?>

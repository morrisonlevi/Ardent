<?php
namespace Spl;

require_once 'interface/Vector.php';
require_once 'interface/Deque.php';

/**
 * An initial API for LinkedList.
 */
class LinkedList implements \Iterator, Vector, Deque {

    /**
     * @var \SplDoublyLinkedList
     */
    private $list;

    public function __construct() {
        $this->list = new \SplDoublyLinkedList();
    }

    /**
     * Returns the number of items in the list.
     * 
     * @return int
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

    public function offsetGet($index) {
        if (filter_var($index, FILTER_VALIDATE_INT) === false) {
            throw new \OutOfRangeException('Invalid index type: expected int.');
        }
        if ($index < 0 || $index >= $this->list->count()) {
            throw new \OutOfBoundsException("Index '$index' is out-of-bounds.");
        }

        return $this->list[$index];
    }

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

    public function offsetExists($index) {
        return filter_var($index, FILTER_VALIDATE_INT) !== false
            && $index >= 0
            && $index < $this->list->count();
    }

    public function offsetUnset($index) {
        if (filter_var($index, FILTER_VALIDATE_INT) === false) {
            throw new \OutOfRangeException('Invalid index type: expected int');
        }
        if ($index < 0 || $index >= $this->list->count()) {
            throw new \OutOfBoundsException("Index '$index' is out-of-bounds");
        }

        unset($this->list[$index]);
    }

    public function peek() {
        if ($this->list->count() === 0) {
            throw new \UnderflowException(
                'Cannot peek from an empty structure.'
            );
        }

        return $this->list[0];
    }

    public function peekTail() {
        if ($this->list->count() === 0) {
            throw new \UnderflowException(
                'Cannot peek at the tail of an empty structure.'
            );
        }

        return $this->list->top();
    }

    public function pop() {
        if ($this->list->count() === 0) {
            throw new \UnderflowException(
                'Cannot pop from an empty structure.'
            );
        }

        return $this->list->pop();
    }

    public function push($item) {
        $this->list->push($item);
    }

    public function shift() {
        if ($this->list->count() === 0) {
            throw new \UnderflowException(
                'Cannot shift from an empty structure.'
            );
        }

        return $this->list->shift();
    }

    public function unshift($item) {
        $this->list->unshift($item);
    }

    public function current() {
        return $this->list->current();
    }

    public function key() {
        return $this->list->key();
    }

    public function next() {
        $this->list->next();
    }

    public function rewind() {
        $this->list->rewind();
    }

    public function valid() {
        return $this->list->valid();
    }

}

?>

<?php

namespace Spl;

use ArrayIterator;
use IteratorAggregate;
use SplFixedArray;
use Spl\Interfaces\Vector;

use OutOfRangeException;
use OutOfBoundsException;

/**
 * An initial API for ArrayList. It would essentially be a high-level of
 * abstraction with a low-level implementation for performance. PHP's arrays
 * are really poor if you just want true index-based datastructures.
 *
 * Note that IteratorAggregate must come before Vector or PHP will fail to
 * work correctly.  This is a bug regarding Traversable.
 */
class ArrayList implements IteratorAggregate, Vector {

    /**
     * @var \SplFixedArray
     */
    private $list;
    /**
     * @var int The number of slots used.
     */
    private $count;

    /**
     * Creates a new ArrayList.
     */
    public function __construct() {
        $this->list = new SplFixedArray(0);
        $this->count = 0;
    }

    /**
     * Returns the collection flattened into a normal array
     *
     * @return array The data from the collection
     */
    public function toArray() {
        return array_slice($this->list->toArray(), 0, $this->count);
    }

    /**
     * Returns the value at the specified position.
     *
     * @param int $index The index being obtained.
     * @return mixed The value at the specified position.
     * @throws \OutOfRangeException if the index is not an int.
     * @throws \OutOfBoundsException if the index does not exist.
     */
    public function offsetGet($offset) {
        if (filter_var($offset, FILTER_VALIDATE_INT) === false) {
            throw new OutOfRangeException("Invalid index type: expected int");
        } else if ($offset < 0 || $offset >= $this->count) {
            throw new OutOfBoundsException(
                "Index '$offset' is out-of-bounds."
            );
        }

        return $this->list[$offset];
    }

    /**
     * Doubles the size of the interal array.
     *
     * @return void
     */
    protected function grow() {
        $size = $this->list->getSize() * 2;
        if (!$size) {
            $size = 2;
        }
        $this->list->setSize($size);
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
    public function offsetSet($offset, $value) {
        //Adding to the array
        if ($offset === null) {
            if ($this->count === $this->list->getSize()) {
                $this->grow();
            }

            $this->list[$this->count++] = $value;
        }
        //Setting an index
        else {
            if (filter_var($offset, FILTER_VALIDATE_INT) === false) {
                throw new \OutOfRangeException(
                    "Invalid index type: expected int"
                );
            } else if ($offset < 0 || $offset > $this->count) {
                throw new OutOfBoundsException(
                    "Index '$offset' out-of-bounds."
                );
            }
            if ($offset == $this->count) {
                $this->offsetSet(null, $value);
            } else {
                $this->list[$offset] = $value;
            }

        }
    }

    /**
     * Returns whether the requested index exists.
     *
     * @param int $index The index being checked.
     * @return bool Returns true if the index exists.
     */
    public function offsetExists($offset) {
        return filter_var($offset, FILTER_VALIDATE_INT) !== false
        && $offset >= 0
        && $offset < $this->count;
    }

    /**
     * Unsets the value at the specified position.
     *
     * @param int $index The index being set.
     * @return void
     * @throws \OutOfRangeException if the index is not an int.
     * @throws \OutOfBoundsException if the index does not exist.
     */
    public function offsetUnset($offset) {
        if (filter_var($offset, FILTER_VALIDATE_INT) === false) {
            throw new \OutOfRangeException(
                "Invalid index type: expected int"
            );
        } else if ($offset < 0 || $offset >= $this->list->getSize()) {
            throw new OutOfBoundsException(
                "Index '$offset' out-of-bounds."
            );
        }

        unset($this->list[$offset]);
        $this->count--;
    }

    /**
     * Returns the number of items in the list.
     *
     * @return int The size of the list.
     */
    public function count() {
        return $this->count;
    }

    /**
     * Returns true if this list contains no items.  This is exactly
     * equivalent to testing count for 0.
     *
     * @return bool Returns true if the colleciton contains no items.
     */
    public function isEmpty() {
        return $this->count === 0;
    }

    /**
     * Removes all items from the list.
     *
     * @return void
     */
    public function clear() {
        $this->list->setSize(0);
        $this->count = 0;
    }

    /**
     * Returns true if the list contains the given item at least once.
     *
     * @param mixed $item Item whose presence is to be tested.
     * @return bool Returns true if the list contains the item.
     */
    public function contains($item) {
        for ($i = 0; $i < $this->count; $i++) {
            $value = $this->list[$i];
            //the object checks are there to prevent PHP from issuing an
            //error.  They may not be needed.
            if ((is_object($value) && !is_object($item))
                || (!is_object($value) && is_object($item))) {
                continue;
            }
            if ($value == $item) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns an external iterator.
     *
     * @return Traversable A traversable object.
     */
    public function getIterator() {
        return new ArrayIterator($this->toArray());
    }

    /**
     * Apply an arbitrary callback to each element of the collection, returning the
     * same structure with the return values in place
     *
     * @param Callable $callback The callback to apply: func($value, $key)
     *
     * @return Collection the same structure, with values replaced
     */
    public function map($callback) {
        $newList = new self;
        foreach ($this as $key => $value) {
            $newList[] = $callback($value, $key);
        }
        return $newList;
    }

    /**
     * Apply an arbitrary callback to each element of the collection, returning the
     * same structure with only items the callback returns true.
     *
     * @param Callable $callback The callback to apply: func($value, $key)
     *
     * @return Collection The same structure, with filtered values only
     */
    public function filter($callback) {
        $newList = new self;
        foreach ($this as $key => $value) {
            if ($callback($value, $key)) {
                $newList[] = $value;
            }
        }
        return $newList;
    }

}

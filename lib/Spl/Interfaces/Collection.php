<?php

namespace Spl\Interfaces;

/**
 * By Collection, we do not mean the same thing as a Java collection. It is
 * simply a name by which we can give generic properties to.
 */
interface Collection extends \Countable, \Traversable {

    /**
     * Returns the collection flattened into a normal array
     *
     * @return array The data from the collection
     */
    public function toArray();

    /**
     * Removes all of the elements from the collection.
     *
     * @return void
     */
    public function clear();

    /**
     * Returns true if the collection contains the given item at least once.
     *
     * @param mixed $item Item whose presence is to be tested.
     *
     * @return bool Returns true if the collection contains the item.
     */
    public function contains($item);

    /**
     * Returns true if this collection contains no items.  This is exactly
     * equivalent to testing count for 0.
     *
     * @return bool Returns true if the colleciton contains no items.
     */
    public function isEmpty();

    /**
     * Apply an arbitrary callback to each element of the collection, returning the
     * same structure with the return values in place
     *
     * @param Callable $callback The callback to apply: func($value, $key)
     *
     * @return Collection the same structure, with values replaced
     */
    public function map($callback);

    /**
     * Apply an arbitrary callback to each element of the collection, returning the
     * same structure with only items the callback returns true.
     *
     * @param Callable $callback The callback to apply: func($value, $key)
     *
     * @return Collection The same structure, with filtered values only
     */
    public function filter($callback);
}
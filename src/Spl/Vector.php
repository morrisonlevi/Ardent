<?php

namespace Spl;

use ArrayAccess,
    Traversable;

/**
 * Vector is the object-oriented version of a PHP array. As with the other structures in this library, it is designed
 * to hold and work on objects.
 *
 * For indices, I did not specifically require an Integer object because there currently isn't one.  I could define one,
 * but unless we get auto-boxing for the class then the hassle of instantiating integers is not worth the benefit of
 * requiring a class. I decided to just throw exceptions instead, as this should not happen anyway.
 */
interface Vector extends ArrayAccess, Collection {

    /**
     * Adds the object to the end of the vector (the last iterated value).
     * @abstract
     * @param Comparable $value
     * @return void
     */
    function add(Comparable $value);

    /**
     * Adds the object to the end of the vector (the last iterated value).
     * @abstract
     * @param int $index
     * @return void
     * @throws \InvalidArgumentException when $index is not an integer
     * @throws OutOfBoundsException when $index < 0 or $index >= count($this)
     */
    function get($index);

    /**
     * @abstract
     * @param int $index
     * @param Comparable $value
     * @return void
     * @throws \InvalidArgumentException when $index is not an integer
     * @throws OutOfBoundsException when $index < 0 or $index >= count($this)
     */
    function set($index, Comparable $value);

    /**
     * @abstract
     * @param int $index
     * @return void
     * @throws \InvalidArgumentException when $index is not an integer
     */
    function remove($index);

    /**
     * @abstract
     * @param Comparable $object
     * @return Comparable
     */
    function removeObject(Comparable $object);

    /**
     * @abstract
     * @param Traversable $objects
     * @return mixed
     * @throws \Exception when the Traversable does not include an item of type Comparable.
     */
    function removeAll(Traversable $objects);

    /**
     * @abstract
     * @param Traversable $objects
     * @return mixed
     * @throws \Exception when the Traversable does not include an item of type Comparable.
     */
    function retainAll(Traversable $objects);

    /**
     * @abstract
     * @param int $startIndex
     * @param int|null $numberOfItemsToRemove [optional]
     * @return Vector
     * @throws \InvalidArgumentException when $startIndex is not an integer
     */
    function slice($startIndex, $numberOfItemsToRemove = NULL);

    /**
     * @abstract
     * @return array
     */
    function toArray();

}

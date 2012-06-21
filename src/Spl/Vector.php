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
     * @abstract
     * @param  $value
     * @return void
     */
    function append($value);

    /**
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
     * @param $value
     * @return void
     * @throws \InvalidArgumentException when $index is not an integer
     * @throws OutOfBoundsException when $index < 0 or $index >= count($this)
     */
    function set($index, $value);

    /**
     * @abstract
     * @param int $index
     * @return void
     * @throws \InvalidArgumentException when $index is not an integer
     */
    function remove($index);

    /**
     * @abstract
     * @param  $object
     * @return
     */
    function removeObject($object);

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

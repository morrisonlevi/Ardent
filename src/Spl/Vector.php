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
     * @param $item
     * @return void
     * @throws InvalidTypeException when $item is not the correct type.
     */
    function append($item);

    /**
     * @abstract
     * @param int $index
     * @return void
     * @throws InvalidTypeException when $index is not an integer.
     * @throws OutOfBoundsException when $index < 0 or $index >= count($this).
     */
    function get($index);

    /**
     * @abstract
     * @param int $index
     * @param $item
     * @return void
     * @throws InvalidTypeException when $index is not an integer or when $item is not the correct type.
     * @throws OutOfBoundsException when $index < 0 or $index >= count($this).
     */
    function set($index, $item);

    /**
     * @abstract
     * @param int $index
     * @return void
     * @throws InvalidTypeException when $index is not an integer.
     */
    function remove($index);

    /**
     * @abstract
     * @param  $object
     * @return mixed
     * @throws InvalidTypeException when $object is not the correct type.
     */
    function removeObject($object);

    /**
     * @abstract
     * @param Traversable $objects
     * @return mixed
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
     */
    function removeAll(Traversable $objects);

    /**
     * @abstract
     * @param Traversable $objects
     * @return mixed
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
     */
    function retainAll(Traversable $objects);

    /**
     * @abstract
     * @param int $startIndex
     * @param int $numberOfItemsToRemove [optional] If not provided, it will remove all items after the $startIndex.
     * @return Vector
     * @throws InvalidTypeException when $startIndex or $numberOfItemsToRemove are not integers.
     */
    function slice($startIndex, $numberOfItemsToRemove = NULL);

    /**
     * @abstract
     * @return array
     */
    function toArray();

}

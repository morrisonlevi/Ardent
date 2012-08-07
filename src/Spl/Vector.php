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
     * @param $item
     *
     * @return void
     * @throws TypeException when $item is not the correct type.
     */
    function append($item);

    /**
     * @param int $index
     *
     * @return mixed
     * @throws TypeException when $index is not an integer.
     * @throws OutOfBoundsException when $index < 0 or $index >= count($this).
     */
    function get($index);

    /**
     * @param int $index
     * @param $item
     *
     * @return void
     * @throws TypeException when $index is not an integer or when $item is not the correct type.
     * @throws OutOfBoundsException when $index < 0 or $index >= count($this).
     */
    function set($index, $item);

    /**
     * @param int $index
     *
     * @return void
     * @throws TypeException when $index is not an integer.
     */
    function remove($index);

    /**
     * @param  $object
     *
     * @return void
     * @throws TypeException when $object is not the correct type.
     */
    function removeObject($object);

    /**
     * @param Traversable $objects
     *
     * @return void
     * @throws TypeException when the Traversable includes an item with an incorrect type.
     */
    function removeAll(Traversable $objects);

    /**
     * @param int $startIndex
     * @param int $numberOfItemsToExtract [optional] If not provided, it will remove all items after the $startIndex.
     *
     * @return Vector
     * @throws TypeException when $startIndex or $numberOfItemsToExtract are not integers.
     */
    function slice($startIndex, $numberOfItemsToExtract = NULL);

    /**
     * @return array
     */
    function toArray();

}

<?php

namespace Spl;

use ArrayAccess,
    Traversable;

interface Vector extends ArrayAccess, Collection {

    /**
     * @abstract
     * @param Comparable $value
     * @return void
     */
    function add(Comparable $value);

    /**
     * @abstract
     * @param int $index
     * @param Comparable $value
     * @return void
     */
    function set($index, Comparable $value);

    /**
     * @abstract
     * @param int $index
     * @return void
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
     */
    function slice($startIndex, $numberOfItemsToRemove = NULL);

}

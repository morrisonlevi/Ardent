<?php

namespace Spl;

use ArrayAccess,
    Traversable;

interface Map extends ArrayAccess, Collection {

    /**
     * @abstract
     * @param $key
     * @return mixed
     * @throws InvalidTypeException when the $key is not the correct type.
     */
    function get($key);

    /**
     * @abstract
     * @param $key
     * @param mixed $value
     * @return void
     * @throws InvalidTypeException when the $key or value is not the correct type.
     */
    function insert($key, $value);

    /**
     * @abstract
     * @param Map $items
     * @return void
     * @throws InvalidTypeException when the Map does not include an item of the correct type.
     */
    function insertAll(Map $items);

    /**
     * @abstract
     * @param  $key
     * @return mixed
     * @throws InvalidTypeException when the $key is not the correct type.
     */
    function remove($key);

    /**
     * @abstract
     * @param Traversable $keys
     * @return mixed
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
     */
    function removeAll(Traversable $keys);

    /**
     * @abstract
     * @param Traversable $keys
     * @return mixed
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
     */
    function retainAll(Traversable $keys);

}

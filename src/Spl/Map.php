<?php

namespace Spl;

use ArrayAccess,
    Traversable;

interface Map extends ArrayAccess, Collection {

    /**
     * @param $key
     *
     * @return mixed
     * @throws TypeException when the $key is not the correct type.
     */
    function get($key);

    /**
     * @param $key
     * @param mixed $value
     *
     * @return void
     * @throws TypeException when the $key or value is not the correct type.
     */
    function insert($key, $value);

    /**
     * @param Map $items
     *
     * @return void
     * @throws TypeException when the Map does not include an item of the correct type.
     */
    function insertAll(Map $items);

    /**
     * @param $key
     *
     * @return mixed
     * @throws TypeException when the $key is not the correct type.
     */
    function remove($key);

    /**
     * @param Traversable $keys
     *
     * @return mixed
     * @throws TypeException when the Traversable does not include an item of the correct type.
     */
    function removeAll(Traversable $keys);

    /**
     * @param Traversable $keys
     *
     * @return mixed
     * @throws TypeException when the Traversable does not include an item of the correct type.
     */
    function retainAll(Traversable $keys);

}

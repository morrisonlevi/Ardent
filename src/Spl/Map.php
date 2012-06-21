<?php

namespace Spl;

use ArrayAccess,
    Traversable;

interface Map extends ArrayAccess, Collection {

    /**
     * @abstract
     * @param $key
     * @return mixed
     */
    function get($key);

    /**
     * @abstract
     * @param $key
     * @param mixed $value
     * @return void
     */
    function insert($key, $value);

    /**
     * @abstract
     * @param Map $items
     * @return void
     */
    function insertAll(Map $items);

    /**
     * @abstract
     * @param  $key
     * @return mixed
     */
    function remove( $key);

    /**
     * @abstract
     * @param Traversable $keys
     * @return mixed
     * @throws \Exception
     */
    function removeAll(Traversable $keys);

    /**
     * @abstract
     * @param Traversable $keys
     * @return mixed
     * @throws \Exception
     */
    function retainAll(Traversable $keys);

}

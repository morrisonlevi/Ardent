<?php

namespace Spl;

use ArrayAccess,
    Traversable;

interface Map extends ArrayAccess, Collection {

    /**
     * @abstract
     * @param Comparable $key
     * @param mixed $value
     * @return void
     */
    function insert(Comparable $key, $value);

    /**
     * @abstract
     * @param Map $items
     * @return void
     */
    function insertAll(Map $items);

    /**
     * @abstract
     * @param Comparable $key
     * @return mixed
     */
    function remove(Comparable $key);

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

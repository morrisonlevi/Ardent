<?php

namespace Ardent;

use Ardent\Exception\KeyException;
use Ardent\Exception\TypeException;
use ArrayAccess;

interface Map extends ArrayAccess, \IteratorAggregate, Collection {

    /**
     * @param $key
     *
     * @return mixed
     * @throws KeyException when the $key does not exist.
     * @throws TypeException when the $key is not the correct type.
     */
    function get($key);

    /**
     * Note that if the key is considered equal to an already existing key in
     * the map that it's value will be replaced with the new one.
     *
     * @param $key
     * @param mixed $value
     *
     * @return void
     * @throws TypeException when the $key or $value is not the correct type.
     */
    function insert($key, $value);

    /**
     * @param $key
     *
     * @return mixed
     * @throws TypeException when the $key is not the correct type.
     */
    function remove($key);

}

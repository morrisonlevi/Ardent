<?php

namespace Collections;

interface Map extends \ArrayAccess, \Countable, Enumerable {

    /**
     * @param $key
     *
     * @return mixed
     * @throws KeyException when the $key does not exist.
     */
    function get($key);

    /**
     * Store a value into the Map with the specified key, overwriting a previous value if already present.
     *
     * @param $key
     * @param mixed $value
     *
     * @return void
     */
    function set($key, $value);

    /**
     * @param $key
     *
     * @return mixed
     */
    function remove($key);

}

<?php

namespace Collections;

interface Collection extends \Traversable {

    /**
     * @return bool
     */
    function isEmpty();

    /**
     * @param callable $map
     * @return Collection
     */
    function map(callable $map);

    /**
     * @param callable $filter
     * @return Collection
     */
    function filter(callable $filter);

    /**
     * @param int $n
     * @return Collection
     */
    function limit($n);

    /**
     * @param $initialValue
     * @param callable $combine
     * @return mixed
     */
    function reduce($initialValue, callable $combine);

    /**
     * @param int $n
     * @return Collection
     */
    function skip($n);

    /**
     * @param int $start
     * @param int $count
     * @return Collection
     */
    function slice($start, $count);

    /**
     * Note that if you attempt to use keys that are not valid as a PHP array key then you will get errors.
     * @return array
     */
    function toArray();

    /**
     * @return Collection containing the keys as the values
     */
    function keys();

    /**
     * @return Collection containing the values but ignores keys
     */
    function values();

} 
<?php

namespace Collections;

interface Collection extends \Traversable {

    /**
     * @return bool
     */
    function isEmpty();

    /**
     * @param callable $callback
     */
    function each(callable $callback);

    /**
     * @param callable $f
     * @return bool
     */
    function every(callable $f);

    /**
     * @param callable $map
     * @return Enumerator
     */
    function map(callable $map);

    /**
     * @param callable $filter
     * @return Enumerator
     */
    function filter(callable $filter);

    /**
     * @param callable $compare
     * @return bool
     */
    function any(callable $compare);

    /**
     * @param string $separator
     * @return string
     */
    function join($separator);

    /**
     * @param int $n
     * @return Enumerator
     */
    function limit($n);

    /**
     * @param callable $compare
     * @return mixed
     */
    function max(callable $compare = NULL);

    /**
     * @param callable $compare
     * @return mixed
     */
    function min(callable $compare = NULL);

    /**
     * @param callable $f
     * @return bool
     */
    function none(callable $f);

    /**
     * @param $initialValue
     * @param callable $combine
     * @return mixed
     */
    function reduce($initialValue, callable $combine);

    /**
     * @param int $n
     * @return Enumerator
     */
    function skip($n);

    /**
     * @param int $start
     * @param int $count
     * @return SlicingIterator
     */
    function slice($start, $count);

    /**
     * @param bool $preserveKeys
     * @return array
     */
    function toArray($preserveKeys = FALSE);

} 
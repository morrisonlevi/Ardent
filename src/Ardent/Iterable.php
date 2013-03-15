<?php

namespace Ardent;


interface Iterable extends \Countable, \Iterator {

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
     * @return Iterable
     */
    function map(callable $map);

    /**
     * @param callable $filter
     * @return Iterable
     */
    function where(callable $filter);

    /**
     * @param callable $compare
     * @return bool
     */
    function contains(callable $compare);

    /**
     * @param string $separator
     * @return string
     */
    function join($separator);

    /**
     * @param int $n
     * @return Iterable
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
     * @return Iterable
     */
    function skip($n);

    /**
     * @param int $start
     * @param int $count
     * @return SlicingIterable
     */
    function slice($start, $count);

    /**
     * @param bool $preserveKeys
     * @return array
     */
    function toArray($preserveKeys = FALSE);

}
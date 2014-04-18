<?php

namespace Collections;

interface Collection extends \Traversable {

    /**
     * @return bool
     */
    function isEmpty();

    function map(callable $map): Collection;

    function filter(callable $filter): Collection;

    /**
     * @param int $n
     * @return Collection
     */
    function limit($n): Collection;

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
    function skip($n): Collection;

    /**
     * @param int $start
     * @param int $count
     * @return Collection
     */
    function slice($start, $count): Collection;


    function toArray(): array;

    /**
     * @return Collection containing the keys as the values
     */
    function keys(): Collection;

    /**
     * @return Collection containing the values but ignores keys
     */
    function values(): Collection;

} 

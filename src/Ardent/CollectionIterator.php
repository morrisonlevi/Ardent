<?php

namespace Ardent;

trait CollectionIterator /* implements Collection */ {

    /**
     * @param callable $callback
     * @return Collection
     */
    function each(callable $callback) {
        return (new IteratorIterable($this))->each($callback);
    }

    /**
     * @param callable $f
     * @return bool
     */
    function every(callable $f) {
        return (new IteratorIterable($this))->every($f);
    }

    /**
     * @param callable $map
     * @return Collection
     */
    function map(callable $map) {
        return (new IteratorIterable($this))->map($map);
    }

    /**
     * @param callable $filter
     * @return Collection
     */
    function where(callable $filter) {
        return (new IteratorIterable($this))->where($filter);
    }

    /**
     * @param callable $compare
     * @return bool
     */
    function contains(callable $compare) {
        return (new IteratorIterable($this))->contains($compare);
    }

    /**
     * @param string $separator
     * @return string
     */
    function join($separator) {
        return (new IteratorIterable($this))->join($separator);
    }

    /**
     * @param int $n
     * @return Collection
     */
    function limit($n) {
        return (new IteratorIterable($this))->limit($n);
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function max(callable $compare = NULL) {
        return (new IteratorIterable($this))->max($compare);
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function min(callable $compare = NULL) {
        return (new IteratorIterable($this))->min($compare);
    }

    /**
     * @param callable $f
     * @return bool
     */
    function none(callable $f) {
        return (new IteratorIterable($this))->none($f);
    }

    /**
     * @param $initialValue
     * @param callable $combine
     * @return mixed
     */
    function reduce($initialValue, callable $combine) {
        return (new IteratorIterable($this))
            ->reduce($initialValue, $combine);
    }

    /**
     * @param int $n
     * @return Collection
     */
    function skip($n) {
        return (new IteratorIterable($this))->skip($n);
    }

    /**
     * @param int $start
     * @param int $count
     * @return Collection
     */
    function slice($start, $count) {
        return (new IteratorIterable($this))
            ->slice($start, $count);
    }

    /**
     * @param bool $preserveKeys
     * @return array
     */
    function toArray($preserveKeys = FALSE) {
        return iterator_to_array($this, $preserveKeys);
    }

    function isEmpty() {
        return $this->count() === 0;
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return iterator_count($this);
    }

}
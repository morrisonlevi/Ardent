<?php

namespace Ardent;

trait CollectionStructure /* implements Collection */ {

    /**
     * @return \Iterator
     */
    abstract function getIterator();

    /**
     * @param callable $callback
     * @return Collection
     */
    function each(callable $callback) {
        return (new IteratorIterable($this->getIterator()))->each($callback);
    }

    /**
     * @param callable $f
     * @return bool
     */
    function every(callable $f) {
        return (new IteratorIterable($this->getIterator()))->every($f);
    }

    /**
     * @param callable $map
     * @return Collection
     */
    function map(callable $map) {
        return (new IteratorIterable($this->getIterator()))->map($map);
    }

    /**
     * @param callable $filter
     * @return Collection
     */
    function where(callable $filter) {
        return (new IteratorIterable($this->getIterator()))->where($filter);
    }

    /**
     * @param callable $compare
     * @return bool
     */
    function contains(callable $compare) {
        return (new IteratorIterable($this->getIterator()))->contains($compare);
    }

    /**
     * @param string $separator
     * @return string
     */
    function join($separator) {
        return (new IteratorIterable($this->getIterator()))->join($separator);
    }

    /**
     * @param int $n
     * @return Collection
     */
    function limit($n) {
        return (new IteratorIterable($this->getIterator()))->limit($n);
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function max(callable $compare = NULL) {
        return (new IteratorIterable($this->getIterator()))->max($compare);
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function min(callable $compare = NULL) {
        return (new IteratorIterable($this->getIterator()))->min($compare);
    }

    /**
     * @param callable $f
     * @return bool
     */
    function none(callable $f) {
        return (new IteratorIterable($this->getIterator()))->none($f);
    }

    /**
     * @param $initialValue
     * @param callable $combine
     * @return mixed
     */
    function reduce($initialValue, callable $combine) {
        return (new IteratorIterable($this->getIterator()))
            ->reduce($initialValue, $combine);
    }

    /**
     * @param int $n
     * @return Collection
     */
    function skip($n) {
        return (new IteratorIterable($this->getIterator()))->skip($n);
    }

    /**
     * @param int $start
     * @param int $count
     * @return Collection
     */
    function slice($start, $count) {
        return (new IteratorIterable($this->getIterator()))
            ->slice($start, $count);
    }

    /**
     * @param bool $preserveKeys
     * @return array
     */
    function toArray($preserveKeys = FALSE) {
        return iterator_to_array($this->getIterator(), $preserveKeys);
    }
}
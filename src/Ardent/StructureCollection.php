<?php

namespace Ardent;

use Ardent\Iterator\IteratorToCollectionAdapter;

trait StructureCollection /* implements Collection */ {

    /**
     * @return \Iterator
     */
    abstract function getIterator();

    /**
     * @param callable $callback
     * @return void
     */
    function each(callable $callback) {
        (new IteratorToCollectionAdapter($this->getIterator()))->each($callback);
    }

    /**
     * @param callable $f
     * @return bool
     */
    function every(callable $f) {
        return (new IteratorToCollectionAdapter($this->getIterator()))->every($f);
    }

    /**
     * @param callable $map
     * @return Collection
     */
    function map(callable $map) {
        return (new IteratorToCollectionAdapter($this->getIterator()))->map($map);
    }

    /**
     * @param callable $filter
     * @return Collection
     */
    function filter(callable $filter) {
        return (new IteratorToCollectionAdapter($this->getIterator()))->filter($filter);
    }

    /**
     * @param callable $compare
     * @return bool
     */
    function any(callable $compare) {
        return (new IteratorToCollectionAdapter($this->getIterator()))->any($compare);
    }

    /**
     * @param string $separator
     * @return string
     */
    function join($separator) {
        return (new IteratorToCollectionAdapter($this->getIterator()))->join($separator);
    }

    /**
     * @param int $n
     * @return Collection
     */
    function limit($n) {
        return (new IteratorToCollectionAdapter($this->getIterator()))->limit($n);
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function max(callable $compare = NULL) {
        return (new IteratorToCollectionAdapter($this->getIterator()))->max($compare);
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function min(callable $compare = NULL) {
        return (new IteratorToCollectionAdapter($this->getIterator()))->min($compare);
    }

    /**
     * @param callable $f
     * @return bool
     */
    function none(callable $f) {
        return (new IteratorToCollectionAdapter($this->getIterator()))->none($f);
    }

    /**
     * @param $initialValue
     * @param callable $combine
     * @return mixed
     */
    function reduce($initialValue, callable $combine) {
        return (new IteratorToCollectionAdapter($this->getIterator()))
            ->reduce($initialValue, $combine);
    }

    /**
     * @param int $n
     * @return Collection
     */
    function skip($n) {
        return (new IteratorToCollectionAdapter($this->getIterator()))->skip($n);
    }

    /**
     * @param int $start
     * @param int $count
     * @return Collection
     */
    function slice($start, $count) {
        return (new IteratorToCollectionAdapter($this->getIterator()))
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
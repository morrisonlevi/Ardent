<?php

namespace Collections;

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
        (new IteratorCollectionAdapter($this->getIterator()))->each($callback);
    }

    /**
     * @param callable $f
     * @return bool
     */
    function every(callable $f) {
        return (new IteratorCollectionAdapter($this->getIterator()))->every($f);
    }

    /**
     * @param callable $map
     * @return Enumerator
     */
    function map(callable $map) {
        return new MappingIterator($this->getIterator(), $map);
    }

    /**
     * @param callable $filter
     * @return Enumerator
     */
    function filter(callable $filter) {
        return new FilteringIterator($this->getIterator(), $filter);
    }

    /**
     * @param callable $compare
     * @return bool
     */
    function any(callable $compare) {
        return (new IteratorCollectionAdapter($this->getIterator()))->any($compare);
    }

    /**
     * @param string $separator
     * @return string
     */
    function join($separator) {
        return (new IteratorCollectionAdapter($this->getIterator()))->join($separator);
    }

    /**
     * @param int $n
     * @return Enumerator
     */
    function limit($n) {
        return new LimitingIterator($this->getIterator(), $n);
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function max(callable $compare = NULL) {
        return (new IteratorCollectionAdapter($this->getIterator()))->max($compare);
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function min(callable $compare = NULL) {
        return (new IteratorCollectionAdapter($this->getIterator()))->min($compare);
    }

    /**
     * @param callable $f
     * @return bool
     */
    function none(callable $f) {
        return (new IteratorCollectionAdapter($this->getIterator()))->none($f);
    }

    /**
     * @param $initialValue
     * @param callable $combine
     * @return mixed
     */
    function reduce($initialValue, callable $combine) {
        return (new IteratorCollectionAdapter($this->getIterator()))
            ->reduce($initialValue, $combine);
    }

    /**
     * @param int $n
     * @return Enumerator
     */
    function skip($n) {
        return new SkippingIterator($this->getIterator(), $n);
    }

    /**
     * @param int $start
     * @param int $count
     * @return Enumerator
     */
    function slice($start, $count) {
        return new SlicingIterator($this->getIterator(), $start, $count);
    }

    /**
     * @return array
     */
    function toArray() {
        return iterator_to_array($this->getIterator());
    }

    function keys() {
        return new KeyIterator($this->getIterator());
    }

    function values() {
        return new ValueIterator($this->getIterator());
    }
}
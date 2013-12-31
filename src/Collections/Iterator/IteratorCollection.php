<?php

namespace Collections;

trait IteratorCollection /* implements \Collections\Collection */ {

    /**
     * @param callable $callback
     */
    function each(callable $callback) {
        (new IteratorToCollectionAdapter($this))->each($callback);
    }

    /**
     * @param callable $f
     * @return bool
     */
    function every(callable $f) {
        return (new IteratorToCollectionAdapter($this))->every($f);
    }

    /**
     * @param callable $map
     * @return Enumerator
     */
    function map(callable $map) {
        return (new IteratorToCollectionAdapter($this))->map($map);
    }

    /**
     * @param callable $filter
     * @return Enumerator
     */
    function filter(callable $filter) {
        return (new IteratorToCollectionAdapter($this))->filter($filter);
    }

    /**
     * @param callable $compare
     * @return bool
     */
    function any(callable $compare) {
        return (new IteratorToCollectionAdapter($this))->any($compare);
    }

    /**
     * @param string $separator
     * @return string
     */
    function join($separator) {
        return (new IteratorToCollectionAdapter($this))->join($separator);
    }

    /**
     * @param int $n
     * @return Enumerator
     */
    function limit($n) {
        return (new IteratorToCollectionAdapter($this))->limit($n);
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function max(callable $compare = NULL) {
        return (new IteratorToCollectionAdapter($this))->max($compare);
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function min(callable $compare = NULL) {
        return (new IteratorToCollectionAdapter($this))->min($compare);
    }

    /**
     * @param callable $f
     * @return bool
     */
    function none(callable $f) {
        return (new IteratorToCollectionAdapter($this))->none($f);
    }

    /**
     * @param $initialValue
     * @param callable $combine
     * @return mixed
     */
    function reduce($initialValue, callable $combine) {
        return (new IteratorToCollectionAdapter($this))
            ->reduce($initialValue, $combine);
    }

    /**
     * @param int $n
     * @return Enumerator
     */
    function skip($n) {
        return (new IteratorToCollectionAdapter($this))->skip($n);
    }

    /**
     * @param int $start
     * @param int $count
     * @return Enumerator
     */
    function slice($start, $count) {
        return (new IteratorToCollectionAdapter($this))
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
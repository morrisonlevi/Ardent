<?php

namespace Ardent\Iterator;

use Ardent\Collection;

class IteratorToCollectionAdapter implements CountableIterator, Collection {

    use IteratorCollection;

    /**
     * @var \Iterator
     */
    protected $inner;

    function __construct(\Iterator $Iterator) {
        $this->inner = $Iterator;
    }

    function isEmpty() {
        return $this->count() === 0;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->inner->current();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->inner->next();
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        return $this->inner->key();
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->inner->valid();
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->inner->rewind();
    }

    /**
     * @param callable $callback
     */
    function each(callable $callback) {
        for ($this->rewind(); $this->valid(); $this->next()) {
            $callback($this->current(), $this->key());
        }
    }

    /**
     * @param callable $f
     * @return bool
     */
    function every(callable $f) {
        for ($this->rewind(); $this->valid(); $this->next()) {
            if (!$f($this->current(), $this->key())) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * @param callable $map
     * @return Collection
     */
    function map(callable $map) {
        return new MappingIterator($this, $map);
    }

    /**
     * @param callable $filter
     * @return Collection
     */
    function where(callable $filter) {
        return new FilteringIterator($this, $filter);
    }

    /**
     * @param callable $compare
     * @return bool
     */
    function contains(callable $compare) {
        foreach ($this as $value) {
            if ($compare($value)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * @param string $separator
     * @return string
     */
    function join($separator) {
        $buffer = '';
        $i = 0;
        foreach ($this as $value) {
            if ($i++ > 0) {
                $buffer .= $separator;
            }
            $buffer .= $value;
        }
        return $buffer;
    }

    /**
     * @param int $n
     * @return Collection
     */
    function limit($n) {
        return new LimitingIterator($this, $n);
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function max(callable $compare = NULL) {
        $this->rewind();
        if (!$this->valid()) {
            return NULL;
        }
        $compare = $compare ?: 'max';
        $max = $this->current();
        for ($this->next(); $this->valid(); $this->next()) {
            $max = $compare($max, $this->current());
        }
        return $max;
    }

    /**
     * @param callable $compare
     * @return mixed
     */
    function min(callable $compare = NULL) {
        $this->rewind();
        if (!$this->valid()) {
            return NULL;
        }
        $compare = $compare ?: 'min';
        $min = $this->current();
        for ($this->next(); $this->valid(); $this->next()) {
            $min = $compare($min, $this->current());
        }
        return $min;
    }

    /**
     * @param callable $f
     * @return bool
     */
    function none(callable $f) {
        for ($this->rewind(); $this->valid(); $this->next()) {
            if ($f($this->current(), $this->key())) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * @param $initialValue
     * @param callable $combine
     * @return mixed
     */
    function reduce($initialValue, callable $combine) {
        $carry = $initialValue;
        for ($this->rewind(); $this->valid(); $this->next()) {
            $carry = $combine($carry, $this->current());
        }
        return $carry;
    }

    /**
     * @param int $n
     * @return Collection
     */
    function skip($n) {
        return new SkippingIterator($this, $n);
    }

    /**
     * @param int $start
     * @param int $count
     * @return Collection
     */
    function slice($start, $count) {
        return new SlicingIterator($this, $start, $count);
    }

    /**
     * @param bool $preserveKeys
     * @return array
     */
    function toArray($preserveKeys = FALSE) {
        return iterator_to_array($this, $preserveKeys);
    }

    function count() {
        return $this->inner instanceof \Countable
            ? $this->inner->count()
            : iterator_count($this);
    }

}
<?php

namespace Collections;

/**
 * @implements \Collections\Enumerator
 */
trait IteratorCollection /* implements \Collections\Enumerator */ {

    /**
     * @return bool
     */
    function isEmpty() {
        if ($this instanceof \Countable) {
            return $this->count() == 0;
        }
        /** @var \Iterator $this */
        $this->rewind();
        return !$this->valid();
    }

    /**
     * @param callable $callback
     */
    function each(callable $callback) {
        /** @var \Iterator $this */
        for ($this->rewind(); $this->valid(); $this->next()) {
            $callback($this->current(), $this->key());
        }
    }

    /**
     * @param callable $f
     * @return bool
     */
    function every(callable $f) {
        /** @var \Iterator $this */
        for ($this->rewind(); $this->valid(); $this->next()) {
            if (!$f($this->current(), $this->key())) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * @param callable $map
     * @return Enumerator
     */
    function map(callable $map) {
        return new MappingIterator($this, $map);
    }

    /**
     * @param callable $filter
     * @return Enumerator
     */
    function filter(callable $filter) {
        return new FilteringIterator($this, $filter);
    }

    /**
     * @param callable $compare
     * @return bool
     */
    function any(callable $compare) {
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
     * @return Enumerator
     */
    function limit($n) {
        return new LimitingIterator($this, $n);
    }

    /**
     * @param callable $compare
     * @throws StateException
     * @return mixed
     */
    function max(callable $compare = NULL) {
        /** @var \Iterator $this */
        $this->rewind();
        if (!$this->valid()) {
            throw new StateException;
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
     * @throws StateException
     * @return mixed
     */
    function min(callable $compare = NULL) {
        /** @var \Iterator $this */
        $this->rewind();
        if (!$this->valid()) {
            throw new StateException;
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
        /** @var \Iterator $this */
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
        /** @var \Iterator $this */
        $carry = $initialValue;
        for ($this->rewind(); $this->valid(); $this->next()) {
            $carry = $combine($carry, $this->current());
        }
        return $carry;
    }

    /**
     * @param int $n
     * @return Enumerator
     */
    function skip($n) {
        return new SkippingIterator($this, $n);
    }

    /**
     * @param int $start
     * @param int $count
     * @return Enumerator
     */
    function slice($start, $count) {
        return new SlicingIterator($this, $start, $count);
    }

    /**
     * @return array
     */
    function toArray() {
        return iterator_to_array($this);
    }

    function keys() {
        return new KeyIterator($this);
    }

    function values() {
        return new ValueIterator($this);
    }

}
<?php

namespace Ardent;

class ArrayIterable extends \ArrayIterator implements CountableSeekableIterator, Iterable {
    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return parent::current();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        parent::next();
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        return parent::key();
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return parent::valid();
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        parent::rewind();
    }

    /**
     * @param callable $callback
     */
    function each(callable $callback) {
        foreach ($this as $key => $value) {
            $callback($value, $key);
        }
    }

    /**
     * @param callable $f
     * @return bool
     */
    function every(callable $f) {
        foreach ($this as $key => $value) {
            if (!$f($value, $key)) {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * @param callable $map
     * @return Iterable
     */
    function map(callable $map) {
        return new MappingIterable($this, $map);
    }

    /**
     * @param callable $filter
     * @return Iterable
     */
    function where(callable $filter) {
        return new FilteringIterable($this, $filter);
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
     * @return Iterable
     */
    function limit($n) {
        return new LimitingIterable($this, $n);
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
        foreach ($this as $key => $value) {
            if ($f($value, $key)) {
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
     * @return Iterable
     */
    function skip($n) {
        return new SkippingIterable($this, $n);
    }

    /**
     * @param int $start
     * @param int $count
     * @return Iterable
     */
    function slice($start, $count) {
        return new SlicingIterable($this, $start, $count);
    }

    /**
     * @param bool $preserveKeys
     * @return array
     */
    function toArray($preserveKeys = FALSE) {
        return iterator_to_array($this, $preserveKeys);
    }

}

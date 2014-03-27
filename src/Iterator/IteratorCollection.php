<?php

namespace Collections;

trait IteratorCollection /* implements \Collections\Collection */ {

    /**
     * @return \Iterator
     */
    private function asIterator() {
        if ($this instanceof \IteratorAggregate) {
            return $this->getIterator();
        }
        return $this;
    }

    /**
     * @return bool
     */
    function isEmpty() {
        $i = $this->asIterator();
        $i->rewind();
        return !$i->valid();
    }

    /**
     * @param callable $f
     * @return bool
     */
    function every(callable $f) {
        $i = $this->asIterator();
        for ($i->rewind(); $i->valid(); $i->next()) {
            if (!$f($i->current(), $i->key())) {
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
        return new MappingIterator($this->asIterator(), $map);
    }

    /**
     * @param callable $filter
     * @return Enumerator
     */
    function filter(callable $filter) {
        return new FilteringIterator($this->asIterator(), $filter);
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
        return new LimitingIterator($this->asIterator(), $n);
    }

    /**
     * @param callable $compare
     * @throws StateException
     * @return mixed
     */
    function max(callable $compare = NULL) {
        $i = $this->asIterator();
        $i->rewind();
        if (!$i->valid()) {
            throw new StateException;
        }
        $compare = $compare ?: 'max';
        $max = $i->current();
        for ($i->next(); $i->valid(); $i->next()) {
            $max = $compare($max, $i->current());
        }
        return $max;
    }

    /**
     * @param callable $compare
     * @throws StateException
     * @return mixed
     */
    function min(callable $compare = NULL) {
        $i = $this->asIterator();
        $i->rewind();
        if (!$i->valid()) {
            throw new StateException;
        }
        $compare = $compare ?: 'min';
        $min = $i->current();
        for ($i->next(); $i->valid(); $i->next()) {
            $min = $compare($min, $i->current());
        }
        return $min;
    }

    /**
     * @param callable $f
     * @return bool
     */
    function none(callable $f) {
        $i = $this->asIterator();
        for ($i->rewind(); $i->valid(); $i->next()) {
            if ($f($i->current(), $i->key())) {
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
        $i = $this->asIterator();
        $carry = $initialValue;
        for ($i->rewind(); $i->valid(); $i->next()) {
            $carry = $combine($carry, $i->current());
        }
        return $carry;
    }

    /**
     * @param int $n
     * @return Enumerator
     */
    function skip($n) {
        return new SkippingIterator($this->asIterator(), $n);
    }

    /**
     * @param int $start
     * @param int $count
     * @return Enumerator
     */
    function slice($start, $count) {
        return new SlicingIterator($this->asIterator(), $start, $count);
    }

    /**
     * @return array
     */
    function toArray() {
        return iterator_to_array($this->asIterator());
    }

    function keys() {
        return new KeyIterator($this->asIterator());
    }

    function values() {
        return new ValueIterator($this->asIterator());
    }

}
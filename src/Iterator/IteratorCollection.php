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
     * @param int $n
     * @return Enumerator
     */
    function limit($n) {
        return new LimitingIterator($this->asIterator(), $n);
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
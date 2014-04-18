<?php

namespace Collections;

trait IteratorCollection /* implements \Collections\Collection */ {


    private function asIterator(): \Iterator {
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
    function map(callable $map): Enumerator {
        return new MappingIterator($this->asIterator(), $map);
    }


    /**
     * @param callable $filter
     * @return Enumerator
     */
    function filter(callable $filter): Enumerator {
        return new FilteringIterator($this->asIterator(), $filter);
    }


    /**
     * @param int $n
     * @return Enumerator
     */
    function limit($n): Enumerator {
        return new LimitingIterator($this->asIterator(), $n);
    }

    /**
     * @param $initialValue
     * @param callable $combine
     * @return mixed
     */
    function reduce($initialValue, callable $combine) {
        $carry = $initialValue;
        foreach ($this->asIterator() as $value) {
            $carry = $combine($carry, $value);
        }
        return $carry;
    }

    /**
     * @param int $n
     * @return Enumerator
     */
    function skip($n): Enumerator {
        return new SkippingIterator($this->asIterator(), $n);
    }

    /**
     * @param int $start
     * @param int $count
     * @return Enumerator
     */
    function slice($start, $count): Enumerator {
        return new SlicingIterator($this->asIterator(), $start, $count);
    }


    function toArray(): array {
        return iterator_to_array($this->asIterator());
    }


    function keys(): Enumerator {
        return new KeyIterator($this->asIterator());
    }


    function values(): Enumerator {
        return new ValueIterator($this->asIterator());
    }

}
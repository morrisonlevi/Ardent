<?php

namespace Ardent\Collection;

class LinkedStack implements Stack {

    /**
     * @var Pair
     */
    private $top;

    private $size = 0;


    /**
     * @return bool
     */
    function isEmpty() {
        return $this->top === null;
    }


    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return StackIterator
     */
    function getIterator() {
        return new LinkedStackIterator($this->size, $this->top);
    }


    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->size;
    }


    /**
     * @param mixed $object
     * @return void
     */
    function push($object) {
        $this->top = new Pair($object, $this->top);
        $this->size++;
    }


    /**
     * @return mixed
     */
    function pop() {
        assert(!$this->isEmpty());

        list($value, $this->top) = [$this->top->first, $this->top->second];
        $this->size--;

        return $value;
    }


    /**
     * @return mixed
     */
    function last() {
        assert(!$this->isEmpty());
        return $this->top->first;
    }


    function clear() {
        $this->top = null;
        $this->size = 0;
    }


    /**
     * @return array
     */
    function toArray() {
        $a = [];
        $current = $this->top;
        while ($current !== null) {
            $a[] = $current->first;
            $current = $current->second;
        }
        return $a;
    }


}

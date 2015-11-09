<?php

namespace Ardent\Collection;

class LinkedStackIterator implements StackIterator {


    /**
     * @var int
     */
    private $size = 0;

    /**
     * @var int
     */
    private $key = null;

    /**
     * @var Pair
     */
    private $pair;

    /**
     * @var Pair
     */
    private $top;


    /**
     * @param int $count
     * @param Pair $top
     */
    function __construct($count, Pair $top = null) {
        $this->pair = $this->top = $top;
        $this->size = $count;
    }


    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->pair = $this->top;
        if ($this->pair !== null) {
            $this->key = 0;
        }
    }


    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool
     */
    function valid() {
        return $this->pair !== null;
    }


    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        return $this->key;
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->pair->first;
    }


    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->pair = $this->pair->second;
        $this->key++;
    }


    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->size;
    }


    /**
     * @return bool
     */
    function isEmpty() {
        return $this->count() === 0;
    }


}

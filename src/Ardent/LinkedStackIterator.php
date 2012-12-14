<?php

namespace Ardent;

class LinkedStackIterator implements StackIterator {

    /**
     * @var LinkedList
     */
    private $list;

    /**
     * @var int
     */
    private $key = NULL;

    function __construct(LinkedList $list) {
        $this->list = $list;
        $this->rewind();
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->list->end();

        if ($this->list->count() > 0 ) {
            $this->key = 0;
        }
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->list->valid();
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
        return $this->list->current();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->list->prev();
        $this->key = $this->list->key() === NULL
            ? NULL
            : $this->key + 1;
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->list->count();
    }

}

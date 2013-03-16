<?php

namespace Ardent\Iterator;

use Ardent\Collection;
use Ardent\CollectionIterator;
use Ardent\LinkedList;

class LinkedListIterator implements CountableSeekableIterator, Collection {

    use CollectionIterator;

    /**
     * @var LinkedList
     */
    private $list;

    function __construct(LinkedList $list) {
        $this->list = $list;
        $this->rewind();
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
        $this->list->next();
    }

    /**
     * @return void
     */
    function prev() {
        $this->list->prev();
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        return $this->list->key();
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->list->valid();
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->list->rewind();
    }

    /**
     * @return void
     */
    function end() {
        $this->list->end();
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->list->count();
    }

    /**
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @param int $position
     * @return void
     */
    function seek($position) {
        $this->list->seek($position);
    }


}

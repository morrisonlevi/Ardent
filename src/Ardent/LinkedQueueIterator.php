<?php

namespace Ardent;

class LinkedQueueIterator implements QueueIterator {

    /**
     * @var LinkedList
     */
    private $list;
    
    function __construct(LinkedList $list) {
        $this->list = $list;
        $this->list->rewind();
    }
    
    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
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
     * @link http://php.net/manual/en/countable.count.php
     * @return int 
     */
    function count() {
        return $this->list->count();
    }

}

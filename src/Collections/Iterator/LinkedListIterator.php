<?php

namespace Collections;

class LinkedListIterator extends IteratorCollectionAdapter implements CountableSeekableIterator, Enumerator {

    function __construct(LinkedList $list) {
        parent::__construct($list);
        $this->rewind();
    }

    /**
     * @return void
     */
    function prev() {
        $this->getInnerIterator()->prev();
    }

    /**
     * @return void
     */
    function end() {
        $this->getInnerIterator()->end();
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->getInnerIterator()->count();
    }

    /**
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @param int $position
     * @return void
     */
    function seek($position) {
        $this->getInnerIterator()->seek($position);
    }

    /**
     * @return LinkedList
     */
    function getInnerIterator() {
        return parent::getInnerIterator();
    }


}

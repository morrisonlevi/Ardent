<?php

namespace Collections;

class KeyIterator extends IteratorCollectionAdapter {

    private $i = 0;

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        parent::rewind();
        $this->i = 0;
    }

    /**
     * Calling `key` when iterator is not in a valid state is undefined.
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        return $this->i;
    }

    /**
     * Calling `current` when iterator is not in a valid state is undefined.
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return parent::key();
    }

    /**
     * Calling `next` when iterator is not in a valid state is harmless.
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        parent::next();
        $this->i++;
    }

}
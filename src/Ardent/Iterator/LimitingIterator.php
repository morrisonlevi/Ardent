<?php

namespace Ardent\Iterator;

class LimitingIterator extends IteratorToCollectionAdapter {

    /**
     * @var int
     */
    private $n;

    private $used = 0;

    /**
     * @param \Iterator $iterator
     * @param int $n
     */
    function __construct(\Iterator $iterator, $n) {
        parent::__construct($iterator);
        $this->n = $n;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->inner->rewind();
        $this->used = 0;
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->inner->next();
        $this->used++;
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->used < $this->n && $this->inner->valid();
    }

    function count() {
        $i = 0;
        for ($this->rewind(); $this->valid(); $this->next()) {
            $i++;
        }
        return $i;
    }

}

<?php

namespace Spl;

class LinkedListIterator implements CountableSeekableIterator {

    private $list;
    private $currentOffset;

    function __construct(LinkedList $list) {
        $this->list = $list;
        $this->rewind();
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    public function current() {
        return $this->list->offsetGet($this->currentOffset);
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    public function next() {
        $this->currentOffset++;

        if ($this->currentOffset < $this->list->count()) {
            $this->list->seek($this->currentOffset);
        }
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    public function key() {
        return $this->currentOffset;
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid() {
        return $this->currentOffset < $this->list->count();
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind() {
        if ($this->list->count() > 0) {
            $this->list->seek($this->currentOffset = 0);
        }
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count() {
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

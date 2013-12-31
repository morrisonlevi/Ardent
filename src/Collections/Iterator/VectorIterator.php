<?php

namespace Collections;

class VectorIterator implements CountableSeekableIterator, Enumerator {

    use IteratorCollection;

    /**
     * @var Vector
     */
    protected $vector;

    /**
     * @var int
     */
    protected $currentKey = 0;

    function __construct(Vector $vector) {
        $this->vector = $vector;
        $this->rewind();
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    function rewind() {
        $this->currentKey = 0;
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->vector->offsetExists($this->currentKey);
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return int
     */
    function key() {
        if (!$this->valid()) {
            return NULL;
        }
        return $this->currentKey;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        if (!$this->valid()) {
            return NULL;
        }
        return $this->vector[$this->currentKey];
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        if (!$this->valid()) {
            return;
        }
        $this->currentKey++;
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->vector->count();
    }

    /**
     * @param int $index
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @return void
     * @throws IndexException if it cannot seek to the position
     */
    function seek($index) {
        if (!$this->vector->offsetExists($index)) {
            throw new IndexException();
        }
        $this->currentKey = $index;
    }

}

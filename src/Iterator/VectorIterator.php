<?php

namespace Collections;

class VectorIterator extends IteratorCollectionAdapter implements CountableSeekableIterator, Enumerator {

    function __construct(Vector $vector) {
        parent::__construct(new \ArrayIterator($vector->toArray()));
        $this->rewind();
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->getInnerIterator()->count();
    }

    /**
     * @param int $index
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @return void
     * @throws IndexException if it cannot seek to the position
     */
    function seek($index) {
        if ($index < 0 || $index >= $this->getInnerIterator()->count()) {
            throw new IndexException;
        }
        $this->getInnerIterator()->seek($index);
    }

    /**
     * @return \ArrayIterator
     */
    function getInnerIterator() {
        return parent::getInnerIterator();
    }

}

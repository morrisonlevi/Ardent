<?php

namespace Collections;

class SkippingIterator extends IteratorCollectionAdapter {

    /**
     * @var int
     */
    private $n;

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

        for ($i = 0; $i < $this->n; $i++) {
            if ($this->inner->valid()) {
                $this->inner->next();
            }
        }
    }

}

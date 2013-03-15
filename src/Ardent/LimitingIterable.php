<?php

namespace Ardent;

class LimitingIterable extends IteratorIterable {

    /**
     * @var int
     */
    private $n;

    private $used = 0;

    /**
     * @param Iterable $iterable
     * @param int $n
     */
    function __construct(Iterable $iterable, $n) {
        parent::__construct($iterable);
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

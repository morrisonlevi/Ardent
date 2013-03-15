<?php

namespace Ardent;

class SlicingIterable extends IteratorIterable {

    private $start;
    private $count;

    private $used = 0;

    function __construct(Iterable $iterable, $start, $count) {
        parent::__construct($iterable);
        $this->start = $start;
        $this->count = $count;
    }

    function next() {
        $this->inner->next();
        $this->used++;
    }

    function valid() {
        return $this->used < $this->count && $this->inner->valid();
    }

    function rewind() {
        $this->inner->rewind();
        $this->used = 0;
        for ($i = 0; $i < $this->start; $i++) {
            $this->inner->next();
        }
    }

    function count() {
        $i = 0;
        for ($this->rewind(); $this->valid(); $this->next()) {
            $i++;
        }
        return $i;
    }

}

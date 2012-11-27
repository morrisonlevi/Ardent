<?php

namespace Spl;

class LinkedNode {

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var LinkedNode
     */
    public $prev;

    /**
     * @var LinkedNode
     */
    public $next;

    function __construct($value) {
        $this->value = $value;
    }

    function __clone() {
        $this->prev = $this->prev !== NULL
            ? clone $this->prev
            : NULL;

        $this->next = $this->next !== NULL
            ? clone $this->next
            : NULL;
    }

}

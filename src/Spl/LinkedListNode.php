<?php

namespace Spl;

class LinkedListNode {

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var LinkedListNode
     */
    public $prev;

    /**
     * @var LinkedListNode
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

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

    public function __construct($value) {
        $this->value = $value;
    }

}

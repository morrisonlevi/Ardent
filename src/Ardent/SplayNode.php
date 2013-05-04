<?php

namespace Ardent;

class SplayNode {

    public $value;

    /**
     * @var SplayNode
     */
    public $parent;

    /**
     * @var SplayNode
     */
    public $left;

    /**
     * @var SplayNode
     */
    public $right;

    function __construct($value, SplayNode $parent = NULL) {
        $this->value = $value;
        $this->parent = $parent;
    }

}
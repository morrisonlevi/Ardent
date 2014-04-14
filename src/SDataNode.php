<?php

namespace Collections;


class SDataNode implements SNode {
    private $prev;
    private $value;
    private $next;


    function __construct($value) {
        $this->value = $value;
    }


    function value() {
        return $this->value;
    }


    function setValue($value) {
        $this->value = $value;
    }


    /**
     * @return SNode
     */
    function prev() {
        return $this->prev;
    }


    /**
     * @return SNode
     */
    function next() {
        return $this->next;
    }


    function setPrev(SNode $prev) {
        $this->prev = $prev;
    }


    function setNext(SNode $next) {
        $this->next = $next;
    }


}
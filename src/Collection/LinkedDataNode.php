<?php

namespace Ardent\Collection;


class LinkedDataNode implements LinkedNode {

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
     * @return LinkedNode
     */
    function prev() {
        return $this->prev;
    }


    /**
     * @return LinkedNode
     */
    function next() {
        return $this->next;
    }


    function setPrev(LinkedNode $prev) {
        $this->prev = $prev;
    }


    function setNext(LinkedNode $next) {
        $this->next = $next;
    }


}

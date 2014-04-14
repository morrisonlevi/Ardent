<?php

namespace Collections;


class STerminalNode implements SNode {

    private $next;
    private $prev;


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
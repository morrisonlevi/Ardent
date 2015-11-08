<?php

namespace Ardent\Collection;


class LinkedTerminalNode implements LinkedNode {

    private $next;
    private $prev;


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

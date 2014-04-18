<?php

namespace Collections;


class LinkedTerminalNode implements LinkedNode {

    private $next;
    private $prev;


    function prev(): LinkedNode {
        return $this->prev;
    }


    function next(): LinkedNode {
        return $this->next;
    }


    function setPrev(LinkedNode $prev) {
        $this->prev = $prev;
    }


    function setNext(LinkedNode $next) {
        $this->next = $next;
    }


}
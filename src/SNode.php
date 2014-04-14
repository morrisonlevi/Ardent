<?php

namespace Collections;


interface SNode {

    /**
     * @return SNode
     */
    function prev();

    /**
     * @return SNode
     */
    function next();

    function setPrev(SNode $prev);

    function setNext(SNode $next);
}

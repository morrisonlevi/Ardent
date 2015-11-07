<?php

namespace Ardent\Collection;


interface LinkedNode {

    /**
     * @return LinkedNode
     */
    function prev();


    /**
     * @return LinkedNode
     */
    function next();


    function setPrev(LinkedNode $prev);


    function setNext(LinkedNode $next);
}

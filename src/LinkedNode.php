<?php

namespace Collections;


interface LinkedNode {

    function prev(): LinkedNode;

    function next(): LinkedNode;

    function setPrev(LinkedNode $prev);

    function setNext(LinkedNode $next);
}

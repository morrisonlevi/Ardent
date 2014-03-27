<?php

namespace Collections;

interface Enumerable extends Collection, \IteratorAggregate {

    /**
     * @return Enumerator
     */
    function getIterator();

} 
<?php

namespace Collections;

use IteratorAggregate;

interface Enumerable extends Countable, IteratorAggregate {

    /**
     * @return Enumerator
     */
    function getIterator();

} 
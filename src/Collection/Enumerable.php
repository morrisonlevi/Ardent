<?php

namespace Ardent\Collection;

use IteratorAggregate;


interface Enumerable extends Collection, IteratorAggregate {

    /**
     * @return Enumerator
     */
    function getIterator();

}

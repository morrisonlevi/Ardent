<?php

namespace Ardent\Collection;


class ArrayIterator extends \ArrayIterator implements Enumerator {


    function __construct(Array $array) {
        parent::__construct($array);
    }


    /**
     * @return Bool
     */
    function isEmpty() {
        return parent::count() === 0;
    }


}

<?php

namespace Ardent\Collection;


class ArrayIterator extends \ArrayIterator implements Enumerator {


    function __construct(array $array) {
        parent::__construct($array);
    }


    /**
     * @return bool
     */
    function isEmpty() {
        return parent::count() === 0;
    }


}

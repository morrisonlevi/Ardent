<?php

namespace Collections;

use Iterator;

interface CountableIterator extends \Countable, Iterator {

    /**
     * @return bool
     */
    function isEmpty();

}

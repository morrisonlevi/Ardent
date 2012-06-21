<?php

namespace Spl;

use Countable,
    Traversable;

interface Collection extends Countable, Traversable /*, \Serializable  */ {

    /**
     * @abstract
     * @return void
     */
    function clear();

    /**
     * @abstract
     * @param Comparable $object
     * @return bool
     */
    function contains(Comparable $object);

    /**
     * @abstract
     * @return bool
     */
    function isEmpty();

}

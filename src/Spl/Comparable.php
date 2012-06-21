<?php

namespace Spl;

/**
 * The foundational interface for the library.  All data structures in this library hold and work on Comparable objects.
 */
interface Comparable {

    /**
     * @abstract
     * @param Comparable $that
     * @return int
     */
    function compareTo(Comparable $that);

    /**
     * @abstract
     * @return string
     */
    function getHash();

}

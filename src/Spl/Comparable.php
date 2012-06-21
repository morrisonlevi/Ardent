<?php

namespace Spl;

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

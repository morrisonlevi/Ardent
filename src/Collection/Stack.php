<?php

namespace Ardent\Collection;

use Countable;


interface Stack extends Countable, Enumerable {

    /**
     * @param Mixed $object
     * @return void
     */
    function push($object);


    /**
     * @return Mixed
     */
    function pop();


    /**
     * @return Mixed
     */
    function last();


    /**
     * @return Array
     */
    function toArray();


    /**
     * @return Bool
     */
    function isEmpty();


}

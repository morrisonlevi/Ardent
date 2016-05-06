<?php

namespace Ardent\Collection;

use Countable;


interface Stack extends Countable, Enumerable {

    /**
     * @param mixed $object
     * @return void
     */
    function push($object);


    /**
     * @return mixed
     */
    function pop();


    /**
     * @return mixed
     */
    function last();


    /**
     * @return array
     */
    function toArray();


    /**
     * @return bool
     */
    function isEmpty();


}

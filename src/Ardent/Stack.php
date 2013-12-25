<?php

namespace Ardent;

use Ardent\Exception\StateException;
use Countable;

interface Stack extends \IteratorAggregate, Countable {

    /**
     * @param mixed $object
     *
     * @throws StateException if $object is not the correct type.
     * @return void
     */
    function push($object);

    /**
     * @throws StateException if the Stack is empty.
     * @return mixed
     */
    function pop();

    /**
     * @throws StateException if the Stack is empty.
     * @return mixed
     */
    function peek();

    /**
     * @return bool
     */
    function isEmpty();

    /**
     * @return array
     */
    function toArray();

}

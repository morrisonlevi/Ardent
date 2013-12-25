<?php

namespace Ardent;

use Ardent\Exception\StateException;

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
    function last();

    /**
     * @return array
     */
    function toArray();

}

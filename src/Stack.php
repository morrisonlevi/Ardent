<?php

namespace Collections;

interface Stack extends \IteratorAggregate, \Countable, Enumerable {

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

    /**
     * @return bool
     */
    function isEmpty();

}

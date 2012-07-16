<?php

namespace Spl;

interface Stack extends Collection {

    /**
     * @abstract
     * @param mixed $object
     * @throws InvalidTypeException if $object is not the correct type.
     * @throws OverflowException if the Stack is full.
     * @return void
     */
    function push($object);

    /**
     * @abstract
     * @throws UnderflowException if the Stack is empty.
     * @return mixed
     */
    function pop();

    /**
     * @abstract
     * @throws UnderflowException if the Stack is empty.
     * @return mixed
     */
    function peek();

}

<?php

namespace Spl;

interface Stack extends Collection {

    /**
     * @param mixed $object
     *
     * @throws InvalidTypeException if $object is not the correct type.
     * @throws OverflowException if the Stack is full.
     * @return void
     */
    function push($object);

    /**
     * @throws UnderflowException if the Stack is empty.
     * @return mixed
     */
    function pop();

    /**
     * @throws UnderflowException if the Stack is empty.
     * @return mixed
     */
    function peek();

}

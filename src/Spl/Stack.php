<?php

namespace Spl;

interface Stack extends Collection {

    /**
     * @param mixed $object
     *
     * @throws TypeException if $object is not the correct type.
     * @throws OverflowException if the Stack is full.
     * @return void
     */
    function pushBack($object);

    /**
     * @throws UnderflowException if the Stack is empty.
     * @return mixed
     */
    function popBack();

    /**
     * @throws UnderflowException if the Stack is empty.
     * @return mixed
     */
    function peekBack();

}

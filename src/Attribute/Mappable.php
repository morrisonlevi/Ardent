<?php

namespace Collections\Attribute;

interface Mappable {
    
    /**
     * @param callable $mapper
     * @return \Traversable
     */
    function map(callable $mapper);

}


<?php

namespace Collections\Attribute;

interface Filterable {

    /**
     * @param callable $filter
     * @return \Traversable
     */
    function filter(callable $filter);

}


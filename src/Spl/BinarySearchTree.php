<?php

namespace Spl;

use IteratorAggregate;

interface BinarySearchTree extends IteratorAggregate, Collection {
    const TRAVERSE_IN_ORDER = 1;
    const TRAVERSE_LEVEL_ORDER = 2;
    const TRAVERSE_PRE_ORDER = 3;
    const TRAVERSE_POST_ORDER = 4;

    /**
     * @param mixed $element
     */
    function add($element);

    /**
     * @param mixed $element
     */
    function remove($element);

    /**
     * @param int $order
     * @param callable $callback
     *
     * @return mixed
     */
    function traverse($order, $callback);

}

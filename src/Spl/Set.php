<?php

namespace Spl;

use Traversable;

interface Set extends Collection {

    /**
     * @abstract
     * @param $item
     * @return void
     */
    function add($item);

    /**
     * @abstract
     * @param Traversable $items
     * @return void
     */
    function addAll($items);

    /**
     * @abstract
     * @param $item
     * @return void
     */
    function remove($item);

    /**
     * @abstract
     * @param Traversable $items
     * @return mixed
     * @throws \Exception when the Traversable does not include an item of type Comparable.
     */
    function removeAll($items);

    /**
     * @abstract
     * @param Traversable $items
     * @return mixed
     * @throws \Exception when the Traversable does not include an item of type Comparable.
     */
    function retainAll($items);

}

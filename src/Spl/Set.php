<?php

namespace Spl;

use Traversable;

interface Set extends Collection {

    /**
     * @abstract
     * @param Comparable $item
     * @return void
     */
    function add(Comparable $item);

    /**
     * @abstract
     * @param Traversable $items
     * @return void
     */
    function addAll(Traversable $items);

    /**
     * @abstract
     * @param Comparable $item
     * @return void
     */
    function remove(Comparable $item);

    /**
     * @abstract
     * @param Traversable $items
     * @return mixed
     * @throws \Exception when the Traversable does not include an item of type Comparable.
     */
    function removeAll(Traversable $items);

    /**
     * @abstract
     * @param Traversable $items
     * @return mixed
     * @throws \Exception when the Traversable does not include an item of type Comparable.
     */
    function retainAll(Traversable $items);

}

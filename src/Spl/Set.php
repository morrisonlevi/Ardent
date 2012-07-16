<?php

namespace Spl;

use Traversable;

interface Set extends Collection {

    /**
     * @abstract
     * @param $item
     * @return void
     * @throws InvalidTypeException when $item is not the correct type.
     */
    function add($item);

    /**
     * @abstract
     * @param Traversable $items
     * @return void
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
     */
    function addAll(Traversable $items);

    /**
     * @abstract
     * @param $item
     * @return void
     * @throws InvalidTypeException when $item is not the correct type.
     */
    function remove($item);

    /**
     * @abstract
     * @param Traversable $items
     * @return mixed
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
     */
    function removeAll(Traversable $items);

    /**
     * @abstract
     * @param Traversable $items
     * @return mixed
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
     */
    function retainAll(Traversable $items);

}

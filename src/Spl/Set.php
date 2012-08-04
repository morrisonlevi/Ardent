<?php

namespace Spl;

use Traversable;

interface Set extends Collection {

    /**
     * @param $item
     *
     * @return void
     * @throws InvalidTypeException when $item is not the correct type.
     */
    function add($item);

    /**
     * @param Traversable $items
     *
     * @return void
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
     */
    function addAll(Traversable $items);

    /**
     * @param $item
     *
     * @return void
     * @throws InvalidTypeException when $item is not the correct type.
     */
    function remove($item);

    /**
     * @param Traversable $items
     *
     * @return mixed
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
     */
    function removeAll(Traversable $items);

}

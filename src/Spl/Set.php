<?php

namespace Spl;

use Traversable;

interface Set extends Collection {

    /**
     * @param $item
     *
     * @return void
     * @throws TypeException when $item is not the correct type.
     */
    function add($item);

    /**
     * @param Traversable $items
     *
     * @return void
     * @throws TypeException when the Traversable includes an item with an incorrect type.
     */
    function addAll(Traversable $items);

    /**
     * @param $item
     *
     * @return void
     * @throws TypeException when $item is not the correct type.
     */
    function remove($item);

    /**
     * @param Traversable $items
     *
     * @return mixed
     * @throws TypeException when the Traversable includes an item with an incorrect type.
     */
    function removeAll(Traversable $items);

    /**
     * @param Set $that
     * @return Set
     */
    function difference(Set $that);

}

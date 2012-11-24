<?php

namespace Spl;

use Traversable;

class SortedSet implements Set {

    /**
     * @var AvlTree
     */
    private $bst;

    public function __construct($comparator = NULL) {
        $this->bst = new AvlTree($comparator);
    }

    /**
     * @return void
     */
    function clear() {
        $this->bst->clear();
    }

    /**
     * @param $object
     *
     * @return bool
     * @throws TypeException when $object is not the correct type.
     */
    function contains($object) {
        return $this->bst->contains($object);
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return $this->bst->isEmpty();
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count() {
        return $this->bst->count();
    }

    /**
     *
     * @param $item
     *
     * @return void
     * @throws TypeException when $item is not the correct type.
     */
    function add($item) {
        $this->bst->add($item);
    }

    /**
     *
     * @param Traversable $items
     *
     * @return void
     * @throws TypeException when the Traversable includes an item of the incorrect type.
     */
    function addAll(Traversable $items) {
        foreach ($items as $item) {
            $this->bst->add($item);
        }
    }

    /**
     *
     * @param $item
     *
     * @return void
     * @throws TypeException when $item is not the correct type.
     */
    function remove($item) {
        $this->bst->remove($item);
    }

    /**
     *
     * @param Traversable $items
     *
     * @return mixed
     * @throws TypeException when the Traversable includes an item with an incorrect type.
     */
    function removeAll(Traversable $items) {
        foreach ($items as $item) {
            $this->bst->remove($item);
        }
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return SortedSetIterator
     */
    public function getIterator() {
        return new SortedSetIterator($this->bst->getIterator(), $this->count());
    }

}

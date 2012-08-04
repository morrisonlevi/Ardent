<?php

namespace Spl;

use IteratorAggregate,
    Traversable;

class SortedSet implements IteratorAggregate, Set {

    /**
     * @var BinarySearchTree
     */
    private $bst;

    public function __construct($comparator = NULL) {
        $this->bst = new AVLTree($comparator);
    }

    /**
     * @return void
     */
    function clear() {
        $this->bst->clear();
    }

    /**
     *
     * @param $object
     *
     * @return bool
     * @throws InvalidTypeException when $object is not the correct type.
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
     * @throws InvalidTypeException when $item is not the correct type.
     */
    function add($item) {
        $this->bst->add($item);
    }

    /**
     *
     * @param Traversable $items
     *
     * @return void
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
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
     * @throws InvalidTypeException when $item is not the correct type.
     */
    function remove($item) {
        $this->bst->remove($item);
    }

    /**
     *
     * @param Traversable $items
     *
     * @return mixed
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
     */
    function removeAll(Traversable $items) {
        foreach ($items as $item) {
            $this->bst->remove($item);
        }
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable
     */
    public function getIterator() {
        return $this->bst->getIterator();
    }

}

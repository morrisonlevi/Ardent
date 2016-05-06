<?php

namespace Ardent\Collection;

class SortedSet extends AbstractSet implements Set {

    /**
     * @var BinarySearchTree
     */
    private $bst;

    /**
     * @var Callable
     */
    private $comparator;


    /**
     * @param Callable $comparator
     * @param BinarySearchTree $tree
     */
    function __construct(Callable $comparator = null, BinarySearchTree $tree = null) {
        if ($tree !== null) {
            assert($tree->isEmpty());
            $this->bst = $tree;
            if ($comparator !== null) {
                $tree->setCompare($comparator);
            }
        } else {
            $this->bst = new AvlTree($comparator);
        }
    }


    /**
     * @return void
     */
    function clear() {
        $this->bst->clear();
    }


    function has($item) {
        return $this->bst->contains($item);
    }


    /**
     * @return Bool
     */
    function isEmpty() {
        return $this->bst->isEmpty();
    }


    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return Int
     */
    function count() {
        return $this->bst->count();
    }


    /**
     * Note that if the item is considered equal to an already existing item
     * in the set that it will be replaced.
     *
     * @param $item
     *
     * @return void
     */
    function add($item) {
        $this->bst->add($item);
    }


    /**
     *
     * @param $item
     *
     * @return void
     */
    function remove($item) {
        $this->bst->remove($item);
    }


    /**
     * @return Mixed
     */
    function first() {
        return $this->bst->first();
    }


    /**
     * @return Mixed
     */
    function last() {
        return $this->bst->last();
    }


    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return SortedSetIterator
     */
    function getIterator() {
        return new SortedSetIterator($this->bst->getIterator(), $this->count());
    }


    /**
     * @return SortedSet
     */
    protected function cloneEmpty() {
        return new self($this->comparator);
    }

}

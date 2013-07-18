<?php

namespace Ardent;

use Ardent\Exception\EmptyException,
    Ardent\Exception\TypeException,
    Ardent\Iterator\SortedSetIterator;
use Ardent\Exception\StateException;

class SortedSet extends AbstractSet implements Set {

    use StructureCollection;

    /**
     * @var BinarySearchTree
     */
    private $bst;

    /**
     * @var callable
     */
    private $comparator;

    /**
     * @param callable $comparator
     * @param BinarySearchTree $tree
     * @throws \Ardent\Exception\StateException
     */
    function __construct(callable $comparator = NULL, BinarySearchTree $tree = NULL) {
        if ($tree !== NULL) {
            if (!$tree->isEmpty()) {
                throw new StateException(
                    'The BinarySearchTree provided to SortedSet was not empty'
                );
            }
            $this->bst = $tree;
            if ($comparator !== NULL) {
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

    function containsItem($item) {
        return $this->bst->containsItem($item);
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
     * @throws TypeException when $item is not the correct type.
     */
    function add($item) {
        $this->bst->add($item);
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
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function findFirst() {
        return $this->bst->findFirst();
    }   

    /** 
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function findLast() {
        return $this->bst->findLast();
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

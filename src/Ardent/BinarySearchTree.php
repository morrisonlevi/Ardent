<?php

namespace Ardent;

use Ardent\Exception\EmptyException,
    Ardent\Exception\LookupException,
    Ardent\Exception\StateException,
    Ardent\Exception\TypeException,
    Ardent\Iterator\BinaryTreeIterator;

interface BinarySearchTree extends \IteratorAggregate, Collection {

    const TRAVERSE_IN_ORDER = 1;
    const TRAVERSE_LEVEL_ORDER = 2;
    const TRAVERSE_PRE_ORDER = 3;
    const TRAVERSE_POST_ORDER = 4;

    /**
     * @param callable $f
     * @return mixed
     * @throws StateException when the tree is not empty
     */
    function setCompare(callable $f);

    /**
     * @param $a
     * @param $b
     * @return int
     */
    function compare($a, $b);

    /**
     * @param mixed $element
     */
    function add($element);

    /**
     * @param mixed $element
     */
    function remove($element);

    /**
     * @param $element
     *
     * @return mixed
     * @throws LookupException
     */
    function get($element);

    /**
     * @return BinaryTree A copy of the current BinaryTree
     */
    function toBinaryTree();

    /**
     * @return void
     */
    function clear();

    /**
     * @param $item
     *
     * @return bool
     * @throws TypeException when $item is not the correct type.
     */
    function contains($item);

    /**
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function findFirst();

    /**
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function findLast();

    /**
     * @return bool
     */
    function isEmpty();

    /**
     * @param int $order [optional]
     *
     * @return BinaryTreeIterator
     */
    function getIterator($order = self::TRAVERSE_IN_ORDER);

}

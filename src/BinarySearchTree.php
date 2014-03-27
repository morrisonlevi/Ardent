<?php

namespace Collections;

interface BinarySearchTree extends \Countable, Enumerable {

    const TRAVERSE_IN_ORDER = 0;
    const TRAVERSE_LEVEL_ORDER = 1;
    const TRAVERSE_PRE_ORDER = 2;
    const TRAVERSE_POST_ORDER = 3;

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
    function first();

    /**
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function last();

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

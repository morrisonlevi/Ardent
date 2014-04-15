<?php

namespace Collections;

interface BinarySearchTree extends \Countable, Enumerable {

    /**
     * @param callable $f
     * @return mixed
     * @throws StateException when the tree is not empty
     */
    function setCompare(callable $f);


    /**
     * @param mixed $element
     * @return void
     */
    function add($element);


    /**
     * @param mixed $element
     * @return void
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
     * @return BinaryTreeIterator
     */
    function getIterator();

}

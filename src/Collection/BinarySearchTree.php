<?php

namespace Ardent\Collection;

use Countable;


interface BinarySearchTree extends Countable, Enumerable {

    /**
     * @param Callable $f
     * @return Mixed
     */
    function setCompare(Callable $f);


    /**
     * @param Mixed $element
     * @return void
     */
    function add($element);


    /**
     * @param Mixed $element
     * @return void
     */
    function remove($element);


    /**
     * @param $element
     *
     * @return Mixed
     */
    function get($element);


    /**
     * @return BinaryTree|null A copy of the current BinaryTree
     */
    function toBinaryTree();


    /**
     * @return void
     */
    function clear();


    /**
     * @param $item
     *
     * @return Bool
     */
    function contains($item);


    /**
     * @return Mixed
     */
    function first();


    /**
     * @return Mixed
     */
    function last();


    /**
     * @return Bool
     */
    function isEmpty();


    /**
     * @return BinaryTreeIterator
     */
    function getIterator();

}

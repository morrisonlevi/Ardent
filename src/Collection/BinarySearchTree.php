<?php

namespace Ardent\Collection;

use Countable;


interface BinarySearchTree extends Countable, Enumerable {

    /**
     * @param callable $f
     * @return mixed
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
     * @return bool
     */
    function contains($item);


    /**
     * @return mixed
     */
    function first();


    /**
     * @return mixed
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

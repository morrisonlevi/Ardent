<?php

namespace Ardent\Iterator;

use Ardent\BinaryTree;
use Ardent\CollectionIterator;
use Ardent\LinkedStack;
use Ardent\Stack;

class PreOrderIterator implements BinaryTreeIterator {

    use CollectionIterator;

    /**
     * @var Stack
     */
    protected $stack;

    /**
     * @var BinaryTree
     */
    protected $root;

    /**
     * @var BinaryTree
     */
    protected $value;

    /**
     * @var int
     */
    protected $key = NULL;

    function __construct(BinaryTree $root = NULL) {
        $this->root = $root;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->stack = new LinkedStack();
        $this->stack->push($this->root);

        $this->value = $this->root;

        $this->key = $this->root === NULL
            ? NULL
            : 0;
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->value !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return int|NULL
     */
    function key() {
        return $this->key;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->value->getValue();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        /**
         * @var BinaryTree $node
         */
        $node = $this->stack->pop();

        $right = $node->getRight();
        if ($right !== NULL) {
            $this->stack->push($right);
        }

        $left = $node->getLeft();
        if ($left !== NULL) {
            $this->stack->push($left);
        }

        if ($this->stack->isEmpty()) {
            $this->value = $this->key = NULL;
            return;
        }
        $this->value = $this->stack->peek();
        $this->key++;
    }

    /**
     * The runtime performance of count is O(n), not the usual constant time.
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return iterator_count($this);
    }

}

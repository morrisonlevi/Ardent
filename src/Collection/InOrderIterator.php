<?php

namespace Ardent\Collection;

class InOrderIterator implements BinaryTreeIterator {

    /**
     * @var Stack
     */
    protected $stack;

    /**
     * @var BinaryTree
     */
    protected $root;

    /**
     * @var int
     */
    protected $key = null;

    /**
     * @var BinaryTree
     */
    protected $node;

    private $size = 0;


    function __construct(BinaryTree $root = null, $count = 0) {
        $this->root = $root;
        $this->size = $count;
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->node->value();
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

        $right = $node->right();
        if ($right !== null) {
            // left-most branch of the right side
            $this->pushLeft($right);
        }

        if ($this->stack->isEmpty()) {
            $this->node = null;
            return;
        }
        $this->node = $this->stack->last();

        $this->key++;
    }


    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return int|NULL
     */
    function key() {
        return $this->key;
    }


    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool
     */
    function valid() {
        return $this->node !== null;
    }


    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->stack = new LinkedStack();

        $this->pushLeft($this->root);

        if (!$this->stack->isEmpty()) {
            $this->node = $this->stack->last();
            $this->key = 0;
        }
    }


    function count() {
        return $this->size;
    }


    private function pushLeft(BinaryTree $n = null) {
        for ($current = $n; $current !== null; $current = $current->left()) {
            $this->stack->push($current);
        }
    }


    /**
     * @return bool
     */
    function isEmpty() {
        return $this->size === 0;
    }


}

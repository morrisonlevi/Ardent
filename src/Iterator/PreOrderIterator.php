<?php

namespace Collections;

class PreOrderIterator implements BinaryTreeIterator {

    use IteratorCollection;

    /**
     * @var Stack
     */
    private $stack;

    /**
     * @var BinaryTree
     */
    private $root;

    /**
     * @var BinaryTree
     */
    private $value;

    private $key = NULL;

    private $size = 0;

    function __construct(BinaryTree $root = NULL, $count = 0) {
        $this->root = $root;
        $this->size = $count;
    }

    function count() {
        return $this->size;
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
        return $this->value->value();
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
        if ($right !== NULL) {
            $this->stack->push($right);
        }

        $left = $node->left();
        if ($left !== NULL) {
            $this->stack->push($left);
        }

        if ($this->stack->isEmpty()) {
            $this->value = $this->key = NULL;
            return;
        }
        $this->value = $this->stack->last();
        $this->key++;
    }

}

<?php

namespace Spl;

class PreOrderIterator implements BinaryTreeIterator {

    /**
     * @var ArrayStack
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

    public function __construct(BinaryTree $root = NULL) {
        $this->stack = new ArrayStack;
        $this->root = $root;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    public function current() {
        return $this->value->getValue();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    public function next() {
        /**
         * @var BinaryTree $node
         */
        $node = $this->stack->popBack();

        $right = $node->getRight();
        if ($right !== NULL) {
            $this->stack->pushBack($right);
        }

        $left = $node->getLeft();
        if ($left !== NULL) {
            $this->stack->pushBack($left);
        }

        if ($this->stack->isEmpty()) {
            $this->value = NULL;
            return;
        }
        $this->value = $this->stack->peekBack();

    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return NULL
     */
    public function key() {
        return NULL; //no keys in a tree . . .
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid() {
        return $this->value !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind() {
        $this->stack->clear();

        $this->stack->pushBack($this->root);

        $this->value = $this->root;
    }
}

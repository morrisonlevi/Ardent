<?php

namespace Spl;

class InOrderIterator implements BinaryTreeIterator {

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
            // left-most branch of the right side
            for ($left = $right; $left !== NULL; $left = $left->getLeft()) {
                $this->stack->pushBack($left);
            }
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

        for ($current = $this->root; $current !== NULL; $current = $current->getLeft()) {
            $this->stack->pushBack($current);
        }

        if (!$this->stack->isEmpty()) {
            $this->value = $this->stack->peekBack();
        }
    }
}

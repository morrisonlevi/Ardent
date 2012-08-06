<?php

namespace Spl;

use Iterator;

class InOrderIterator implements BinaryTreeIterator {

    /**
     * @var ArrayStack
     */
    protected $stack;

    /**
     * @var BinaryNode
     */
    protected $root;

    /**
     * @var BinaryNode
     */
    protected $value;

    public function __construct(BinaryNode $root) {
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
         * @var BinaryNode $node
         */
        $node = $this->stack->pop();

        $right = $node->getRight();
        if ($right !== NULL) {
            // left-most branch of the right side
            for ($left = $right; $left !== NULL; $left = $left->getLeft()) {
                $this->stack->push($left);
            }
        }

        if ($this->stack->isEmpty()) {
            $this->value = NULL;
            return;
        }
        $this->value = $this->stack->peek();

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
            $this->stack->push($current);
        }

        $this->value = $this->stack->peek();
    }
}

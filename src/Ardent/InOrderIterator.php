<?php

namespace Ardent;

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
    protected $key = NULL;

    /**
     * @var BinaryTree
     */
    protected $value;

    public function __construct(BinaryTree $root = NULL) {
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
        if (!$this->valid()) {
            $this->key = NULL;
            return;
        }

        /**
         * @var BinaryTree $node
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

        $this->key++;
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return int|NULL
     */
    public function key() {
        return $this->key;
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
        $this->stack = new LinkedStack();

        for ($current = $this->root; $current !== NULL; $current = $current->getLeft()) {
            $this->stack->push($current);
        }

        if (!$this->stack->isEmpty()) {
            $this->value = $this->stack->peek();
            $this->key = 0;
        }
    }
}

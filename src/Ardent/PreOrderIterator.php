<?php

namespace Ardent;

class PreOrderIterator implements BinaryTreeIterator {

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

    public function __construct(BinaryTree $root = NULL) {
        $this->root = $root;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind() {
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
    public function valid() {
        return $this->value !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return int|NULL
     */
    public function key() {
        return $this->key;
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

}

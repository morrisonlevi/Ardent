<?php

namespace Ardent\Iterator;

use Ardent\BinaryTree;
use Ardent\CollectionIterator;
use Ardent\LinkedStack;
use Ardent\Stack;

class PostOrderIterator implements BinaryTreeIterator {

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
     * @var BinaryTree
     */
    protected $current;

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

        $this->value = $this->root;
        $this->next();
        $this->key = $this->root === NULL
            ? NULL
            : 0;
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->current !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return NULL
     */
    function key() {
        return $this->current !== NULL
            ? $this->key
            : NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->current->getValue();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        /**
         * @var BinaryTree $node
         */
        if ($this->value !== NULL) {
            $right = $this->value->getRight();
            if ($right !== NULL) {
                $this->stack->push($right);
            }
            $this->stack->push($this->value);
            $this->value = $this->value->getLeft();
            $this->next();
            return;
        }

        if ($this->stack->isEmpty()) {
            $this->current = $this->value;
            $this->key++;
            $this->value = NULL;
            return;
        }

        $this->value = $this->stack->pop();

        $right = $this->value->getRight();
        if ($right !== NULL && !$this->stack->isEmpty() && $right === $this->stack->peek()) {
            $this->stack->pop();
            $this->stack->push($this->value);
            $this->value = $right;
            $this->next();
        } else {
            $this->current = $this->value;
            $this->key++;
            $this->value = NULL;
        }
    }

}

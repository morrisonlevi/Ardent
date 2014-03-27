<?php

namespace Collections;

class InOrderIterator implements BinaryTreeIterator {

    use IteratorCollection;

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
    protected $node;

    private $size = 0;

    function __construct(BinaryTree $root = NULL, $count = 0) {
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
        if ($right !== NULL) {
            // left-most branch of the right side
            for ($left = $right; $left !== NULL; $left = $left->left()) {
                $this->stack->push($left);
            }
        }

        if ($this->stack->isEmpty()) {
            $this->node = NULL;
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
     * @return boolean
     */
    function valid() {
        return $this->node !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->stack = new LinkedStack();

        for ($current = $this->root; $current !== NULL; $current = $current->left()) {
            $this->stack->push($current);
        }

        if (!$this->stack->isEmpty()) {
            $this->node = $this->stack->last();
            $this->key = 0;
        }
    }

    function count() {
        return $this->size;
    }

}

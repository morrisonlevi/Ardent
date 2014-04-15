<?php

namespace Collections;

class PostOrderIterator implements BinaryTreeIterator {

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
     * @var BinaryTree
     */
    protected $value;

    /**
     * @var BinaryTree
     */
    protected $current;

    protected $key = -1;

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

        $this->value = $this->root;
        $this->key = -1;
        $this->next();
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
     * @return 0
     */
    function key() {
        return $this->key;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->current->value();
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
            $right = $this->value->right();
            if ($right !== NULL) {
                $this->stack->push($right);
            }
            $this->stack->push($this->value);
            $this->value = $this->value->left();
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

        $right = $this->value->right();
        if ($right !== NULL && !$this->stack->isEmpty() && $right === $this->stack->last()) {
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

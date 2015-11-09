<?php

namespace Ardent\Collection;

class PostOrderIterator implements BinaryTreeIterator {

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


    function __construct(BinaryTree $root = null, $count = 0) {
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
     * @return bool
     */
    function valid() {
        return $this->current !== null;
    }


    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return int
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
        if ($this->value !== null) {
            $this->next_valueNotNull();
            return;
        }

        if ($this->stack->isEmpty()) {
            $this->next_set();
            return;
        }

        $this->value = $this->stack->pop();

        $this->next_right();
    }


    private function next_right() {
        $right = $this->value->right();
        if ($right !== null && !$this->stack->isEmpty() && $right === $this->stack->last()) {
            $this->stack->pop();
            $this->next_push($right);
        } else {
            $this->next_set();
        }
    }


    private function next_valueNotNull() {
        $right = $this->value->right();
        if ($right !== null) {
            $this->stack->push($right);
        }
        $this->next_push($this->value->left());
    }


    private function next_set() {
        $this->current = $this->value;
        $this->key++;
        $this->value = null;
    }


    private function next_push(BinaryTree $n = null) {
        $this->stack->push($this->value);
        $this->value = $n;
        $this->next();
    }


    /**
     * @return bool
     */
    function isEmpty() {
        return $this->count() === 0;
    }
}

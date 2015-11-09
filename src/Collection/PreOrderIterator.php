<?php

namespace Ardent\Collection;

class PreOrderIterator implements BinaryTreeIterator {

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

    private $key = 0;

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
        $this->key = 0;
        $this->stack->push($this->root);
        $this->value = $this->root;
    }


    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool
     */
    function valid() {
        return $this->value !== null;
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

        $this->pushIfNotNull('right', $node);
        $this->pushIfNotNull('left', $node);

        if ($this->stack->isEmpty()) {
            $this->value = $this->key = null;
            return;
        }
        $this->value = $this->stack->last();
        $this->key++;
    }


    private function pushIfNotNull($direction, BinaryTree $n) {
        $next = $n->$direction();
        if ($next !== null) {
            $this->stack->push($next);
        }
    }


    /**
     * @return bool
     */
    function isEmpty() {
        return $this->count() === 0;
    }


}

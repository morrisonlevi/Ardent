<?php

namespace Spl;

use Iterator;

class InOrderIterator implements Iterator {

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
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current() {
        return $this->value->getValue();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next() {
        /**
         * @var BinaryNode $node
         */
        $node = $this->stack->pop();

        $right = $node->getRight();
        if ($right !== NULL) {
            // left-most branch of the right side
            for ($left = $right; $left !== NULL; $left = $left->getRight()) {
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
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key() {
        return NULL; //no keys in a tree . . .
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid() {
        return $this->value !== NULL;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind() {
        $this->stack->clear();

        for ($current = $this->root; $current !== NULL; $current = $current->getLeft()) {
            $this->stack->push($current);
        }

        $this->value = $this->stack->peek();
    }
}

<?php

namespace Ardent;

class LevelOrderIterator implements BinaryTreeIterator {

    /**
     * @var array
     */
    protected $queue = array();

    /**
     * @var BinaryTree
     */
    protected $root;

    /**
     * @var BinaryTree
     */
    protected $value;

    protected $key = NULL;

    public function __construct(BinaryTree $root = NULL) {
        $this->root = $root;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind() {
        $this->queue = array($this->root);
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
        $node = array_shift($this->queue);

        $left = $node->getLeft();
        if ($left !== NULL) {
            $this->queue[] = $left;
        }

        $right = $node->getRight();
        if ($right !== NULL) {
            $this->queue[] = $right;
        }

        if (empty($this->queue)) {
            $this->value = $this->key = NULL;
            return;
        }

        $this->value = $this->queue[0];
        $this->key++;
    }

}

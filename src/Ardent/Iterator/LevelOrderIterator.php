<?php

namespace Ardent\Iterator;

use Ardent\BinaryTree;
use Ardent\CollectionIterator;

class LevelOrderIterator implements BinaryTreeIterator {

    use CollectionIterator;

    /**
     * @var array
     */
    protected $queue = [];

    /**
     * @var BinaryTree
     */
    protected $root;

    /**
     * @var BinaryTree
     */
    protected $value;

    protected $key = NULL;

    function __construct(BinaryTree $root = NULL) {
        $this->root = $root;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->queue = [$this->root];
        $this->value = $this->root;
        $this->key = $this->root === NULL
            ? NULL
            : 0;
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->value !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return int|NULL
     */
    function key() {
        return $this->key;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return $this->value->getValue();
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
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

    /**
     * The runtime performance of count is O(n), not the usual constant time.
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return iterator_count($this);
    }

}

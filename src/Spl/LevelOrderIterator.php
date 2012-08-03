<?php

namespace Spl;

use ArrayIterator,
    Iterator;

class _LevelOrderIterator extends ArrayIterator {

    public function __construct(BinaryNode $root) {

        $array = array();
        $queue = array();
        array_push($queue, $root);
        while (!empty($queue)) {
            /**
             * @var BinaryNode $node
             */
            $node = array_shift($queue);
            $array[] = $node->getValue();

            $left = $node->getLeft();
            $right = $node->getRight();
            if ($left !== NULL) {
                array_push($queue, $left);
            }
            if ($right !== NULL) {
                array_push($queue, $right);
            }
        }

        parent::__construct($array);
    }
}

class LevelOrderIterator implements Iterator {

    /**
     * @var array
     */
    protected $queue = array();

    /**
     * @var BinaryNode
     */
    protected $root;

    /**
     * @var BinaryNode
     */
    protected $value;

    public function __construct(BinaryNode $root) {
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
        /**
         * @var BinaryNode $node
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
            $this->value = NULL;
            return;
        }

        $this->value = $this->queue[0];

    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return NULL
     */
    public function key() {
        return NULL; //no keys in a tree . . .
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
        $this->queue = array($this->root);
        $this->value = $this->root;
    }
}

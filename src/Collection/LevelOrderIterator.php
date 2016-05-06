<?php

namespace Ardent\Collection;

class LevelOrderIterator implements BinaryTreeIterator {


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

    protected $key = 0;

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
        $this->queue = [$this->root];
        $this->value = $this->root;
        $this->key = 0;
    }


    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool
     */
    function valid() {
        return $this->key < $this->count();
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
        $this->key++;
        /**
         * @var BinaryTree $node
         */
        $node = array_shift($this->queue);

        $this->pushIfNotNull('left', $node);
        $this->pushIfNotNull('right', $node);

        $this->value = empty($this->queue)
            ? null
            : $this->queue[0];
    }


    private function pushIfNotNull($direction, BinaryTree $context) {
        $value = $context->$direction();
        if ($value !== null) {
            $this->queue[] = $value;
        }
    }


    /**
     * @return bool
     */
    function isEmpty() {
        return $this->count() === 0;
    }


}

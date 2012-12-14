<?php

namespace Ardent;

class BinaryTree {

    /**
     * @var BinaryTree
     */
    protected $left = NULL;

    /**
     * @var BinaryTree
     */
    protected $right = NULL;

    /**
     * @var mixed
     */
    protected $value = NULL;

    /**
     * @var int
     */
    protected $height = 0;

    /**
     * @param mixed $value
     */
    function __construct($value) {
        $this->value = $value;
    }

    /**
     * @return BinaryTree
     */
    function getRight() {
        return $this->right;
    }

    /**
     * @return BinaryTree
     */
    function getLeft() {
        return $this->left;
    }

    /**
     * @param BinaryTree $node
     * @return void
     */
    function setRight(BinaryTree $node = NULL) {
        $this->right = $node;
        $this->recalculateHeight();
    }

    /**
     * @param BinaryTree $node
     * @return void
     */
    function setLeft(BinaryTree $node = NULL) {
        $this->left = $node;
        $this->recalculateHeight();
    }

    /**
     * @return int
     */
    function getHeight() {
        return $this->height + 1;
    }

    /**
     * @param BinaryTree $node
     * @return int
     */
    protected function getNodeHeight(BinaryTree $node = NULL) {
        if ($node === NULL) {
            return 0;
        }

        return $node->getHeight();
    }

    /**
     * @return mixed
     */
    function getValue() {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return void
     */
    function setValue($value) {
        $this->value = $value;
    }

    function recalculateHeight() {
        $this->height = max($this->getNodeHeight($this->left), $this->getNodeHeight($this->right));
    }

    /**
     * @return bool
     */
    function isLeaf() {
        return $this->left === NULL && $this->right === NULL;
    }

    /**
     * @return bool
     */
    function hasOnlyOneChild() {
        return ($this->left === NULL && $this->right !== NULL)
            || ($this->left !== NULL && $this->right === NULL);
    }

    /**
     * @return BinaryTree
     */
    function getInOrderPredecessor() {

        for ($current = $this->getLeft(); $current->getRight() !== NULL; $current = $current->getRight()) ;

        return $current;

    }

    function __clone() {
        $this->left = $this->left === NULL
            ? NULL
            : clone $this->left;

        $this->right = $this->right === NULL
            ? NULL
            : clone $this->right;
    }

}

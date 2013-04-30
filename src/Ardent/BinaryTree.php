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
    protected $height = 1;

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
        return $this->height;
    }

    /**
     * @return int
     */
    function getLeftHeight() {
        return $this->left === NULL ? 0 : $this->left->getHeight();
    }

    /**
     * @return int
     */
    function getRightHeight() {
        return $this->right === NULL ? 0 : $this->right->getHeight();
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
        $this->height = max($this->getLeftHeight(), $this->getRightHeight()) + 1;
    }

    /**
     * Note that this function is only safe to call when it has a predecessor.
     * @return BinaryTree
     */
    function getInOrderPredecessor() {
        $current = $this->getLeft();
        while ($current->getRight() !== NULL) {
            $current = $current->getRight();
        }
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

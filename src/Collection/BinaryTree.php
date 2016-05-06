<?php

namespace Ardent\Collection;


class BinaryTree {

    /**
     * @var BinaryTree|null
     */
    private $left = null;

    /**
     * @var BinaryTree|null
     */
    private $right = null;

    private $value;

    private $height = 1;


    function __construct($value) {
        $this->value = $value;
    }


    /**
     * @return BinaryTree|null
     */
    function right() {
        return $this->right;
    }


    /**
     * @return BinaryTree|null
     */
    function left() {
        return $this->left;
    }


    /**
     * @param BinaryTree $node
     * @return void
     */
    function setRight(BinaryTree $node = null) {
        $this->right = $node;
        $this->recalculateHeight();
    }


    /**
     * @param BinaryTree $node
     * @return void
     */
    function setLeft(BinaryTree $node = null) {
        $this->left = $node;
        $this->recalculateHeight();
    }


    /**
     * @return int
     */
    function height() {
        return $this->height;
    }


    /**
     * @return int
     */
    function leftHeight() {
        return $this->left === null
            ? 0
            : $this->left->height();
    }


    /**
     * @return int
     */
    function rightHeight() {
        return $this->right === null
            ? 0
            : $this->right->height();
    }


    /**
     * @return mixed
     */
    function value() {
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
        $this->height = max($this->leftHeight(), $this->rightHeight()) + 1;
    }


    /**
     * Note that this function is only safe to call when it has a predecessor.
     * @return BinaryTree
     */
    function inOrderPredecessor() {
        $current = $this->left();
        while ($current->right() !== null) {
            $current = $current->right();
        }
        return $current;
    }


    function __clone() {
        $this->left = $this->left === null
            ? null
            : clone $this->left;

        $this->right = $this->right === null
            ? null
            : clone $this->right;
    }


}

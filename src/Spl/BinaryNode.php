<?php

namespace Spl;

class BinaryNode {

    /**
     * @var BinaryNode
     */
    protected $left = NULL;

    /**
     * @var BinaryNode
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
     * @return BinaryNode
     */
    function getRight() {
        return $this->right;
    }

    /**
     * @return BinaryNode
     */
    function getLeft() {
        return $this->left;
    }

    /**
     * @param BinaryNode $node
     * @return void
     */
    function setRight(BinaryNode $node = NULL) {
        $this->right = $node;
        $this->recalculateHeight();
    }

    /**
     * @param BinaryNode $node
     * @return void
     */
    function setLeft(BinaryNode $node = NULL) {
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
     * @param BinaryNode $node
     * @return int
     */
    protected function getNodeHeight(BinaryNode $node = NULL) {
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

}

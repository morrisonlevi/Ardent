<?php

namespace Spl;

class BinaryNode {

    protected $left = NULL;

    protected $right = NULL;

    protected $value = NULL;

    protected $height = 0;

    function __construct($element) {
        $this->value = $element;
    }

    function getRight() {
        return $this->right;
    }

    function getLeft() {
        return $this->left;
    }

    function setRight(BinaryNode $node = NULL) {
        $this->right = $node;
        $this->recalculateHeight();
    }

    function setLeft(BinaryNode $node = NULL) {
        $this->left = $node;
        $this->recalculateHeight();
    }

    function getHeight() {
        return $this->height + 1;
    }

    protected function getNodeHeight(BinaryNode $node = NULL) {
        if ($node === NULL) {
            return 0;
        }

        return $node->getHeight();
    }

    function getValue() {
        return $this->value;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function recalculateHeight() {
        $this->height = max($this->getNodeHeight($this->left), $this->getNodeHeight($this->right));
    }

    function isLeaf() {
        return $this->left === NULL && $this->right === NULL;
    }

    function hasOnlyOneChild() {
        return ($this->left === NULL && $this->right !== NULL)
            || ($this->left !== NULL && $this->right === NULL);
    }

}

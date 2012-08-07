<?php

namespace Spl;

class AvlTree extends BinarySearchTree {

    /**
     * @param $element
     * @param BinaryTree $node
     *
     * @return null|BinaryTree
     */
    protected function addNode($element, BinaryTree $node = NULL) {
        return $this->balance(parent::addNode($element, $node));
    }

    /**
     * @param $element
     * @param BinaryTree $node
     *
     * @return BinaryTree
     */
    protected function removeNode($element, BinaryTree $node = NULL) {
        return $this->balance(parent::removeNode($element, $node));
    }

    /**
     * @param BinaryTree $node
     *
     * @return null|BinaryTree
     */
    protected function balance(BinaryTree $node = NULL) {
        if ($node === NULL) {
            return NULL;
        }
        $node->recalculateHeight();

        $leftHeight = $this->getHeight($node->getLeft());
        $rightHeight = $this->getHeight($node->getRight());

        $diff = $leftHeight - $rightHeight;

        if ($diff < -1) {
            // right side is taller
            $node = $this->rotateLeft($node);
        } elseif ($diff > 1) {
            // left side is taller
            $node = $this->rotateRight($node);
        }

        return $node;
    }

    /**
     * @param BinaryTree $root
     *
     * @return BinaryTree
     */
    protected function rotateRight(BinaryTree $root) {
        $leftNode = $root->getLeft();
        $leftHeight = $this->getHeight($leftNode->getLeft());
        $rightHeight = $this->getHeight($leftNode->getRight());

        $diff = $leftHeight - $rightHeight;

        if ($diff < 0) {
            // Left-Right case
            $root->setLeft($this->rotateSingleLeft($leftNode));
            return $this->rotateSingleRight($root);
        } else {
            // Left-Left case
            return $this->rotateSingleRight($root);
        }
    }

    /**
     * @param BinaryTree $root
     *
     * @return BinaryTree
     */
    protected function rotateLeft(BinaryTree $root) {
        $rightNode = $root->getRight();
        $leftHeight = $this->getHeight($rightNode->getLeft());
        $rightHeight = $this->getHeight($rightNode->getRight());

        $diff = $leftHeight - $rightHeight;

        if ($diff < 0) {
            // Right-Right case
            return $this->rotateSingleLeft($root);
        } else {
            // Right-Left case
            $root->setRight($this->rotateSingleRight($rightNode));
            return $this->rotateSingleLeft($root);
        }
    }

    /**
     * @param BinaryTree $root
     *
     * @return BinaryTree
     */
    protected function rotateSingleRight(BinaryTree $root) {
        $pivot = $root->getLeft();
        $root->setLeft($pivot->getRight());
        $pivot->setRight($root);

        return $pivot;
    }

    /**
     * @param BinaryTree $root
     *
     * @return BinaryTree
     */
    protected function rotateSingleLeft(BinaryTree $root) {
        $pivot = $root->getRight();
        $root->setRight($pivot->getLeft());
        $pivot->setLeft($root);

        return $pivot;
    }

    /**
     * @param BinaryTree $node
     *
     * @return int
     */
    private function getHeight(BinaryTree $node = NULL) {
        if ($node === NULL) {
            return 0;
        }

        return $node->getHeight();
    }

}

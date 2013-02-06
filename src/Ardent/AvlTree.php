<?php

namespace Ardent;

class AvlTree extends BinarySearchTree {

    /**
     * @param $element
     * @param BinaryTree $node
     *
     * @return BinaryTree
     */
    protected function addRecursive($element, BinaryTree $node = NULL) {
        return $this->balance(parent::addRecursive($element, $node));
    }

    /**
     * @param $element
     * @param BinaryTree $node
     *
     * @return BinaryTree
     */
    protected function removeRecursive($element, BinaryTree $node = NULL) {
        return $this->balance(parent::removeRecursive($element, $node));
    }

    /**
     * @param BinaryTree $node
     *
     * @return BinaryTree
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
            $pivot = $leftNode->getRight();
            $leftNode->setRight($pivot->getLeft());
            $pivot->setLeft($leftNode);
            $root->setLeft($pivot);
        }

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
    protected function rotateLeft(BinaryTree $root) {
        $rightNode = $root->getRight();
        $leftHeight = $this->getHeight($rightNode->getLeft());
        $rightHeight = $this->getHeight($rightNode->getRight());

        $diff = $leftHeight - $rightHeight;

        if ($diff >= 0) {
            // Right-Left case

            $pivot = $rightNode->getLeft();
            $rightNode->setLeft($pivot->getRight());
            $pivot->setRight($rightNode);

            $root->setRight($pivot);
        }


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

<?php

namespace Collections;

class AvlTree implements BinarySearchTree {

    use EmptyGuard;
    use IteratorCollection;

    /**
     * @var BinaryTree
     */
    private $root = null;

    /**
     * @var callable
     */
    protected $comparator;

    /**
     * @var BinaryTree
     */
    private $cache = null;

    private $size = 0;

    private $deleteOptions;


    /**
     * @param callable $comparator
     */
    function __construct(callable $comparator = null) {
        $this->comparator = $comparator ?: '\Collections\compare';
        $this->deleteOptions = [
            0b000 => [$this, 'deleteNoChildren'],
            0b001 => $this->deleteSelect('right'),
            0b010 => $this->deleteSelect('left'),
            0b011 => [$this, 'deleteNeitherChildIsNull'],
        ];
    }


    /**
     * @param mixed $element
     */
    function add($element) {
        $this->root = $this->addRecursive($element, $this->root);
        $this->cache = null;
    }


    /**
     * @param mixed $element
     */
    function remove($element) {
        $this->root = $this->removeRecursive($element, $this->root);
        $this->cache = null;
    }


    /**
     * @param $element
     *
     * @return mixed
     * @throws LookupException
     */
    function get($element) {
        $node = $this->findNode($element, $this->root);
        if (!$node) {
            throw new LookupException;
        }
        return $node->value();
    }


    /**
     * @return BinaryTree A copy of the current BinaryTree
     */
    function toBinaryTree() {
        return $this->root !== null
            ? clone $this->root
            : null;
    }


    /**
     * @return void
     */
    function clear() {
        $this->root = null;
        $this->size = 0;
    }


    /**
     * @param $item
     *
     * @return bool
     * @throws TypeException when $item is not the correct type.
     */
    function contains($item) {
        return $this->findNode($item, $this->root) !== null;
    }


    /**
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function first() {
        $this->emptyGuard(__METHOD__);
        return $this->farthest('left', $this->root);
    }


    /**
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function last() {
        $this->emptyGuard(__METHOD__);
        return $this->farthest('right', $this->root);
    }


    /**
     * @return bool
     */
    function isEmpty() {
        return $this->root === null;
    }


    /**
     * @return BinaryTreeIterator
     */
    function getIterator() {
        return new InOrderIterator($this->root, $this->size);
    }


    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->size;
    }


    function __clone() {
        $this->root = $this->root === null
            ? null
            : clone $this->root;
    }


    /**
     * @param callable $f
     * @return mixed
     * @throws StateException when the tree is not empty
     */
    function setCompare(callable $f) {
        if ($this->root !== null) {
            throw new StateException('Cannot set compare function when the BinarySearchTree is not empty');
        }
        $this->comparator = $f;
    }


    /**
     * @param $element
     * @param BinaryTree $node
     *
     * @return BinaryTree
     */
    protected function addRecursive($element, BinaryTree $node = null) {
        $nullAction = [$this, 'createTree'];
        $matchAction = function (BinaryTree $n) use ($element) {
            $n->setValue($element);
            return $n;
        };
        return $this->doRecursive($nullAction, $matchAction, $element, $node);
    }


    /**
     * @param $element
     * @param BinaryTree $node
     *
     * @return BinaryTree
     */
    protected function removeRecursive($element, BinaryTree $node = null) {
        $nullAction = [$this, 'doNothing'];
        $matchAction = [$this, 'deleteNode'];
        return $this->doRecursive($nullAction, $matchAction, $element, $node);
    }


    private function doRecursive($nullAction, $matchAction, $element, BinaryTree $node = null) {
        if ($node === null) {
            return $nullAction($element);
        }

        $comparisonResult = call_user_func($this->comparator, $element, $node->value());

        if ($comparisonResult < 0) {
            $node->setLeft($this->doRecursive($nullAction, $matchAction, $element, $node->left));
        } elseif ($comparisonResult > 0) {
            $node->setRight($this->doRecursive($nullAction, $matchAction, $element, $node->right));
        } else {
            $node = $matchAction($node);
        }

        return $this->balance($node);
    }


    /**
     * @param BinaryTree $node
     *
     * @return BinaryTree
     */
    protected function deleteNode(BinaryTree $node) {
        $state = $this->deleteSelectState($node);
        return $this->deleteOptions[$state]($node);
    }


    /**
     * @param BinaryTree $node
     *
     * @return BinaryTree
     */
    protected function balance(BinaryTree $node = null) {
        if ($node === null) {
            return null;
        }

        $diff = $node->leftHeight() - $node->rightHeight();

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
        $leftNode = $root->left;
        $leftHeight = $leftNode->leftHeight();
        $rightHeight = $leftNode->rightHeight();

        $diff = $leftHeight - $rightHeight;

        if ($diff < 0) {
            // Left-Right case
            $pivot = $leftNode->right;
            $leftNode->setRight($pivot->left);
            $pivot->setLeft($leftNode);
            $root->setLeft($pivot);
        }

        $pivot = $root->left;
        $root->setLeft($pivot->right);
        $pivot->setRight($root);

        return $pivot;
    }


    /**
     * @param BinaryTree $root
     *
     * @return BinaryTree
     */
    protected function rotateLeft(BinaryTree $root) {
        $rightNode = $root->right;

        $diff = $rightNode->leftHeight() - $rightNode->rightHeight();

        if ($diff >= 0) {
            // Right-Left case
            $pivot = $rightNode->left;
            $rightNode->setLeft($pivot->right);
            $pivot->setRight($rightNode);
            $root->setRight($pivot);
        }


        $pivot = $root->right;
        $root->setRight($pivot->left);
        $pivot->setLeft($root);

        return $pivot;
    }


    private function findNode($element, BinaryTree $context = null) {
        while ($context !== null) {
            $comparisonResult = call_user_func($this->comparator, $element, $context->value());

            if ($comparisonResult < 0) {
                $context = $context->left;
            } elseif ($comparisonResult > 0) {
                $context = $context->right;
            } else {
                return $context;
            }
        }
        return null;
    }


    private function farthest($direction, BinaryTree $context) {
        for ($node = $context; $node->$direction !== null; $node = $node->$direction) {
            ;
        }
        return $node->value();
    }


    private function doNothing() {

    }


    private function createTree($element) {
        $this->size++;
        return new BinaryTree($element);
    }


    /**
     * @param BinaryTree $node
     * @return int
     */
    private function deleteSelectState(BinaryTree $node) {
        $state = 0;
        $state |= ($node->right != null) << 0;
        $state |= ($node->left != null) << 1;
        return $state;
    }


    private function deleteNoChildren(BinaryTree $tree) {
        $this->size--;
        return null;
    }


    private function deleteSelect($direction) {
        return function (BinaryTree $node) use ($direction) {
            $d = $node->$direction;
            unset($node);
            return $d;
        };
    }


    /**
     * @param BinaryTree $node
     * @return BinaryTree
     */
    private function deleteNeitherChildIsNull(BinaryTree $node) {
        $value = $node->inOrderPredecessor()->value();
        $node->setLeft($this->removeRecursive($value, $node->left));
        $node->setValue($value);
        return $node;
    }


}

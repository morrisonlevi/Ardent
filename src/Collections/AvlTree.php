<?php

namespace Collections;

class AvlTree implements BinarySearchTree {

    use IteratorCollection;

    /**
     * @var BinaryTree
     */
    private $root = NULL;

    /**
     * @var callable
     */
    protected $comparator;

    /**
     * @var BinaryTree
     */
    private $cache = NULL;

    private $size = 0;

    /**
     * @param callable $comparator
     */
    function __construct(callable $comparator = NULL) {
        $this->comparator = $comparator ?: [$this, 'compare'];
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    function compare($a, $b) {
        if ($a < $b) {
            return -1;
        } elseif ($b < $a) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * @param mixed $element
     */
    function add($element) {
        $this->root = $this->addRecursive($element, $this->root);
        $this->cache = NULL;
    }

    /**
     * @param $element
     * @param BinaryTree $node
     *
     * @return BinaryTree
     */
    protected function addRecursive($element, BinaryTree $node = NULL) {
        if ($node === NULL) {
            $this->size++;
            return new BinaryTree($element);
        }

        $comparisonResult = call_user_func($this->comparator, $element, $node->value());

        if ($comparisonResult < 0) {
            $node->setLeft($this->addRecursive($element, $node->left()));
        } elseif ($comparisonResult > 0) {
            $node->setRight($this->addRecursive($element, $node->right()));
        } else {
            $node->setValue($element);
        }

        return $this->balance($node);
    }

    /**
     * @param mixed $element
     */
    function remove($element) {
        $this->root = $this->removeRecursive($element, $this->root);
        $this->cache = NULL;
    }

    /**
     * @param $element
     * @param BinaryTree $node
     *
     * @return BinaryTree
     */
    protected function removeRecursive($element, BinaryTree $node = NULL) {
        if ($node === NULL) {
            return NULL;
        }

        $comparisonResult = call_user_func($this->comparator, $element, $node->value());

        if ($comparisonResult < 0) {
            $node->setLeft($this->removeRecursive($element, $node->left()));
        } elseif ($comparisonResult > 0) {
            $node->setRight($this->removeRecursive($element, $node->right()));
        } else {
            //remove the element
            $node = $this->deleteNode($node);
        }

        return $this->balance($node);
    }

    /**
     * @param BinaryTree $node
     *
     * @return BinaryTree
     */
    protected function deleteNode(BinaryTree $node) {
        $left = $node->left();
        $right = $node->right();
        if ($left === NULL) {
            $this->size--;
            if ($right === NULL) {
                // left and right empty
                return NULL;
            } else {
                // left empty, right is not
                unset($node);
                return $right;
            }
        } else {
            if ($right === NULL) {
                // right empty, left is not
                unset($node);
                return $left;
            } else {
                // neither is empty
                $value = $node->inOrderPredecessor()->value();
                $node->setLeft($this->removeRecursive($value, $node->left()));
                $node->setValue($value);
                return $node;
            }
        }
    }

    /**
     * @param $element
     *
     * @return mixed
     * @throws LookupException
     */
    function get($element) {
        $node = $this->root;

        while ($node !== NULL) {
            $comparisonResult = call_user_func($this->comparator, $element, $node->value());

            if ($comparisonResult < 0) {
                $node = $node->left();
            } elseif ($comparisonResult > 0) {
                $node = $node->right();
            } else {
                return $node->value();
            }
        }

        throw new LookupException;
    }

    /**
     * @return BinaryTree A copy of the current BinaryTree
     */
    function toBinaryTree() {
        return $this->root !== NULL
            ? clone $this->root
            : NULL;
    }

    /**
     * @return void
     */
    function clear() {
        $this->root = NULL;
        $this->size = 0;
    }

    /**
     * @param $item
     *
     * @return bool
     * @throws TypeException when $item is not the correct type.
     */
    function contains($item) {
        $node = $this->root;
        while ($node !== NULL) {
            $comparisonResult = call_user_func($this->comparator, $item, $node->value());

            if ($comparisonResult < 0) {
                $node = $node->left();
            } elseif ($comparisonResult > 0) {
                $node = $node->right();
            } else {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function first() {
        if ($this->root === NULL) {
            throw new EmptyException();
        }
        $node = $this->root;
        while (($left = $node->left()) !== NULL) {
            $node = $left;
        }
        return $node->value();
    }

    /**
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function last() {
        if ($this->root === NULL) {
            throw new EmptyException();
        }
        $node = $this->root;
        while (($right = $node->right()) !== NULL) {
            $node = $right;
        }
        return $node->value();
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return $this->root === NULL;
    }

    /**
     * @param int $order [optional]
     *
     * @return BinaryTreeIterator
     */
    function getIterator($order = self::TRAVERSE_IN_ORDER) {
        $iterator = NULL;

        $root = $this->cache ?: (
        $this->root !== NULL
            ? clone $this->root
            : NULL
        );

        switch ($order) {
            case self::TRAVERSE_LEVEL_ORDER:
                $iterator = new LevelOrderIterator($root, $this->size);
                break;

            case self::TRAVERSE_PRE_ORDER:
                $iterator = new PreOrderIterator($root, $this->size);
                break;

            case self::TRAVERSE_POST_ORDER:
                $iterator = new PostOrderIterator($root, $this->size);
                break;

            case self::TRAVERSE_IN_ORDER:
            default:
                $iterator = new InOrderIterator($root, $this->size);
        }

        return $iterator;

    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->size;
    }

    function __clone() {
        $this->root = $this->root === NULL
            ? NULL
            : clone $this->root;
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
        $leftNode = $root->left();
        $leftHeight = $leftNode->leftHeight();
        $rightHeight = $leftNode->rightHeight();

        $diff = $leftHeight - $rightHeight;

        if ($diff < 0) {
            // Left-Right case
            $pivot = $leftNode->right();
            $leftNode->setRight($pivot->left());
            $pivot->setLeft($leftNode);
            $root->setLeft($pivot);
        }

        $pivot = $root->left();
        $root->setLeft($pivot->right());
        $pivot->setRight($root);

        return $pivot;
    }

    /**
     * @param BinaryTree $root
     *
     * @return BinaryTree
     */
    protected function rotateLeft(BinaryTree $root) {
        $rightNode = $root->right();

        $diff = $rightNode->leftHeight() - $rightNode->rightHeight();

        if ($diff >= 0) {
            // Right-Left case
            $pivot = $rightNode->left();
            $rightNode->setLeft($pivot->right());
            $pivot->setRight($rightNode);
            $root->setRight($pivot);
        }


        $pivot = $root->right();
        $root->setRight($pivot->left());
        $pivot->setLeft($root);

        return $pivot;
    }

    /**
     * @param callable $f
     * @return mixed
     * @throws StateException when the tree is not empty
     */
    function setCompare(callable $f) {
        if ($this->root !== NULL) {
            throw new StateException('Cannot set compare function when the BinarySearchTree is not empty');
        }
        $this->comparator = $f;
    }

}

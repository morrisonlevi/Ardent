<?php

namespace Ardent;

use Ardent\Exception\LookupException;
use Ardent\Exception\TypeException;
use Ardent\Iterator\BinaryTreeIterator;
use Ardent\Iterator\InOrderIterator;
use Ardent\Iterator\LevelOrderIterator;
use Ardent\Iterator\PostOrderIterator;
use Ardent\Iterator\PreOrderIterator;

class BinarySearchTree implements \IteratorAggregate, Collection {

    use StructureCollection;

    const TRAVERSE_IN_ORDER = 1;
    const TRAVERSE_LEVEL_ORDER = 2;
    const TRAVERSE_PRE_ORDER = 3;
    const TRAVERSE_POST_ORDER = 4;

    /**
     * @var BinaryTree
     */
    private $root = NULL;

    /**
     * @var int
     */
    private $size = 0;

    /**
     * @var callable
     */
    protected $comparator;

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

        $comparisonResult = call_user_func($this->comparator, $element, $node->getValue());

        if ($comparisonResult < 0) {
            $node->setLeft($this->addRecursive($element, $node->getLeft()));
        } elseif ($comparisonResult > 0) {
            $node->setRight($this->addRecursive($element, $node->getRight()));
        } else {
            $node->setValue($element);
        }

        return $node;
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

        $comparisonResult = call_user_func($this->comparator, $element, $node->getValue());

        if ($comparisonResult < 0) {
            $node->setLeft($this->removeRecursive($element, $node->getLeft()));
        } elseif ($comparisonResult > 0) {
            $node->setRight($this->removeRecursive($element, $node->getRight()));
        } else {
            //remove the element
            $node = $this->deleteNode($node);
        }

        return $node;
    }

    /**
     * @param BinaryTree $node
     *
     * @return BinaryTree
     */
    protected function deleteNode(BinaryTree $node) {
        if ($node->isLeaf()) {
            $this->size--;
            return NULL;
        }

        if ($node->hasOnlyOneChild()) {
            $this->size--;

            $newNode = $node->getRight() ?: $node->getLeft();

            unset($node);
            return $newNode;
        }

        $value = $node->getInOrderPredecessor()->getValue();

        $node->setLeft($this->removeRecursive($value, $node->getLeft()));

        $node->setValue($value);

        return $node;
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
            $comparisonResult = call_user_func($this->comparator, $element, $node->getValue());

            if ($comparisonResult < 0) {
                $node = $node->getLeft();
            } elseif ($comparisonResult > 0) {
                $node = $node->getRight();
            } else {
                return $node->getValue();
            }
        }

        throw new LookupException;
    }

    /**
     * @return BinaryTree A copy of the current BinaryTree
     */
    function getBinaryTree() {
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
    function containsItem($item) {
        $node = $this->root;
        while ($node !== NULL) {
            $comparisonResult = call_user_func($this->comparator, $item, $node->getValue());

            if ($comparisonResult < 0) {
                $node = $node->getLeft();
            } elseif ($comparisonResult > 0) {
                $node = $node->getRight();
            } else {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return $this->root === NULL;
    }

    /**
     * @var BinaryTree
     */
    private $cache = NULL;

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
                $iterator = new LevelOrderIterator($root);
                break;

            case self::TRAVERSE_PRE_ORDER:
                $iterator = new PreOrderIterator($root);
                break;

            case self::TRAVERSE_POST_ORDER:
                $iterator = new PostOrderIterator($root);
                break;

            case self::TRAVERSE_IN_ORDER:
            default:
                $iterator = new InOrderIterator($root);
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
}

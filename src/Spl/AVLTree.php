<?php

namespace Spl;

class AVLTree implements \IteratorAggregate, BinarySearchTree {

    /**
     * @var BinaryNode
     */
    private $root = NULL;

    /**
     * @var int
     */
    private $size = 0;

    /**
     * @var callable
     */
    protected $comparator = NULL;

    /**
     * @param callable $comparator
     */
    function __construct($comparator = NULL) {
        if ($this->comparator === NULL) {
            $this->comparator = array($this, 'compare');
        } else {
            $this->comparator = $comparator;
        }
    }

    /**
     * @param $a
     * @param $b
     *
     * @return int
     */
    private function compare($a, $b) {
        if ($a < $b) {
            return -1;
        } else {
            if ($b < $a) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    /**
     * @param $element
     */
    public function add($element) {
        $this->root = $this->addNode($element, $this->root);
    }

    /**
     * @param $element
     * @param BinaryNode $node
     *
     * @return null|BinaryNode
     */
    protected function addNode($element, BinaryNode $node = NULL) {
        if ($node === NULL) {
            $this->size++;
            return new BinaryNode($element);
        }

        $comparisonResult = call_user_func($this->comparator, $element, $node->getValue());

        if ($comparisonResult < 0) {
            $node->setLeft($this->addNode($element, $node->getLeft()));
        } else {
            if ($comparisonResult > 0) {
                $node->setRight($this->addNode($element, $node->getRight()));
            }
        }

        $node = $this->balance($node);

        return $node;
    }

    /**
     * @param BinaryNode $node
     *
     * @return null|BinaryNode
     */
    protected function balance(BinaryNode $node = NULL) {
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
     * @param BinaryNode $root
     *
     * @return BinaryNode
     */
    protected function rotateRight(BinaryNode $root) {
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
     * @param BinaryNode $root
     *
     * @return BinaryNode
     */
    protected function rotateLeft(BinaryNode $root) {
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
     * @param BinaryNode $root
     *
     * @return BinaryNode
     */
    protected function rotateSingleRight(BinaryNode $root) {
        $pivot = $root->getLeft();
        $root->setLeft($pivot->getRight());
        $pivot->setRight($root);

        return $pivot;
    }

    /**
     * @param BinaryNode $root
     *
     * @return BinaryNode
     */
    protected function rotateSingleLeft(BinaryNode $root) {
        $pivot = $root->getRight();
        $root->setRight($pivot->getLeft());
        $pivot->setLeft($root);

        return $pivot;
    }

    /**
     * @param BinaryNode $node
     *
     * @return int
     */
    private function getHeight(BinaryNode $node = NULL) {
        if ($node === NULL) {
            return 0;
        }

        return $node->getHeight();

    }

    function remove($element) {
        $this->root = $this->removeNode($element, $this->root);
    }


    /**
     * @param $element
     * @param BinaryNode $node
     *
     * @return BinaryNode
     */
    private function removeNode($element, BinaryNode $node = NULL) {
        if ($node === NULL) {
            return NULL;
        }

        $comparisonResult = call_user_func($this->comparator, $element, $node->getValue());

        if ($comparisonResult < 0) {
            $node->setLeft($this->removeNode($element, $node->getLeft()));
        } else {
            if ($comparisonResult > 0) {
                $node->setRight($this->removeNode($element, $node->getRight()));
            } else {
                //remove the element
                $node = $this->deleteNode($node);
            }
        }

        $node = $this->balance($node);

        return $node;
    }

    function deleteNode(BinaryNode $node) {

        if ($node->isLeaf()) {
            $this->size--;
            return NULL;
        }

        if ($node->hasOnlyOneChild()) {
            $this->size--;
            $right = $node->getRight();

            $newNode = $right !== NULL
                ? $right
                : $node->getLeft();

            unset($node);
            return $newNode;
        }

        $toFind = $this->getInOrderPredecessor($node);
        $value = $toFind->getValue();

        $node->setLeft($this->removeNode($value, $node->getLeft()));

        $node->setValue($value);

        return $node;
    }

    /**
     * @param BinaryNode $node
     *
     * @return BinaryNode
     */
    protected function getInOrderPredecessor(BinaryNode $node) {

        for ($current = $node->getLeft(); $current->getRight() !== NULL; $current = $current->getRight()) ;

        return $current;

    }

    /**
     * @return void
     */
    function clear() {
        $this->root = NULL;
        $this->size = 0;
    }

    /**
     * @param $object
     *
     * @return bool
     * @throws InvalidTypeException when $object is not the correct type.
     */
    function contains($object) {
        return $this->containsNode($object, $this->root);
    }

    protected function containsNode($element, BinaryNode $node = NULL) {
        if ($node === NULL) {
            return FALSE;
        }

        $comparisonResult = call_user_func($this->comparator, $element, $node->getValue());

        if ($comparisonResult < 0) {
            return $this->containsNode($element, $node->getLeft());
        } else {
            if ($comparisonResult > 0) {
                return $this->containsNode($element, $node->getRight());
            } else {
                return TRUE;
            }
        }

    }

    /**
     * @return bool
     */
    function isEmpty() {
        return $this->root === NULL;
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count() {
        return $this->size;
    }

    /**
     * @param int $order
     * @param callable $callback
     */
    public function traverse($order, $callback) {
        if ($this->root === NULL) {
            return;
        }
        switch ($order) {
            case self::TRAVERSE_LEVEL_ORDER:
                $iterator = new LevelOrderIterator($this->root);
                break;

            case self::TRAVERSE_PRE_ORDER:
                $iterator = new PreOrderIterator($this->root);
                break;

            case self::TRAVERSE_POST_ORDER:
                $iterator = new PostOrderIterator($this->root);
                break;

            case self::TRAVERSE_IN_ORDER:
            default:
                $iterator = new InOrderIterator($this->root);
                break;
        }

        foreach ($iterator as $item) {
            $callback($item);
        }
    }

    protected function preOrder(BinaryNode $node, $callback) {
        $left = $node->getLeft();
        $right = $node->getRight();

        $callback($node->getValue());
        if ($left !== NULL) {
            $this->preOrder($left, $callback);
        }
        if ($right !== NULL) {
            $this->preOrder($right, $callback);
        }
    }

    protected function postOrder(BinaryNode $node, $callback) {
        $left = $node->getLeft();
        $right = $node->getRight();

        if ($left !== NULL) {
            $this->postOrder($left, $callback);
        }
        if ($right !== NULL) {
            $this->postOrder($right, $callback);
        }
        $callback($node->getValue());
    }

    /**
     * @param int $order [optional]
     *
     * @return \Traversable
     */
    public function getIterator($order = self::TRAVERSE_IN_ORDER) {
        switch ($order) {
            case self::TRAVERSE_LEVEL_ORDER:
                return new LevelOrderIterator($this->root);
                break;

            case self::TRAVERSE_PRE_ORDER:
                return new PreOrderIterator($this->root);
                break;

            case self::TRAVERSE_POST_ORDER:
                return new PostOrderIterator($this->root);
                break;

            case self::TRAVERSE_IN_ORDER:
            default:
                return new InOrderIterator($this->root);
                break;
        }

    }

    /**
     * @return BinaryNode
     */
    protected function getRoot() {
        return $this->root;
    }

}

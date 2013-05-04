<?php

namespace Ardent;

use Ardent\Exception\EmptyException,
    Ardent\Exception\LookupException,
    Ardent\Exception\StateException,
    Ardent\Iterator\BinaryTreeIterator,
    Ardent\Iterator\InOrderIterator,
    Ardent\Iterator\LevelOrderIterator,
    Ardent\Iterator\PostOrderIterator,
    Ardent\Iterator\PreOrderIterator;

class SplayTree implements BinarySearchTree {

    use StructureCollection;

    /**
     * @var SplayNode
     */
    private $root;

    private $size = 0;

    /**
     * @var callable
     */
    private $comparator;

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

    function containsItem($element) {
        return $this->find($element, $this->root) !== NULL;
    }

    /**
     * @param $element
     * @param SplayNode $node
     * @return SplayNode|null
     */
    protected function find($element, $node) {
        if ($node === NULL) {
            return NULL;
        }
        $n = $this->root;
        loop: {
            $comparisonResult = call_user_func($this->comparator, $element, $n->value);

            if ($comparisonResult < 0 && $n->left !== NULL) {
                    $n = $n->left;
                    goto loop;

            } elseif ($comparisonResult > 0 && $n->right !== NULL) {
                    $n = $n->right;
                    goto loop;
            } elseif ($comparisonResult == 0) {
                $this->splay($n);
                return $n;
            }
        }
        return NULL;
    }

    /**
     * @param mixed $element
     *
     * Goto is the best solution here as any loop structure in PHP will add at least one more comparison and probably
     * a another jump as well. Additionally, it's still pretty readable, so I have no qualms about it *at all*.
     */
    function add($element) {

        if ($this->root === NULL) {
            $this->root = new SplayNode($element);
            $this->size++;
            return;
        }
        $n = $this->root;
        loop: {
            $comparisonResult = call_user_func($this->comparator, $element, $n->value);

            if ($comparisonResult < 0) {
                if ($n->left !== NULL) {
                    $n = $n->left;
                    goto loop;
                } else {
                    $n = $n->left = new SplayNode($element, $n);
                    $this->size++;
                }

            } elseif ($comparisonResult > 0) {
                if ($n->right !== NULL) {
                    $n = $n->right;
                    goto loop;
                } else {
                    $n = $n->right = new SplayNode($element, $n);
                    $this->size++;
                }
            } else {
                $n->value = $element;
            }
        }
        $this->splay($n);
    }

    protected function splay(SplayNode $n) {
        while ($n->parent !== NULL) {
            if (!$n->parent->parent) {
                if ($n->parent->left == $n) {
                    $this->rotateRight($n->parent);
                } else {
                    $this->rotateLeft($n->parent);
                }
            } elseif ($n->parent->left == $n && $n->parent->parent->left == $n->parent) {
                $this->rotateRight($n->parent->parent);
                $this->rotateRight($n->parent);
                
            } elseif ($n->parent->right == $n && $n->parent->parent->right == $n->parent) {
                $this->rotateLeft($n->parent->parent);
                $this->rotateLeft($n->parent);
                
            } elseif ($n->parent->left == $n && $n->parent->parent->right == $n->parent) {
                $this->rotateRight($n->parent);
                $this->rotateLeft($n->parent);
                
            } else {
                $this->rotateLeft($n->parent);
                $this->rotateRight($n->parent);
            }
        }
    }

    protected function rotateRight(SplayNode $x) {
        $y = $x->left;
        $x->left = $y->right;
        if ($y->right) {
            $y->right->parent = $x;
        }
        $y->parent = $x->parent;
        if (!$x->parent) {
            $this->root = $y;
        } elseif ($x == $x->parent->left) {
            $x->parent->left = $y;
        } else {
            $x->parent->right = $y;
        }
        $y->right = $x;
        $x->parent = $y;
    }

    protected function rotateLeft(SplayNode $x) {
        $y = $x->right;
        $x->right = $y->left;
        if ($y->left) {
            $y->left->parent = $x;
        }
        $y->parent = $x->parent;
        if (!$x->parent) {
            $this->root = $y;
        } elseif ($x == $x->parent->left) {
            $x->parent->left = $y;
        } else {
            $x->parent->right = $y;
        }
        $y->left = $x;
        $x->parent = $y;
    }

    /**
     * @return BinaryTree
     */
    function toBinaryTree() {
        if ($this->root !== NULL) {
            return $this->convertSplayNodeToBinaryTree($this->root);
        }
        return NULL;
    }

    /**
     * @param SplayNode $splay
     * @return BinaryTree
     */
    private function convertSplayNodeToBinaryTree(SplayNode $splay) {
        $tree = new BinaryTree($splay->value);
        if ($splay->left) {
            $tree->setLeft($this->convertSplayNodeToBinaryTree($splay->left));
        }
        if ($splay->right) {
            $tree->setRight($this->convertSplayNodeToBinaryTree($splay->right));
        }
        return $tree;
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->size;
    }

    /**
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function findFirst() {
        if ($this->root === NULL) {
            throw new EmptyException();
        }
        $n = $this->root;
        while ($n->left !== NULL) {
            $n = $n->left;
        }
        $this->splay($n);
        return $n->value;
    }

    /**
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function findLast() {
        if ($this->root === NULL) {
            throw new EmptyException();
        }
        $n = $this->findMax($this->root);
        return $n->value;
    }

    private function findMax(SplayNode $n) {
        while ($n->right !== NULL) {
            $n = $n->right;
        }
        $this->splay($n);
        return $n;
    }

    /**
     * @param mixed $element
     */
    function remove($element) {
        $n = $this->find($element, $this->root);
        if ($n === NULL) {
            return;
        }

        $left = $n->left;
        $right = $n->right;
        if ($left === NULL) {
            $this->root = $right;
            if ($right !== NULL) {
                $right->parent = NULL;
            }

        } else {
            $left->parent = NULL;
            $this->root = $this->findMax($left);
            $this->root->right = $right;
            if ($right !== NULL) {
                $right->parent = $this->root;
            }
        }
        $this->size--;

    }

    /**
     * @param $element
     *
     * @return mixed
     * @throws LookupException
     */
    function get($element) {
        $n = $this->find($element, $this->root);
        if ($n === NULL) {
            throw new LookupException;
        }
        return $n->value;
    }

    /**
     * @return void
     */
    function clear() {
        $this->root = NULL;
        $this->size = 0;
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

        $root = $this->toBinaryTree();

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
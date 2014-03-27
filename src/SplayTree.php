<?php

namespace Collections;

class SplayTree implements BinarySearchTree {

    use IteratorCollection;

    /**
     * @var SplayNode
     */
    private $root;
    /**
     * @var callable
     */
    private $comparator;

    private $header;

    private $size = 0;

    function __construct(callable $comparator = NULL) {
        $this->comparator = $comparator ? : [$this, 'compare'];
        $this->header = new SplayNode(null);
    }

    /**
     * @param callable $f
     * @return mixed
     * @throws StateException when the tree is not empty
     */
    function setCompare(callable $f) {
        if ($this->root !== NULL) {
            throw new StateException;
        }
        $this->comparator = $f;
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

    function toBinaryTree() {
        return $this->copyNode($this->root);
    }

    private function copyNode(SplayNode $n = NULL) {
        if ($n === NULL) {
            return NULL;
        }
        $new = new BinaryTree($n->value);
        $new->setLeft($this->copyNode($n->left));
        $new->setRight($this->copyNode($n->right));
        return $new;
    }

    /**
     * Insert into the tree.
     * @param mixed $value the item to insert.
     * @return void
     */
    function add($value) {
        $n = null;
        if ($this->root == null) {
            $this->root = new SplayNode($value);
            $this->size++;
            return;
        }
        $this->splay($value);
        if (($c = call_user_func($this->comparator, $value, $this->root->value)) === 0) {
            $this->root->value = $value;
            return;
        }
        $n = new SplayNode($value);
        $this->size++;
        if ($c < 0) {
            $n->left = $this->root->left;
            $n->right = $this->root;
            $this->root->left = null;
        }
        else {
            $n->right = $this->root->right;
            $n->left = $this->root;
            $this->root->right = null;
        }
        $this->root = $n;
    }

    private function splay($value) {
        $l = null;
        $r = null;
        $t = null;
        $y = null;

        $l = $r = $this->header;
        $t = $this->root;
        $this->header->left = $this->header->right = null;
        for (; ;) {
            if (call_user_func($this->comparator, $value, $t->value) < 0) {
                if ($t->left == null) {
                    break;
                }
                if (call_user_func($this->comparator, $value, $t->left->value) < 0) {
                    $y = $t->left; /* rotate right */
                    $t->left = $y->right;
                    $y->right = $t;
                    $t = $y;
                    if ($t->left == null) {
                        break;
                    }
                }
                $r->left = $t; /* link right */
                $r = $t;
                $t = $t->left;
            }
            else if (call_user_func($this->comparator, $value, $t->value) > 0) {
                if ($t->right == null) {
                    break;
                }
                if (call_user_func($this->comparator, $value, $t->right->value) > 0) {
                    $y = $t->right; /* rotate left */
                    $t->right = $y->left;
                    $y->left = $t;
                    $t = $y;
                    if ($t->right == null) {
                        break;
                    }
                }
                $l->right = $t; /* link left */
                $l = $t;
                $t = $t->right;
            }
            else {
                break;
            }
        }
        $l->right = $t->left; /* assemble */
        $r->left = $t->right;
        $t->left = $this->header->right;
        $t->right = $this->header->left;
        $this->root = $t;
    }

    /**
     * Remove from the tree.
     * @param mixed $value the item to remove.
     */
    function remove($value) {
        if ($this->root === NULL) {
            return;
        }
        $this->splay($value);
        if (call_user_func($this->comparator, $value, $this->root->value) !== 0) {
            return;
        }
        // Now delete the $this->root
        $this->size--;
        if ($this->root->left == null) {
            $this->root = $this->root->right;
        }
        else {
            $x = $this->root->right;
            $this->root = $this->root->left;
            $this->splay($value);
            $this->root->right = $x;
        }
    }

    /**
     * Find the smallest item in the tree.
     */
    function first() {
        $x = $this->root;
        if ($this->root == null) {
            throw new EmptyException;
        }
        while ($x->left != null) {
            $x = $x->left;
        }
        $this->splay($x->value);
        return $x->value;
    }

    /**
     * Find the largest item in the tree.
     */
    function last() {
        $x = $this->root;
        if ($this->root == null) {
            throw new EmptyException;
        }
        while ($x->right != null) {
            $x = $x->right;
        }
        $this->splay($x->value);
        return $x->value;
    }

    /**
     * Find an item in the tree.
     */
    function get($value) {
        if ($this->root == null) {
            throw new LookupException;
        }
        $this->splay($value);
        if (call_user_func($this->comparator, $this->root->value, $value) !== 0) {
            throw new LookupException;
        }
        return $this->root->value;
    }

    /**
     * @param $item
     * @return bool
     */
    function contains($item) {
        if ($this->root == null) {
            return FALSE;
        }
        $this->splay($item);
        return call_user_func($this->comparator, $this->root->value, $item) === 0;
    }

    /**
     * Test if the tree is logically empty.
     * @return bool true if empty, false otherwise.
     */
    function isEmpty() {
        return $this->root == null;
    }

    function count() {
        return $this->size;
    }

    function clear() {
        $this->root = NULL;
        $this->header = new SplayNode(null);
        $this->size = 0;
    }

    /**
     * @param int $order [optional]
     *
     * @return BinaryTreeIterator
     */
    function getIterator($order = self::TRAVERSE_IN_ORDER) {
        $root = $this->copyNode($this->root);
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
                $iterator = new InOrderIterator($root, 0);
        }

        return $iterator;
    }

}
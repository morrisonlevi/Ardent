<?php

namespace Collections;

class SplayTree implements BinarySearchTree {

    use EmptyGuard;
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
        $this->comparator = $comparator ? : '\Collections\compare';
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


    function toBinaryTree() {
        return $this->copyNode($this->root);
    }


    /**
     * Insert into the tree.
     * @param mixed $value the item to insert.
     * @return void
     */
    function add($value) {
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
        $this->emptyGuard(__METHOD__);
        return $this->farthest('left', $this->root);
    }


    /**
     * Find the largest item in the tree.
     */
    function last() {
        $this->emptyGuard(__METHOD__);
        return $this->farthest('right', $this->root);
    }


    /**
     * Find an item in the tree.
     * @param mixed $value
     * @throws LookupException
     * @return mixed
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
     * @return BinaryTreeIterator
     */
    function getIterator() {
        $root = $this->copyNode($this->root);
        return new InOrderIterator($root, 0);
    }


    /**
     * @param $direction
     * @param SplayNode $context
     * @return SplayNode
     */
    private function farthest($direction, SplayNode $context) {
        for ($n = $context; $n->$direction != null; $n = $n->$direction);
        $this->splay($n->value);
        return $n->value;
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


    private function rotateRight(SplayNode $t) {
        $y = $t->left;
        $t->left = $y->right;
        $y->right = $t;
        return $y;
    }


    private function rotateLeft(SplayNode $t) {
        $y = $t->right;
        $t->right = $y->left;
        $y->left = $t;
        return $y;
    }


    private function splay_rotate($property, $rotate, $value, $t, $d) {
        if ($t->$property == null) {
            return [false, $t, $d];
        }
        if (call_user_func($this->comparator, $value, $t->$property->value) > 0) {
            $t = $this->$rotate($t);
            if ($t->$property == null) {
                return [false, $t, $d];
            }
        }
        $d->$property = $t;
        $d = $t;
        $t = $t->$property;
        return [true, $t, $d];
    }


    private function splay($value) {
        $l = $r = $this->header;
        $t = $this->root;
        $this->header->left = $this->header->right = null;

        do {
            $continue = false;
            $result = call_user_func($this->comparator, $value, $t->value) ;
            if ($result < 0) {
                list($continue, $t, $r) = $this->splay_rotate('left', 'rotateRight', $value, $t, $r);
            }
            else if ($result > 0) {
                list($continue, $t, $l) = $this->splay_rotate('right', 'rotateLeft', $value, $t, $l);
            }
        } while($continue);

        $l->right = $t->left; /* assemble */
        $r->left = $t->right;
        $t->left = $this->header->right;
        $t->right = $this->header->left;
        $this->root = $t;
    }


}
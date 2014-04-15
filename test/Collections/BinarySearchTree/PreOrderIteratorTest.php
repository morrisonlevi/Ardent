<?php

namespace Collections;


class PreOrderIteratorTest extends BinaryTreeIteratorTest {


    function instance(BinaryTree $root = null, $count = 0) {
        return new PreOrderIterator($root, $count);
    }


    function test_toArray() {
        $tree = new BinaryTree(0);
        $tree->setLeft(new BinaryTree(-4));
        $tree->left()->setLeft(new BinaryTree(1));
        $tree->left()->setRight(new BinaryTree(2));

        $iterator = $this->instance($tree, 4);
        $expect = [0, -4, 1, 2];
        $actual = $iterator->toArray();
        $this->assertEquals($expect, $actual);
    }


} 
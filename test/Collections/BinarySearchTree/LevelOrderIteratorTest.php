<?php

namespace Collections;


class LevelOrderIteratorTest extends BinaryTreeIteratorTest {


    function instance(BinaryTree $root = null, $count = 0) {
        return new LevelOrderIterator($root, $count);
    }


    function test_toArray() {
        $tree = new BinaryTree(0);
        $tree->setLeft(new BinaryTree(-4));
        $tree->left->setLeft(new BinaryTree(1));
        $tree->left->setRight(new BinaryTree(2));


        $tree->setRight(new BinaryTree(4));

        $iterator = $this->instance($tree, 5);
        $expect = [0, -4, 4, 1, 2];
        $actual = $iterator->toArray();
        $this->assertEquals($expect, $actual);
    }


} 
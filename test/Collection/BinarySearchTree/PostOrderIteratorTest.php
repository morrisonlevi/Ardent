<?php

namespace Ardent\Collection;


class PostOrderIteratorTest extends BinaryTreeIteratorTest {


    function instance(BinaryTree $root = null, $count = 0) {
        return new PostOrderIterator($root, $count);
    }


    function test_toArray() {
        $tree = new BinaryTree(0);
        $tree->setLeft(new BinaryTree(-4));
        $tree->left()->setLeft(new BinaryTree(1));
        $tree->left()->setRight(new BinaryTree(2));

        $iterator = $this->instance($tree, 4);
        $expect = [1, 2, -4, 0];
        $actual = iterator_to_array($iterator);
        $this->assertEquals($expect, $actual);
    }


} 
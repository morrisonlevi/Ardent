<?php

namespace Collections;

class AvlTreeTest extends \PHPUnit_Framework_TestCase {

    function testInitiallyEmpty() {
        $object = new AvlTree();
        $this->assertTrue($object->isEmpty());
        $this->assertCount(0, $object);
    }

    /**
     * @expectedException \Collections\EmptyException
     */
    function testFindFirstEmpty() {
        $tree = new AvlTree();
        $tree->first();
    }

    function testFindFirst() {
        $tree = new AvlTree();
        $tree->add(1);
        $tree->add(0);
        $tree->add(2);
        $tree->add(-1);

        $expected = -1;
        $actual = $tree->first();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Collections\EmptyException
     */
    function testFindLastEmpty() {
        $tree = new AvlTree();
        $tree->last();
    }

    function testFindLast() {
        $tree = new AvlTree();
        $tree->add(1);
        $tree->add(0);
        $tree->add(2);

        $expected = 2;
        $actual = $tree->last();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Collections\StateException
     */
    function testSetCompareException() {
        $tree = new AvlTree();
        $tree->add(1);
        $tree->setCompare('max');
    }

    function testSetCompare() {
        $tree = new AvlTree();
        $tree->setCompare(function($a, $b) {
            if ($a < $b) {
                return 1;
            } elseif ($b < $a) {
                return -1;
            } else {
                return 0;
            }
        });
        $tree->add(1);
        $tree->add(2);
        $tree->add(0);

        $binary = $tree->toBinaryTree();
        $this->assertInstanceOf('Collections\\BinaryTree', $binary);
        $this->assertNotNull($binary->left());
        $this->assertEquals(2, $binary->left()->value());
        $this->assertNotNull($binary->right());
        $this->assertEquals(0, $binary->right()->value());
    }

    function testRemoveNonExistingItem() {
        $object = new AvlTree();
        $object->remove(1);
    }

    function testRemoveRootBasic() {
        $object = new AvlTree();
        $object->add(5);
        $object->remove(5);

        $root = $object->toBinaryTree();
        $this->assertNull($root);
    }

    function testRemoveLeaf() {
        $object = new AvlTree();
        $object->add(4);
        $object->add(3);
        $object->add(5);

        $object->remove(3);

        $root = new BinaryTree(4);
        $root->setRight(new BinaryTree(5));
        $this->reCalculateHeights($root);

        $this->assertEquals($root, $object->toBinaryTree());
    }

    function testRemoveWithLeftChild() {
        $object = new AvlTree();
        $object->add(4);
        $object->add(3);
        $object->add(5);
        $object->add(1);

        $object->remove(3);

        $root = new BinaryTree(4);
        $root->setLeft(new BinaryTree(1));
        $root->setRight(new BinaryTree(5));
        $this->reCalculateHeights($root);

        $this->assertEquals($root, $object->toBinaryTree());
    }

    function testRemoveWithRightChild() {
        $object = new AvlTree();
        $object->add(4);
        $object->add(3);
        $object->add(5);
        $object->add(7);

        $object->remove(5);

        $root = new BinaryTree(4);
        $root->setLeft(new BinaryTree(3));
        $root->setRight(new BinaryTree(7));
        $this->reCalculateHeights($root);

        $this->assertEquals($root, $object->toBinaryTree());
    }

    function testAddItemThatAlreadyExists() {
        $object = new AvlTree();
        $object->add(4);
        $object->add(4);

        $expectedRoot = new BinaryTree(4);
        $actualRoot = $object->toBinaryTree();

        $this->reCalculateHeights($expectedRoot);

        $this->assertEquals($expectedRoot, $actualRoot);

    }

    function testRemoveWithBothChildren() {
        $object = new AvlTree();
        $object->add(4);
        $object->add(3);
        $object->add(5);

        $object->remove(4);

        $root = new BinaryTree(3);
        $root->setRight(new BinaryTree(5));
        $this->reCalculateHeights($root);

        $this->assertEquals($root, $object->toBinaryTree());
    }

    function testRemoveWhereInOrderPredecessorHasChild() {
        $object = new AvlTree();
        //          5
        //        /    \
        //       2      9
        //     /  \    / \
        //    1    4  8  11
        //        /
        //       3
        $object->add(5);
        $object->add(2);
        $object->add(9);
        $object->add(1);
        $object->add(4);
        $object->add(8);
        $object->add(11);
        $object->add(3);

        // build a test tree to validate that we are set up correctly
        $expectedRoot = new BinaryTree(5);
        $expectedRoot->setLeft(new BinaryTree(2));
        $expectedRoot->left()->setLeft(new BinaryTree(1));
        $expectedRoot->left()->setRight(new BinaryTree(4));
        $expectedRoot->left()->right()->setLeft(new BinaryTree(3));
        $expectedRoot->setRight(new BinaryTree(9));
        $expectedRoot->right()->setLeft(new BinaryTree(8));
        $expectedRoot->right()->setRight(new BinaryTree(11));

        $this->reCalculateHeights($expectedRoot);

        $actualRoot = $object->toBinaryTree();

        $this->assertEquals($expectedRoot, $actualRoot);

        // okay, now for the real test:
        $object->remove(5);

        //          4
        //        /    \
        //       2      9
        //     /  \    / \
        //    1    3  8  11

        $expectedRoot = new BinaryTree(4);
        $expectedRoot->setLeft(new BinaryTree(2));
        $expectedRoot->left()->setLeft(new BinaryTree(1));
        $expectedRoot->left()->setRight(new BinaryTree(3));
        $expectedRoot->setRight(new BinaryTree(9));
        $expectedRoot->right()->setLeft(new BinaryTree(8));
        $expectedRoot->right()->setRight(new BinaryTree(11));

        $this->reCalculateHeights($expectedRoot);

        $actualRoot = $object->toBinaryTree();

        $this->assertEquals($expectedRoot, $actualRoot);
    }

    private function reCalculateHeights(BinaryTree $root = NULL) {
        if ($root === NULL) {
            return;
        }
        $this->reCalculateHeights($root->left());
        $this->reCalculateHeights($root->right());
        $root->recalculateHeight();

    }

    /**
     * @covers \Collections\AvlTree::clear
     * @covers \Collections\AvlTree::count
     */
    function testClear() {
        $object = new AvlTree();
        $object->add(5);

        $object->clear();

        $this->assertNull($object->toBinaryTree());
        $this->assertEmpty($object->count());
    }

    function testContains() {
        $object = new AvlTree();
        $this->assertFalse($object->contains(1));

        $object->add(1);
        $this->assertTrue($object->contains(1));
    }

    function testContainsRightSubTree() {
        $object = new AvlTree();
        $object->add(2);
        $object->add(3);
        $this->assertTrue($object->contains(3));
        $this->assertFalse($object->contains(1));
    }

    function testContainsLeftSubTree() {
        $object = new AvlTree();
        $object->add(2);
        $object->add(1);
        $this->assertTrue($object->contains(1));
        $this->assertFalse($object->contains(3));
    }

    function testGet() {
        $object = new AvlTree();
        $object->add(1);
        $this->assertEquals(1, $object->get(1));
    }

    function testGetRightSubTree() {
        $object = new AvlTree();
        $object->add(2);
        $object->add(3);
        $this->assertEquals(3, $object->get(3));
    }

    function testGetLeftSubTree() {
        $object = new AvlTree();
        $object->add(2);
        $object->add(1);
        $this->assertEquals(1, $object->get(1));
    }

    /**
     * @expectedException \Collections\LookupException
     */
    function testGetMissingGreaterThan() {
        $object = new AvlTree();
        $object->add(2);

        $object->get(3);
    }

    /**
     * @expectedException \Collections\LookupException
     */
    function testGetMissingSmallerThan() {
        $object = new AvlTree();
        $object->add(2);
        $object->get(1);
    }

    function testDefaultIterator() {
        $object = new AvlTree();
        $iterator = $object->getIterator();

        $this->assertInstanceOf('\\Collections\\InOrderIterator', $iterator);
    }

    /**
     * @depends testDefaultIterator
     */
    function testIteratorCount() {
        $object = new AvlTree();
        $object->add(0);
        $object->add(4);
        $object->add(2);
        $object->add(3);
        $iterator = $object->getIterator();
        $this->assertCount(4, $iterator);
    }

    function testGetBinaryTree() {
        $object = new AvlTree();
        $this->assertNull($object->toBinaryTree());
    }

    function testConstructor() {
        $avl = new AvlTree(function ($a, $b) {
            if ($a < $b) {
                return 1;
            } elseif ($b < $a) {
                return -1;
            } else {
                return 0;
            }
        });

        $avl->add(4);
        $avl->add(3);
        $avl->add(5);

        $root = new BinaryTree(4);
        $root->setLeft(new BinaryTree(5));
        $root->setRight(new BinaryTree(3));

        $this->reCalculateHeights($root);
        $this->assertEquals($root, $avl->toBinaryTree());
    }

    function test__clone() {
        $avl = new AvlTree();
        $avl->add(1);
        $avl->add(0);
        $avl->add(2);

        $copy = clone $avl;
        $this->assertCount(3, $copy);

        $avl->add(3);
        $this->assertCount(3, $copy);
        $this->assertCount(4, $avl);
    }

}

<?php

namespace Ardent;

class AvlTreeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    function initiallyEmpty() {
        $object = new AvlTree();
        $this->assertTrue($object->isEmpty());
        $this->assertCount(0, $object);
    }

    /**
     * @test
     */
    function addingTwoElementsGivesSizeTwo() {
        $object = new AvlTree();
        $object->add(3);
        $object->add(4);

        $this->assertFalse($object->isEmpty());
        $this->assertCount(2, $object);
    }

    /**
     * @test
     */
    function caseRightRightBalances() {
        $object = new AvlTree();
        $object->add(3);
        $object->add(4);
        $object->add(5);

        $root = new BinaryTree(4);
        $root->setLeft(new BinaryTree(3));
        $root->setRight(new BinaryTree(5));

        $this->reCalculateHeights($root);
        $actualRoot = $object->toBinaryTree();

        $this->assertEquals($root, $actualRoot);
    }

    /**
     * @expectedException \Ardent\Exception\EmptyException
     */
    function testFindFirstEmpty() {
        $tree = new AvlTree();
        $tree->findFirst();
    }

    /**
     * @expectedException \Ardent\Exception\EmptyException
     */
    function testFindLastEmpty() {
        $tree = new AvlTree();
        $tree->findLast();
    }

    /**
     * @test
     */
    function caseLeftLeftBalances() {
        $object = new AvlTree();
        $object->add(5);
        $object->add(4);
        $object->add(3);

        $root = new BinaryTree(4);
        $root->setLeft(new BinaryTree(3));
        $root->setRight(new BinaryTree(5));

        $this->reCalculateHeights($root);
        $this->assertEquals($root, $object->toBinaryTree());
    }

    /**
     * @test
     */
    function caseLeftRightBalances() {
        $object = new AvlTree();
        $object->add(5);
        $object->add(3);
        $object->add(4);


        $root = new BinaryTree(4);
        $root->setLeft(new BinaryTree(3));
        $root->setRight(new BinaryTree(5));

        $actualRoot = $object->toBinaryTree();
        $this->assertEquals($root, $actualRoot);

    }

    function testCaseRightLeft() {
        $object = new AvlTree();
        $object->add(5);
        $object->add(1);
        $object->add(8);
        $object->add(7);
        $object->add(9);

        // triggers the double rotate
        $object->add(6);

        // original tree before rotate
        //      5
        //    /   \
        //   1    8
        //       / \
        //      7  9
        //     /
        //    6
        //
        // resulting tree should be
        //      7
        //    /   \
        //   5    8
        //  / \    \
        // 1  6    9


        $root = new BinaryTree(7);
        $root->setLeft(new BinaryTree(5));
        $root->setRight(new BinaryTree(8));
        $root->getLeft()->setLeft(new BinaryTree(1));
        $root->getLeft()->setRight(new BinaryTree(6));
        $root->getRight()->setRight(new BinaryTree(9));

        $this->reCalculateHeights($root);
        $actualRoot = $object->toBinaryTree();
        $this->assertEquals($root, $actualRoot);

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

    /**
     * @depends caseRightRightBalances
     * @depends caseLeftLeftBalances
     */
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

    /**
     * @depends caseRightRightBalances
     * @depends caseLeftLeftBalances
     */
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

    /**
     * @depends caseRightRightBalances
     * @depends caseLeftLeftBalances
     */
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
        $expectedRoot->getLeft()->setLeft(new BinaryTree(1));
        $expectedRoot->getLeft()->setRight(new BinaryTree(4));
        $expectedRoot->getLeft()->getRight()->setLeft(new BinaryTree(3));
        $expectedRoot->setRight(new BinaryTree(9));
        $expectedRoot->getRight()->setLeft(new BinaryTree(8));
        $expectedRoot->getRight()->setRight(new BinaryTree(11));

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
        $expectedRoot->getLeft()->setLeft(new BinaryTree(1));
        $expectedRoot->getLeft()->setRight(new BinaryTree(3));
        $expectedRoot->setRight(new BinaryTree(9));
        $expectedRoot->getRight()->setLeft(new BinaryTree(8));
        $expectedRoot->getRight()->setRight(new BinaryTree(11));

        $this->reCalculateHeights($expectedRoot);

        $actualRoot = $object->toBinaryTree();

        $this->assertEquals($expectedRoot, $actualRoot);
    }

    private function reCalculateHeights(BinaryTree $root = NULL) {
        if ($root === NULL) {
            return;
        }
        $this->reCalculateHeights($root->getLeft());
        $this->reCalculateHeights($root->getRight());
        $root->recalculateHeight();

    }

    /**
     * @covers \Ardent\AvlTree::clear
     * @covers \Ardent\AvlTree::count
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
        $this->assertFalse($object->containsItem(1));

        $object->add(1);
        $this->assertTrue($object->containsItem(1));
    }

    function testContainsRightSubTree() {
        $object = new AvlTree();
        $object->add(2);
        $object->add(3);
        $this->assertTrue($object->containsItem(3));
        $this->assertFalse($object->containsItem(1));
    }

    function testContainsLeftSubTree() {
        $object = new AvlTree();
        $object->add(2);
        $object->add(1);
        $this->assertTrue($object->containsItem(1));
        $this->assertFalse($object->containsItem(3));
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
     * @expectedException \Ardent\Exception\LookupException
     */
    function testGetMissingGreaterThan() {
        $object = new AvlTree();
        $object->add(2);

        $object->get(3);
    }

    /**
     * @expectedException \Ardent\Exception\LookupException
     */
    function testGetMissingSmallerThan() {
        $object = new AvlTree();
        $object->add(2);
        $object->get(1);
    }

    function testDefaultIterator() {
        $object = new AvlTree();
        $iterator = $object->getIterator();

        $this->assertInstanceOf('\\Ardent\\Iterator\\InOrderIterator', $iterator);
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

    function testEmptyTreeIterators() {
        $object = new AvlTree();

        $iterators = array(
            'InOrder' => $object->getIterator(AvlTree::TRAVERSE_IN_ORDER),
            'PreOrder' => $object->getIterator(AvlTree::TRAVERSE_PRE_ORDER),
            'PostOrder' => $object->getIterator(AvlTree::TRAVERSE_POST_ORDER),
            'LevelOrder' => $object->getIterator(AvlTree::TRAVERSE_LEVEL_ORDER),
        );

        foreach ($iterators as $algorithm => $iterator) {
            /**
             * @var \Iterator $iterator
             */
            $this->assertInstanceOf("\\Ardent\\Iterator\\{$algorithm}Iterator", $iterator);

            $iterator->rewind();
            $this->assertFalse($iterator->valid());
        }
    }

    function testGetIteratorA() {
        $object = new AvlTree();
        //          5
        //        /    \
        //       2      8
        //        \      \
        //        3      11
        $object->add(5);
        $object->add(2);
        $object->add(8);
        $object->add(11);
        $object->add(3);

        $expectedSequences = array(
            'inOrder' => array(2,3,5,8,11),
            'preOrder' => array(5,2,3,8,11),
            'postOrder' => array(3,2,11,8,5),
            'levelOrder' => array(5,2,8,3,11),
        );

        $this->__testIterators($object, $expectedSequences);

    }

    function testGetIteratorB() {
        $object = new AvlTree();
        //          5
        //        /     \
        //       2      8
        //      / \
        //     1  3
        $object->add(5);
        $object->add(2);
        $object->add(8);
        $object->add(1);
        $object->add(3);

        $expectedSequences = array(
            'inOrder' => array(1,2,3,5,8),
            'preOrder' => array(5,2,1,3,8),
            'postOrder' => array(1,3,2,8,5),
            'levelOrder' => array(5,2,8,1,3),
        );

        $this->__testIterators($object, $expectedSequences);

    }

    function testGetIteratorC() {
        $object = new AvlTree();
        //          5
        //        /     \
        //       2      8
        //             / \
        //            6  9
        $object->add(5);
        $object->add(2);
        $object->add(8);
        $object->add(6);
        $object->add(9);

        $expectedSequences = array(
            'inOrder' => array(2,5,6,8,9),
            'preOrder' => array(5,2,8,6,9),
            'postOrder' => array(2,6,9,8,5),
            'levelOrder' => array(5,2,8,6,9),
        );

        $this->__testIterators($object, $expectedSequences);

    }

    function testGetIteratorD() {
        $object = new AvlTree();
        //          5
        //        /
        //       2
        $object->add(5);
        $object->add(2);

        $expectedSequences = array(
            'inOrder' => array(2,5),
            'preOrder' => array(5,2),
            'postOrder' => array(2,5),
            'levelOrder' => array(5,2),
        );

        $this->__testIterators($object, $expectedSequences);

    }

    function testGetIteratorE() {
        $object = new AvlTree();
        //     0
        //      \
        //       2
        $object->add(0);
        $object->add(2);

        $expectedSequences = array(
            'inOrder' => array(0,2),
            'preOrder' => array(0,2),
            'postOrder' => array(2,0),
            'levelOrder' => array(0,2),
        );

        $this->__testIterators($object, $expectedSequences);

    }

    private function __testIterators(AvlTree $object, array $expectedSequences) {

        $iterators = array(
            'inOrder' => $object->getIterator(AvlTree::TRAVERSE_IN_ORDER),
            'preOrder' => $object->getIterator(AvlTree::TRAVERSE_PRE_ORDER),
            'postOrder' => $object->getIterator(AvlTree::TRAVERSE_POST_ORDER),
            'levelOrder' => $object->getIterator(AvlTree::TRAVERSE_LEVEL_ORDER),
        );

        foreach ($iterators as $algorithm => $iterator) {
            $actualSequence = array();
            $expectedKey = 0;
            foreach ($iterator as $key => $item) {
                $actualSequence[] = $item;
                $this->assertEquals($expectedKey++, $key);
            }

            $this->assertEquals($expectedSequences[$algorithm], $actualSequence);
        }

    }

    function testGetInOrderPredecessorBasic() {
        $root = new BinaryTree(5);
        $inOrderPredecessor = new BinaryTree(2);
        $root->setLeft($inOrderPredecessor);
        $root->setRight(new BinaryTree(6));

        $this->assertEquals($inOrderPredecessor, $root->getInOrderPredecessor());
    }

    function testGetInOrderPredecessorWithLeftNodeHavingRightSubTree() {
        $root = new BinaryTree(5);
        $left = new BinaryTree(2);
        $root->setLeft($left);
        $root->setRight(new BinaryTree(6));

        $inOrderPredecessor = new BinaryTree(3);
        $left->setRight($inOrderPredecessor);

        $this->assertEquals($inOrderPredecessor, $root->getInOrderPredecessor());
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

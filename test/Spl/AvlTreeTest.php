<?php

namespace Spl;

class AvlTreeHelper extends AvlTree {

    public function getRoot() {
        return parent::getRoot();
    }

    public function getInOrderPredecessor(BinaryNode $node) {
        return parent::getInOrderPredecessor($node);
    }
}

class AvlTreeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var AvlTreeHelper
     */
    protected $object;

    protected function setUp() {
        $this->object = new AvlTreeHelper();
    }

    /**
     * @covers Spl\AvlTree::add
     * @covers Spl\AvlTree::balance
     * @covers Spl\AvlTree::rotateLeft
     * @covers Spl\AvlTree::rotateSingleLeft
     */
    public function testCaseRightRight() {
        $this->object->add(3);
        $this->object->add(4);
        $this->object->add(5);

        $root = new BinaryNode(4);
        $root->setLeft(new BinaryNode(3));
        $root->setRight(new BinaryNode(5));

        $this->reCalculateHeights($root);
        $this->assertEquals($root, $this->object->getRoot());

    }

    /**
     * @covers Spl\AvlTree::add
     * @covers Spl\AvlTree::balance
     * @covers Spl\AvlTree::rotateRight
     * @covers Spl\AvlTree::rotateSingleRight
     */
    public function testCaseLeftLeft() {
        $this->object->add(5);
        $this->object->add(4);
        $this->object->add(3);

        $root = new BinaryNode(4);
        $root->setLeft(new BinaryNode(3));
        $root->setRight(new BinaryNode(5));

        $this->reCalculateHeights($root);
        $this->assertEquals($root, $this->object->getRoot());
    }

    /**
     * @covers Spl\AvlTree::add
     * @covers Spl\AvlTree::balance
     * @covers Spl\AvlTree::rotateRight
     * @covers Spl\AvlTree::rotateSingleLeft
     * @covers Spl\AvlTree::rotateSingleRight
     */
    public function testCaseLeftRight() {
        $this->object->add(5);
        $this->object->add(1);
        $this->object->add(9);
        $this->object->add(3);
        $this->object->add(0);

        // triggers the double rotate
        $this->object->add(4);

        // original tree before rotate
        //      5
        //    /   \
        //   1    9
        //  / \
        // 0  3
        //     \
        //     4
        //
        // resulting tree should be
        //      3
        //    /   \
        //   1    5
        //  /    / \
        // 0    4  9


        $root = new BinaryNode(3);
        $root->setLeft(new BinaryNode(1));
        $root->setRight(new BinaryNode(5));
        $root->getLeft()->setLeft(new BinaryNode(0));
        $root->getRight()->setLeft(new BinaryNode(4));
        $root->getRight()->setRight(new BinaryNode(9));

        $this->reCalculateHeights($root);
        $actualRoot = $this->object->getRoot();
        $this->assertEquals($root, $actualRoot);

    }

    /**
     * @covers Spl\AvlTree::add
     * @covers Spl\AvlTree::balance
     * @covers Spl\AvlTree::rotateLeft
     * @covers Spl\AvlTree::rotateSingleLeft
     * @covers Spl\AvlTree::rotateSingleRight
     */
    public function testCaseRightLeft() {
        $this->object->add(5);
        $this->object->add(1);
        $this->object->add(8);
        $this->object->add(7);
        $this->object->add(9);

        // triggers the double rotate
        $this->object->add(6);

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


        $root = new BinaryNode(7);
        $root->setLeft(new BinaryNode(5));
        $root->setRight(new BinaryNode(8));
        $root->getLeft()->setLeft(new BinaryNode(1));
        $root->getLeft()->setRight(new BinaryNode(6));
        $root->getRight()->setRight(new BinaryNode(9));

        $this->reCalculateHeights($root);
        $actualRoot = $this->object->getRoot();
        $this->assertEquals($root, $actualRoot);

    }

    /**
     * @covers Spl\AvlTree::remove
     * @covers Spl\AvlTree::removeNode
     * @covers Spl\AvlTree::deleteNode
     */
    public function testRemoveNonExistingItem() {
        $this->object->remove(1);
    }

    /**
     * @covers Spl\AvlTree::remove
     * @covers Spl\AvlTree::removeNode
     * @covers Spl\AvlTree::deleteNode
     * @covers Spl\BinaryNode::isLeaf
     * @covers Spl\BinaryNode::hasOnlyOneChild
     */
    public function testRemoveRootBasic() {
        $this->object->add(5);
        $this->object->remove(5);

        $root = $this->object->getRoot();
        $this->assertNull($root);
    }

    /**
     * @covers Spl\AvlTree::remove
     * @covers Spl\AvlTree::removeNode
     * @covers Spl\AvlTree::deleteNode
     * @covers Spl\BinaryNode::isLeaf
     * @depends testCaseRightRight
     * @depends testCaseLeftLeft
     */
    public function testRemoveLeaf() {
        $this->object->add(4);
        $this->object->add(3);
        $this->object->add(5);

        $this->object->remove(3);

        $root = new BinaryNode(4);
        $root->setRight(new BinaryNode(5));
        $this->reCalculateHeights($root);

        $this->assertEquals($root, $this->object->getRoot());
    }

    /**
     * @covers Spl\AvlTree::remove
     * @covers Spl\AvlTree::removeNode
     * @covers Spl\AvlTree::deleteNode
     * @covers Spl\BinaryNode::setValue
     * @covers Spl\BinaryNode::hasOnlyOneChild
     * @depends testCaseRightRight
     * @depends testCaseLeftLeft
     */
    public function testRemoveWithLeftChild() {
        $this->object->add(4);
        $this->object->add(3);
        $this->object->add(5);
        $this->object->add(1);

        $this->object->remove(3);

        $root = new BinaryNode(4);
        $root->setLeft(new BinaryNode(1));
        $root->setRight(new BinaryNode(5));
        $this->reCalculateHeights($root);

        $this->assertEquals($root, $this->object->getRoot());
    }

    /**
     * @covers Spl\AvlTree::remove
     * @covers Spl\AvlTree::removeNode
     * @covers Spl\AvlTree::deleteNode
     * @covers Spl\BinaryNode::setValue
     * @covers Spl\BinaryNode::hasOnlyOneChild
     * @depends testCaseRightRight
     * @depends testCaseLeftLeft
     */
    public function testRemoveWithRightChild() {
        $this->object->add(4);
        $this->object->add(3);
        $this->object->add(5);
        $this->object->add(7);

        $this->object->remove(5);

        $root = new BinaryNode(4);
        $root->setLeft(new BinaryNode(3));
        $root->setRight(new BinaryNode(7));
        $this->reCalculateHeights($root);

        $this->assertEquals($root, $this->object->getRoot());
    }

    public function testAddItemThatAlreadyExists() {
        $this->object->add(4);
        $this->object->add(4);

        $expectedRoot = new BinaryNode(4);
        $actualRoot = $this->object->getRoot();

        $this->reCalculateHeights($expectedRoot);

        $this->assertEquals($expectedRoot, $this->object->getRoot());

    }

    /**
     * @covers Spl\AvlTree::remove
     * @covers Spl\AvlTree::removeNode
     * @covers Spl\AvlTree::deleteNode
     * @covers Spl\BinaryNode::setValue
     */
    public function testRemoveWithBothChildren() {
        $this->object->add(4);
        $this->object->add(3);
        $this->object->add(5);

        $this->object->remove(4);

        $root = new BinaryNode(3);
        $root->setRight(new BinaryNode(5));
        $this->reCalculateHeights($root);

        $this->assertEquals($root, $this->object->getRoot());
    }

    /**
     * @covers Spl\AvlTree::add
     * @covers Spl\AvlTree::remove
     * @covers Spl\AvlTree::balance
     */
    public function testGauntlet() {

        // add a bunch of items!
        $this->object->add(8);
        $this->object->add(10);
        $this->object->add(-5);

        $this->object->add(2);

        //triggers right-right case
        $this->object->add(4);

        $this->object->add(-1);


        $this->object->add(3);
        $this->object->add(5);

        //triggers left-right case
        $this->object->add(6);


        //         2
        //      /     \
        //   -5        5
        //     \     /   \
        //     -1   4     8
        //         /     / \
        //        3     6  10

        $root = new BinaryNode(2);
        $root->setLeft(new BinaryNode(-5));
        $root->setRight(new BinaryNode(5));

        $root->getLeft()->setRight(new BinaryNode(-1));
        $root->getRight()->setLeft(new BinaryNode(4));
        $root->getRight()->setRight(new BinaryNode(8));

        $root->getRight()->getLeft()->setLeft(new BinaryNode(3));
        $root->getRight()->getRight()->setLeft(new BinaryNode(6));
        $root->getRight()->getRight()->setRight(new BinaryNode(10));

        $this->reCalculateHeights($root);
        $this->assertEquals($root, $this->object->getRoot());


        $this->object->add(-2);
        $this->object->add(-6);

        //triggers left-left
        $this->object->add(-7);

        //            2
        //         /     \
        //      -2        5
        //      / \     /   \
        //    -6  -1   4     8
        //    / \     /     / \
        //  -7  -5   3     6  10


        $root = new BinaryNode(2);
        $root->setLeft(new BinaryNode(-2));
        $root->setRight(new BinaryNode(5));

        $root->getLeft()->setLeft(new BinaryNode(-6));
        $root->getLeft()->setRight(new BinaryNode(-1));
        $root->getRight()->setLeft(new BinaryNode(4));
        $root->getRight()->setRight(new BinaryNode(8));

        $root->getLeft()->getLeft()->setLeft(new BinaryNode(-7));
        $root->getLeft()->getLeft()->setRight(new BinaryNode(-5));
        $root->getRight()->getLeft()->setLeft(new BinaryNode(3));
        $root->getRight()->getRight()->setLeft(new BinaryNode(6));
        $root->getRight()->getRight()->setRight(new BinaryNode(10));

        $this->reCalculateHeights($root);
        $this->assertEquals($root, $this->object->getRoot());


        // begin removing items
        $this->object->remove(6);
        $this->object->remove(10);
        $this->object->remove(8); //triggers rotation

        //            2
        //         /     \
        //      -2        4
        //      / \     /   \
        //    -6  -1   3     5
        //    / \
        //  -7  -5

        $root = new BinaryNode(2);
        $root->setLeft(new BinaryNode(-2));
        $root->setRight(new BinaryNode(4));

        $root->getLeft()->setLeft(new BinaryNode(-6));
        $root->getLeft()->setRight(new BinaryNode(-1));
        $root->getRight()->setLeft(new BinaryNode(3));
        $root->getRight()->setRight(new BinaryNode(5));
        $root->getLeft()->getLeft()->setLeft(new BinaryNode(-7));
        $root->getLeft()->getLeft()->setRight(new BinaryNode(-5));
        $this->reCalculateHeights($root);

        $actualRoot = $this->object->getRoot();

        $this->assertEquals($root, $actualRoot);

        //remove root
        $this->object->remove(2);

        //            -1
        //         /     \
        //      -6        4
        //      / \     /   \
        //    -7  -2   3    5
        //        /
        //      -5

        $root = new BinaryNode(-1);
        $root->setLeft(new BinaryNode(-6));
        $root->setRight(new BinaryNode(4));

        $root->getLeft()->setLeft(new BinaryNode(-7));
        $root->getLeft()->setRight(new BinaryNode(-2));
        $root->getRight()->setLeft(new BinaryNode(3));
        $root->getRight()->setRight(new BinaryNode(5));
        $root->getLeft()->getRight()->setLeft(new BinaryNode(-5));
        $this->reCalculateHeights($root);

        $actualRoot = $this->object->getRoot();
        $this->assertEquals($root, $actualRoot);
    }

    private function reCalculateHeights(BinaryNode $root = NULL) {
        if ($root === NULL) {
            return;
        }
        $this->reCalculateHeights($root->getLeft());
        $this->reCalculateHeights($root->getRight());
        $root->recalculateHeight();

    }

    /**
     * @covers Spl\AvlTree::clear
     * @covers Spl\AvlTree::count
     */
    public function testClear() {
        $this->object->add(5);

        $this->object->clear();

        $this->assertNull($this->object->getRoot());
        $this->assertEmpty($this->object->count());
    }

    /**
     * @covers Spl\AvlTree::contains
     * @covers Spl\AvlTree::containsNode
     */
    public function testContains() {
        $this->assertFalse($this->object->contains(1));

        $this->object->add(1);
        $this->assertTrue($this->object->contains(1));
    }

    /**
     * @covers Spl\AvlTree::contains
     * @covers Spl\AvlTree::containsNode
     */
    public function testContainsRightSubTree() {
        $this->object->add(2);
        $this->object->add(3);
        $this->assertTrue($this->object->contains(3));
        $this->assertFalse($this->object->contains(1));
    }

    /**
     * @covers Spl\AvlTree::contains
     * @covers Spl\AvlTree::containsNode
     */
    public function testContainsLeftSubTree() {
        $this->object->add(2);
        $this->object->add(1);
        $this->assertTrue($this->object->contains(1));
        $this->assertFalse($this->object->contains(3));
    }

    /**
     * @covers Spl\AvlTree::get
     */
    public function testGet() {
        $this->assertNULL($this->object->get(1));

        $this->object->add(1);
        $this->assertEquals(1, $this->object->get(1));
    }

    /**
     * @covers Spl\AvlTree::get
     * @covers Spl\AvlTree::getNode
     */
    public function testGetRightSubTree() {
        $this->object->add(2);
        $this->object->add(3);
        $this->assertEquals(3, $this->object->get(3));
        $this->assertNull($this->object->get(1));
    }

    /**
     * @covers Spl\AvlTree::get
     * @covers Spl\AvlTree::getNode
     */
    public function testGetLeftSubTree() {
        $this->object->add(2);
        $this->object->add(1);
        $this->assertEquals(1, $this->object->get(1));
        $this->assertNull($this->object->get(3));
    }

    /**
     * @covers Spl\AvlTree::isEmpty
     */
    public function testIsEmpty() {
        $this->assertTrue($this->object->isEmpty());

        $this->object->add(1);
        $this->assertFalse($this->object->isEmpty());
    }

    public function testDefaultIterator() {
        $iterator = $this->object->getIterator();

        $this->assertInstanceOf('\\Spl\\InOrderIterator', $iterator);
    }

    public function testEmptyTreeIterators() {

        $iterators = array(
            'InOrder' => $this->object->getIterator(AvlTree::TRAVERSE_IN_ORDER),
            'PreOrder' => $this->object->getIterator(AvlTree::TRAVERSE_PRE_ORDER),
            'PostOrder' => $this->object->getIterator(AvlTree::TRAVERSE_POST_ORDER),
            'LevelOrder' => $this->object->getIterator(AvlTree::TRAVERSE_LEVEL_ORDER),
        );

        foreach ($iterators as $algorithm => $iterator) {
            $this->assertInstanceOf("\\Spl\\{$algorithm}Iterator", $iterator);

            $iterator->rewind();
            $this->assertFalse($iterator->valid());
        }
    }

    public function testGetIteratorA() {
        //          5
        //        /     \
        //       2      8
        //        \    /
        //        3  11
        $this->object->add(5);
        $this->object->add(2);
        $this->object->add(8);
        $this->object->add(11);
        $this->object->add(3);

        $expectedSequences = array(
            'inOrder' => array(2,3,5,8,11),
            'preOrder' => array(5,2,3,8,11),
            'postOrder' => array(3,2,11,8,5),
            'levelOrder' => array(5,2,8,3,11),
        );

        $this->__testIterators($expectedSequences);

    }

    public function testGetIteratorB() {
        //          5
        //        /     \
        //       2      8
        //      / \
        //     1  3
        $this->object->add(5);
        $this->object->add(2);
        $this->object->add(8);
        $this->object->add(1);
        $this->object->add(3);

        $expectedSequences = array(
            'inOrder' => array(1,2,3,5,8),
            'preOrder' => array(5,2,1,3,8),
            'postOrder' => array(1,3,2,8,5),
            'levelOrder' => array(5,2,8,1,3),
        );

        $this->__testIterators($expectedSequences);

    }

    public function testGetIteratorC() {
        //          5
        //        /     \
        //       2      8
        //             / \
        //            6  9
        $this->object->add(5);
        $this->object->add(2);
        $this->object->add(8);
        $this->object->add(6);
        $this->object->add(9);

        $expectedSequences = array(
            'inOrder' => array(2,5,6,8,9),
            'preOrder' => array(5,2,8,6,9),
            'postOrder' => array(2,6,9,8,5),
            'levelOrder' => array(5,2,8,6,9),
        );

        $this->__testIterators($expectedSequences);

    }

    public function testGetIteratorD() {
        //          5
        //        /
        //       2
        $this->object->add(5);
        $this->object->add(2);

        $expectedSequences = array(
            'inOrder' => array(2,5),
            'preOrder' => array(5,2),
            'postOrder' => array(2,5),
            'levelOrder' => array(5,2),
        );

        $this->__testIterators($expectedSequences);

    }

    public function testGetIteratorE() {
        //     0
        //      \
        //       2
        $this->object->add(0);
        $this->object->add(2);

        $expectedSequences = array(
            'inOrder' => array(0,2),
            'preOrder' => array(0,2),
            'postOrder' => array(2,0),
            'levelOrder' => array(0,2),
        );

        $this->__testIterators($expectedSequences);

    }

    private function __testIterators(array $expectedSequences) {

        $iterators = array(
            'inOrder' => $this->object->getIterator(AvlTree::TRAVERSE_IN_ORDER),
            'preOrder' => $this->object->getIterator(AvlTree::TRAVERSE_PRE_ORDER),
            'postOrder' => $this->object->getIterator(AvlTree::TRAVERSE_POST_ORDER),
            'levelOrder' => $this->object->getIterator(AvlTree::TRAVERSE_LEVEL_ORDER),
        );

        foreach ($iterators as $algorithm => $iterator) {
            $actualSequence = array();
            foreach ($iterator as $key => $item) {
//                $this->assertNull($key);
                $actualSequence[] = $item;
            }

            $this->assertEquals($expectedSequences[$algorithm], $actualSequence);
        }

    }

    public function testTraverse() {
        $sum = 0;
        $callback = function($item) use (&$sum) {
            $sum += $item;
        };

        $algorithms = array(
            AvlTree::TRAVERSE_IN_ORDER,
            AvlTree::TRAVERSE_PRE_ORDER,
            AvlTree::TRAVERSE_POST_ORDER,
            AvlTree::TRAVERSE_LEVEL_ORDER,
        );

        foreach ($algorithms as $algorithm) {
            $this->object->traverse($algorithm, $callback);
        }

        $this->assertEquals(0, $sum);

        $this->object->add(1);
        $this->object->add(2);

        foreach ($algorithms as $algorithm) {
            $this->object->traverse($algorithm, $callback);
        }

        $this->assertEquals(12, $sum);

    }

    /**
     * @covers Spl\AvlTree::getInOrderPredecessor
     */
    public function testGetInOrderPredecessorBasic() {
        $this->object->add(5);
        $this->object->add(2);
        $this->object->add(6);

        $root = $this->object->getRoot();

        $this->assertEquals(2, $this->object->getInOrderPredecessor($root)->getValue());
    }


    /**
     * @covers Spl\AvlTree::getInOrderPredecessor
     */
    public function testGetInOrderPredecessorWithLeftNodeHavingRightSubTree() {
        $this->object->add(5);
        $this->object->add(2);
        $this->object->add(6);
        $this->object->add(3);

        $root = $this->object->getRoot();

        $this->assertEquals(3, $this->object->getInOrderPredecessor($root)->getValue());
    }

    /**
     * @covers Spl\AvlTree::getRoot
     */
    public function testGetRoot() {
        $this->assertNull($this->object->getRoot());
    }

    public function testConstructor() {
        $avl = new AvlTreeHelper(function ($a, $b) {
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

        $root = new BinaryNode(4);
        $root->setLeft(new BinaryNode(5));
        $root->setRight(new BinaryNode(3));

        $this->reCalculateHeights($root);
        $this->assertEquals($root, $avl->getRoot());
    }
}

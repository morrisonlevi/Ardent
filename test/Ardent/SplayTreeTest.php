<?php

namespace Ardent;

class SplayTreeTest extends \PHPUnit_Framework_TestCase {

    function testAddSimple() {
        $tree = new SplayTree();
        $tree->add(0);
        $this->assertCount(1, $tree);

        $tree->add(1);

        $root = $tree->toBinaryTree();
        $this->assertEquals(1, $root->getValue());
        $this->assertBinaryTree($root);
        $this->assertCount(2, $tree);
    }

    function testAddLeft() {
        $tree = new SplayTree();
        $tree->add(1);
        $tree->add(0);

        $root = $tree->toBinaryTree();
        $this->assertEquals(0, $root->getValue());
        $this->assertBinaryTree($root);
        $this->assertCount(2, $tree);
    }

    function testAddLeftRight() {
        $tree = new SplayTree();
        $tree->add(2);
        $tree->add(1);
        $tree->add(0);

        $root = $tree->toBinaryTree();
        $this->assertEquals(0, $root->getValue());
        $this->assertBinaryTree($root);
        $this->assertCount(3, $tree);
    }

    function testAddRightRight() {
        $tree = new SplayTree();
        $tree->add(1);
        $tree->add(0);
        $tree->add(2);

        $root = $tree->toBinaryTree();
        $this->assertEquals(2, $root->getValue());
        $this->assertBinaryTree($root);
        $this->assertCount(3, $tree);
    }

    function testAddLeftLeft() {
        $tree = new SplayTree();
        $tree->add(1);
        $tree->add(2);
        $tree->add(0);

        $root = $tree->toBinaryTree();
        $this->assertEquals(0, $root->getValue());
        $this->assertBinaryTree($root);
        $this->assertCount(3, $tree);
    }

    function testAddExisting() {
        $tree = new SplayTree();
        $tree->add(1);
        $tree->add(1);

        $root = $tree->toBinaryTree();
        $this->assertEquals(1, $root->getValue());
        $this->assertCount(1, $tree);
    }

    protected function assertBinaryTree(BinaryTree $root) {
        $x = $root->getValue();
        $left = $root->getLeft();
        if ($left !== NULL) {
            $y = $root->getLeft()->getValue();
            $this->assertLessThan($x, $y);
            $this->assertBinaryTree($root->getLeft());
        }
        $right = $root->getRight();
        if ($right !== NULL) {
            $y = $right->getValue();
            $this->assertLessThan($y, $x);
            $this->assertBinaryTree($root->getRight());
        }
    }

    function testAddItemThatAlreadyExists() {
        $object = new SplayTree();
        $object->add(4);
        $object->add(4);

        $expectedRoot = new BinaryTree(4);
        $actualRoot = $object->toBinaryTree();

        $this->assertEquals($expectedRoot, $actualRoot);

    }

    function testContains() {
        $object = new SplayTree();
        $this->assertFalse($object->contains(1));

        $object->add(0);
        $this->assertFalse($object->contains(1));

        $object->add(2);
        $this->assertFalse($object->contains(1));

        $object->add(1);
        $this->assertTrue($object->contains(1));

        $expected = new BinaryTree(1);
        $expected->setLeft(new BinaryTree(0));
        $expected->setRight(new BinaryTree(2));
        $actual = $object->toBinaryTree();
        $this->assertEquals($expected, $actual);
    }

    function testFindLast() {
        $tree = new SplayTree();
        $tree->add(2);
        $tree->add(1);
        $tree->add(0);

        $expected = 2;
        $actual = $tree->last();
        $this->assertEquals($expected, $actual);
    }

    function testFindFirst() {
        $tree = new SplayTree();
        $tree->add(0);
        $tree->add(2);
        $tree->add(1);

        $expected = 0;
        $actual = $tree->first();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Ardent\Exception\LookupException
     */
    function testGetException() {
        $tree = new SplayTree();
        $tree->get(1);
    }

    /**
     * @expectedException \Ardent\Exception\LookupException
     */
    function testGetExceptionB() {
        $tree = new SplayTree();
        $tree->add(0);
        $tree->get(1);
    }

    function testGet() {
        $tree = new SplayTree();
        $tree->add(0);
        $tree->add(1);
        $tree->add(2);

        $expected = 1;
        $actual = $tree->get(1);
        $this->assertEquals($expected, $actual);
    }

    function testRemove() {
        $tree = new SplayTree();
        $tree->add(1);
        $tree->add(0);
        $tree->remove(0);
        $this->assertCount(1, $tree);
        $this->assertTrue($tree->contains(1));
    }

    function testRemoveNonExistent() {
        $tree = new SplayTree();
        $tree->remove(0);

        $tree->add(1);
        $tree->remove(0);

        $this->assertCount(1, $tree);
        $this->assertFalse($tree->isEmpty());

    }

    function testGetIterator() {
        $tree = new SplayTree();
        $iterator = $tree->getIterator();
        $this->assertInstanceOf('\Ardent\Iterator\InOrderIterator', $iterator);
    }

    function testGetIterator_InOrderIterator() {
        $tree = new SplayTree();
        $iterator = $tree->getIterator($tree::TRAVERSE_IN_ORDER);
        $this->assertInstanceOf('\Ardent\Iterator\InOrderIterator', $iterator);
    }

    function testGetIterator_LevelOrderIterator() {
        $tree = new SplayTree();
        $iterator = $tree->getIterator($tree::TRAVERSE_LEVEL_ORDER);
        $this->assertInstanceOf('\Ardent\Iterator\LevelOrderIterator', $iterator);
    }

    function testGetIterator_PostOrderIterator() {
        $tree = new SplayTree();
        $iterator = $tree->getIterator($tree::TRAVERSE_POST_ORDER);
        $this->assertInstanceOf('\Ardent\Iterator\PostOrderIterator', $iterator);
    }

    function testGetIterator_PreOrderIterator() {
        $tree = new SplayTree();
        $iterator = $tree->getIterator($tree::TRAVERSE_PRE_ORDER);
        $this->assertInstanceOf('\Ardent\Iterator\PreOrderIterator', $iterator);
    }

    function testSetCompare() {
        $tree = new SplayTree();
        $tree->setCompare(function($a, $b) {
            if ($a < $b) {
                return 1;
            } elseif ($b < $a) {
                return -1;
            } else {
                return 0;
            }
        });

        for ($i = 0; $i < 3; $i++) {
            $tree->add($i);
        }
        $bt = $tree->toBinaryTree();
        $this->assertEquals(2, $bt->getValue());
        $this->assertEquals(1, $bt->getRight()->getValue());
        $this->assertEquals(0, $bt->getRight()->getRight()->getValue());
    }

    /**
     * @expectedException \Ardent\Exception\StateException
     */
    function testSetCompareNotEmpty() {
        $tree = new SplayTree();
        $tree->add(0);
        $tree->setCompare(function($a, $b) {

        });
    }

}
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
        $this->assertFalse($object->containsItem(1));

        $object->add(0);
        $this->assertFalse($object->containsItem(1));

        $object->add(2);
        $this->assertFalse($object->containsItem(1));

        $object->add(1);
        $this->assertTrue($object->containsItem(1));

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
        $actual = $tree->findLast();
        $this->assertEquals($expected, $actual);
    }

    function testFindFirst() {
        $tree = new SplayTree();
        $tree->add(0);
        $tree->add(2);
        $tree->add(1);

        $expected = 0;
        $actual = $tree->findFirst();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Ardent\Exception\LookupException
     */
    function testGetException() {
        $tree = new SplayTree();
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

    function testFoo() {
        $tree = new SplayTree();

        for ($i = 0; $i < 100; $i++) {
            $tree->add($i);
        }

        $tree->containsItem(50);
        $tree->get(50);
    }

}
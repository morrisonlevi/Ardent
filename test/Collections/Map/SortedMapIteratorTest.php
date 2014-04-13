<?php

namespace Collections;


class SortedMapIteratorTest extends \PHPUnit_Framework_TestCase {


    function testIterator() {
        $map = new SortedMap();
        $map[0] = 1;
        $map[2] = 3;
        $map[3] = 4;
        $map[1] = 2;

        $iterator = $map->getIterator();

        $this->assertInstanceOf('Collections\\SortedMapIterator', $iterator);
        $this->assertCount(count($map), $iterator);

        $iterator->rewind();

        for ($i = 0; $i < count($map); $i++) {
            $this->assertTrue($iterator->valid());

            $this->assertEquals($i, $iterator->key());
            $expectedValue = $map[$i];
            $this->assertEquals($expectedValue, $iterator->current());

            $iterator->next();
        }

        $this->assertFalse($iterator->valid());
        $this->assertCount(count($map), $iterator);

    }
    

    function test_key_badInnerIterator_throwsException() {
        $this->setExpectedException('\Collections\TypeException');

        $tree = new BinaryTree(0);
        $tree->setLeft(new BinaryTree(-1));
        $tree->setRight(new BinaryTree(1));

        $iterator = new SortedMapIterator(new InOrderIterator($tree, 0), 3);
        $iterator->key();
    }


    function test_current_badInnerIterator_throwsException() {
        $this->setExpectedException('\Collections\TypeException');

        $tree = new BinaryTree(0);
        $tree->setLeft(new BinaryTree(-1));
        $tree->setRight(new BinaryTree(1));

        $iterator = new SortedMapIterator(new InOrderIterator($tree, 0), 3);
        $iterator->current();
    }


} 
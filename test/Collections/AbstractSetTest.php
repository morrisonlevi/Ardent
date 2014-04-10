<?php

namespace Collections;

class AbstractSetTest extends \PHPUnit_Framework_TestCase {


    function testDifference() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);
        $a->add(3);
        $a->add(5);
        $a->add(6);

        $b = new HashSet();
        $b->add(0);
        $b->add(2);
        $b->add(4);
        $b->add(6);

        $c = new HashSet();
        $c->add(1);
        $c->add(3);
        $c->add(5);
        $c->add(2);
        $c->add(4);

        $difference = $a->difference($b);
        foreach ($difference as $item) {
            $this->assertTrue(
                $c->has($item),
                "Symmetric difference failed: had '$item' but should not have"
            );
        }
        foreach ($c as $item) {
            $this->assertTrue(
                $difference->has($item),
                "Symmetric difference failed: should have contained '$item'"
            );
        }

        $difference = $b->difference($a);
        foreach ($difference as $item) {
            $this->assertTrue(
                $c->has($item),
                "Symmetric difference failed: had '$item' but should not have"
            );
        }
        foreach ($c as $item) {
            $this->assertTrue(
                $difference->has($item),
                "Symmetric difference failed: should have contained '$item'"
            );
        }
    }

    function testIntersectionEmpty() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);

        $b = new HashSet();
        $b->add(2);
        $b->add(3);

        $intersection = $a->intersection($b);
        $this->assertInstanceOf('Collections\\HashSet', $intersection);
        $this->assertCount(0, $intersection);

        $intersection = $b->intersection($a);
        $this->assertInstanceOf('Collections\\HashSet', $intersection);
        $this->assertCount(0, $intersection);
    }

    function testIntersectionAll() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);

        $b = new HashSet();
        $b->add(0);
        $b->add(1);

        $intersection = $a->intersection($b);
        $this->assertInstanceOf('Collections\\HashSet', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertNotSame($a, $intersection);
        $this->assertNotSame($b, $intersection);
        $this->assertEquals($a, $intersection);


        $intersection = $b->intersection($a);
        $this->assertInstanceOf('Collections\\HashSet', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertNotSame($a, $intersection);
        $this->assertNotSame($b, $intersection);
        $this->assertEquals($b, $intersection);
    }

    function testIntersectionSome() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);
        $a->add(2);
        $a->add(3);

        $b = new HashSet();
        $b->add(1);
        $b->add(3);
        $b->add(5);
        $b->add(7);

        $intersection = $a->intersection($b);
        $this->assertInstanceOf('Collections\\HashSet', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertTrue($intersection->has(1));
        $this->assertTrue($intersection->has(3));

        $intersection = $b->intersection($a);
        $this->assertInstanceOf('Collections\\HashSet', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertTrue($intersection->has(1));
        $this->assertTrue($intersection->has(3));

    }

    function testRelativeComplementSelf() {
        $a = new HashSet();
        $a->add(2);
        $a->add(3);
        $a->add(4);

        $complement = $a->complement($a);
        $this->assertInstanceOf('Collections\\HashSet', $complement);
        $this->assertCount(0, $complement);
    }

    function testRelativeComplement() {
        $a = new HashSet();
        $a->add(2);
        $a->add(3);
        $a->add(4);

        $b = new HashSet();
        $b->add(1);
        $b->add(2);
        $b->add(3);

        $complement = $a->complement($b);
        $this->assertInstanceOf('Collections\\HashSet', $complement);
        $this->assertCount(1, $complement);
        $this->assertTrue($complement->has(1));

        $complement = $b->complement($a);
        $this->assertInstanceOf('Collections\\HashSet', $complement);
        $this->assertCount(1, $complement);
        $this->assertTrue($complement->has(4));
    }

    function testUnionSelf() {
        $a = new HashSet();
        $a->add(1);
        $a->add(2);
        $a->add(3);

        $union = $a->union($a);
        $this->assertInstanceOf('Collections\\HashSet', $union);
        $this->count(3, $union);
        $this->assertNotSame($a, $union);
        $this->assertEquals($a, $union);
    }

    function testUnion() {
        $a = new HashSet();
        $a->add(1);
        $a->add(2);
        $a->add(3);

        $b = new HashSet();
        $b->add(2);
        $b->add(3);
        $b->add(4);

        $union = $a->union($b);
        $this->assertInstanceOf('Collections\\HashSet', $union);
        $this->assertCount(4, $union);

        for ($i = 1; $i <= 4; $i++) {
            $this->assertTrue($union->has($i));
        }

        $union = $b->union($a);
        $this->assertInstanceOf('Collections\\HashSet', $union);
        $this->assertCount(4, $union);

        for ($i = 1; $i <= 4; $i++) {
            $this->assertTrue($union->has($i));
        }

    }

}

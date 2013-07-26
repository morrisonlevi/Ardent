<?php

namespace Ardent;

class AbstractSetTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers \Ardent\AbstractSet::difference
     */
    function testDifferenceNone() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);
        $a->add(2);
        $a->add(3);


        $b = new HashSet();
        $b->add(0);
        $b->add(1);
        $b->add(2);
        $b->add(3);

        $diff = $a->difference($b);
        $this->assertInstanceOf('Ardent\\HashSet', $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);
        $this->assertCount(0, $diff);
    }

    /**
     * @depends testDifferenceNone
     * @covers \Ardent\AbstractSet::difference
     */
    function testDifferenceAll() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);
        $a->add(2);
        $a->add(3);


        $b = new HashSet();

        $diff = $a->difference($b);
        $this->assertCount(4, $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);

        for ($i = 0; $i < 4; $i++) {
            $this->assertTrue($a->contains($i));
        }
    }

    /**
     * @depends testDifferenceNone
     * @covers \Ardent\AbstractSet::difference
     */
    function testDifferenceSome() {
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

        $diff = $a->difference($b);
        $this->assertCount(2, $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);

        $this->assertTrue($diff->contains(0));
        $this->assertTrue($diff->contains(2));

        $diff = $b->difference($a);
        $this->assertCount(2, $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);

        $this->assertTrue($diff->contains(5));
        $this->assertTrue($diff->contains(7));
    }

    function testSymmetricDifference() {
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

        $difference = $a->symmetricDifference($b);
        foreach ($difference as $item) {
            $this->assertTrue(
                $c->contains($item),
                "Symmetric difference failed: had '$item' but should not have"
            );
        }
        foreach ($c as $item) {
            $this->assertTrue(
                $difference->contains($item),
                "Symmetric difference failed: should have contained '$item'"
            );
        }

        $difference = $b->symmetricDifference($a);
        foreach ($difference as $item) {
            $this->assertTrue(
                $c->contains($item),
                "Symmetric difference failed: had '$item' but should not have"
            );
        }
        foreach ($c as $item) {
            $this->assertTrue(
                $difference->contains($item),
                "Symmetric difference failed: should have contained '$item'"
            );
        }
    }

    /**
     * @covers \Ardent\AbstractSet::intersection
     */
    function testIntersectionEmpty() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);

        $b = new HashSet();
        $b->add(2);
        $b->add(3);

        $intersection = $a->intersection($b);
        $this->assertInstanceOf('Ardent\\HashSet', $intersection);
        $this->assertCount(0, $intersection);

        $intersection = $b->intersection($a);
        $this->assertInstanceOf('Ardent\\HashSet', $intersection);
        $this->assertCount(0, $intersection);
    }

    /**
     * @covers \Ardent\AbstractSet::intersection
     */
    function testIntersectionAll() {
        $a = new HashSet();
        $a->add(0);
        $a->add(1);

        $b = new HashSet();
        $b->add(0);
        $b->add(1);

        $intersection = $a->intersection($b);
        $this->assertInstanceOf('Ardent\\HashSet', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertNotSame($a, $intersection);
        $this->assertNotSame($b, $intersection);
        $this->assertEquals($a, $intersection);


        $intersection = $b->intersection($a);
        $this->assertInstanceOf('Ardent\\HashSet', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertNotSame($a, $intersection);
        $this->assertNotSame($b, $intersection);
        $this->assertEquals($b, $intersection);
    }

    /**
     * @covers \Ardent\AbstractSet::intersection
     */
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
        $this->assertInstanceOf('Ardent\\HashSet', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertTrue($intersection->contains(1));
        $this->assertTrue($intersection->contains(3));

        $intersection = $b->intersection($a);
        $this->assertInstanceOf('Ardent\\HashSet', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertTrue($intersection->contains(1));
        $this->assertTrue($intersection->contains(3));

    }

    /**
     * @covers \Ardent\AbstractSet::relativeComplement
     */
    function testRelativeComplementSelf() {
        $a = new HashSet();
        $a->add(2);
        $a->add(3);
        $a->add(4);

        $complement = $a->relativeComplement($a);
        $this->assertInstanceOf('Ardent\\HashSet', $complement);
        $this->assertCount(0, $complement);
    }

    /**
     * @covers \Ardent\AbstractSet::relativeComplement
     */
    function testRelativeComplement() {
        $a = new HashSet();
        $a->add(2);
        $a->add(3);
        $a->add(4);

        $b = new HashSet();
        $b->add(1);
        $b->add(2);
        $b->add(3);

        $complement = $a->relativeComplement($b);
        $this->assertInstanceOf('Ardent\\HashSet', $complement);
        $this->assertCount(1, $complement);
        $this->assertTrue($complement->contains(1));

        $complement = $b->relativeComplement($a);
        $this->assertInstanceOf('Ardent\\HashSet', $complement);
        $this->assertCount(1, $complement);
        $this->assertTrue($complement->contains(4));
    }

    /**
     * @covers \Ardent\AbstractSet::union
     */
    function testUnionSelf() {
        $a = new HashSet();
        $a->add(1);
        $a->add(2);
        $a->add(3);

        $union = $a->union($a);
        $this->assertInstanceOf('Ardent\\HashSet', $union);
        $this->count(3, $union);
        $this->assertNotSame($a, $union);
        $this->assertEquals($a, $union);
    }

    /**
     * @covers \Ardent\AbstractSet::union
     */
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
        $this->assertInstanceOf('Ardent\\HashSet', $union);
        $this->assertCount(4, $union);

        for ($i = 1; $i <= 4; $i++) {
            $this->assertTrue($union->contains($i));
        }

        $union = $b->union($a);
        $this->assertInstanceOf('Ardent\\HashSet', $union);
        $this->assertCount(4, $union);

        for ($i = 1; $i <= 4; $i++) {
            $this->assertTrue($union->contains($i));
        }

    }

    function testSubsetEmpty() {
        $a = new HashSet();
        $b = new HashSet();

        $this->assertTrue($a->isSubsetOf($b));
        $this->assertTrue($b->isSubsetOf($a));
    }

    function testSubsetSelfEmpty() {
        $a = new HashSet();
        $this->assertTrue($a->isSubsetOf($a));
    }

    function testSubsetSelfNonEmpty() {
        $a = new HashSet();
        $a->add(0);
        $this->assertTrue($a->isSubsetOf($a));
    }

    function testSubset() {
        $a = new HashSet();

        $b = new HashSet();
        $b->add(0);

        $this->assertTrue($a->isSubsetOf($b));

        $a->add(0);
        $this->assertTrue($a->isSubsetOf($b));
    }

    function testNotSubset() {
        $a = new HashSet();

        $b = new HashSet();
        $b->add(0);

        $this->assertFalse($b->isSubsetOf($a));
    }

    function testStrictSubsetSelfEmpty() {
        $a = new HashSet();
        $this->assertFalse($a->isStrictSubsetOf($a));
    }

    function testStrictSubsetSelfNonEmpty() {
        $a = new HashSet();
        $a->add(0);
        $this->assertFalse($a->isStrictSubsetOf($a));
    }

    function testStrictSubsetEmpty() {
        $a = new HashSet();
        $b = new HashSet();
        $this->assertFalse($a->isStrictSubsetOf($b));
        $this->assertFalse($b->isStrictSubsetOf($a));
    }


    function testSupersetEmpty() {
        $a = new HashSet();
        $b = new HashSet();

        $this->assertTrue($a->isSupersetOf($b));
        $this->assertTrue($b->isSupersetOf($a));
    }

    function testSupersetSelfEmpty() {
        $a = new HashSet();
        $this->assertTrue($a->isSupersetOf($a));
    }

    function testSupersetSelfNonEmpty() {
        $a = new HashSet();
        $a->add(0);
        $this->assertTrue($a->isSupersetOf($a));
    }

    function testSuperset() {
        $a = new HashSet();

        $b = new HashSet();
        $b->add(0);

        $this->assertTrue($b->isSupersetOf($a));

        $a->add(0);
        $this->assertTrue($b->isSupersetOf($a));
    }

    function testNotSuperset() {
        $a = new HashSet();

        $b = new HashSet();
        $b->add(0);

        $this->assertFalse($a->isSupersetOf($b));
    }

    function testStrictSupersetSelfEmpty() {
        $a = new HashSet();
        $this->assertFalse($a->isStrictSupersetOf($a));
    }

    function testStrictSupersetSelfNonEmpty() {
        $a = new HashSet();
        $a->add(0);
        $this->assertFalse($a->isStrictSupersetOf($a));
    }

    function testStrictSupersetEmpty() {
        $a = new HashSet();
        $b = new HashSet();
        $this->assertFalse($a->isStrictSupersetOf($b));
        $this->assertFalse($b->isStrictSupersetOf($a));
    }

}

<?php

namespace Spl;

class AbstractSetTest extends \PHPUnit_Framework_TestCase {


    /**
     * @covers \Spl\AbstractSet::difference
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
        $this->assertInstanceOf('Spl\\HashSet', $diff);
        $this->assertNotSame($diff, $a);
        $this->assertNotSame($diff, $b);
        $this->assertCount(0, $diff);
    }

    /**
     * @depends testDifferenceNone
     * @covers \Spl\AbstractSet::difference
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
     * @covers \Spl\AbstractSet::difference
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


}

<?php

namespace Collections;

abstract class SetTest extends TestCase {


    /**
     * @return Set
     */
    abstract function instance();


    /**
     * @dataProvider provide_rangeN
     * @param array $add
     */
    function test_count_sizeN_returnsN(array $add) {
        $set = $this->instance();
        array_walk($add, [$set, 'add']);

        $expectedSize = count($add);
        $this->count($expectedSize, $set);
    }


    /**
     * @depends test_count_sizeN_returnsN
     * @dataProvider provide_rangeN
     */
    function test_clear_sizeN_isEmpty(array $data) {
        $set = $this->instance();
        foreach ($data as $item) {
            $set->add($item);
        }
        $set->clear();
        $this->assertTrue($set->isEmpty());
    }


    function test_isEmpty_removingOnlyItem_returnsTrue() {
        $item = new \StdClass();
        $set = $this->instance();
        $set->add($item);
        $set->remove($item);

        $this->assertTrue($set->isEmpty());
    }


    /**
     * @dataProvider provide_rangeN
     */
    function test_has_removingLastItem_returnsFalse(array $data) {
        $set = $this->instance();
        foreach ($data as $item) {
            $set->add($item);
        }
        $set->remove($item);
        $this->assertFalse($set->has($item));
    }


    /**
     * @dataProvider provide_rangeN
     */
    function test_isEmpty_sizeN(array $data) {
        $set = $this->instance();
        array_walk($data, [$set, 'add']);

        $this->assertEquals(count($data) === 0, $set->isEmpty());
    }

    /**
     * @depends test_count_sizeN_returnsN
     */
    function testDifferenceSelf() {
        $a = new HashSet();
        $a->add(0);

        $diff = $a->difference($a);
        $this->assertInstanceOf('Collections\\HashSet', $diff);
        $this->assertNotSame($diff, $a);
        $this->assertCount(0, $diff);
    }


    /**
     * @dataProvider provide_rangeN
     */
    function test_difference_withSelf_returnsEmptySet(array $data) {
        $set = $this->instance();
        array_walk($data, [$set, 'add']);
        $difference = $set->difference($set);
        $this->assertTrue($difference->isEmpty());
    }


    /**
     * @dataProvider provide_rangeN
     */
    function test_difference_noDifference_returnsEmptySet(array $data) {
        $a = $this->instance();
        array_walk($data, [$a, 'add']);
        $b = $this->instance();
        foreach ($a as $item) {
            $b->add($item);
        }
        $difference = $a->difference($b);
        $this->assertTrue($difference->isEmpty());
    }


    function test_difference_allDifferent_returnsAll() {
        $a = $this->instance();
        $a->add(1);
        $a->add(3);
        $a->add(5);

        $b = $this->instance();
        $b->add(0);
        $b->add(2);
        $b->add(4);

        $difference = $a->difference($b);
        $this->assertCount($a->count() + $b->count(), $difference);
        foreach ($difference as $item) {
            $this->assertTrue($b->has($item) || $a->has($item));
        }
    }


    function testIntersectionEmpty() {
        $a = $this->instance();
        $a->add(0);
        $a->add(1);

        $b = $this->instance();
        $b->add(2);
        $b->add(3);

        $intersection = $a->intersection($b);
        $this->assertInstanceOf('Collections\\Set', $intersection);
        $this->assertCount(0, $intersection);

        $intersection = $b->intersection($a);
        $this->assertInstanceOf('Collections\\Set', $intersection);
        $this->assertCount(0, $intersection);
    }


    /**
     * @dataProvider provide_rangeN
     */
    function test_intersection_allMatch_returnsAll(array $add) {
        $a = $this->instance();
        array_walk($add, [$a, 'add']);

        $b = $this->instance();
        array_walk($add, [$b, 'add']);

        $intersection = $a->intersection($b);
        $this->assertInstanceOf('Collections\\Set', $intersection);
        $this->assertCount(count($add), $intersection);
        $this->assertNotSame($a, $intersection);
        $this->assertNotSame($b, $intersection);

        foreach ($intersection as $item) {
            $this->assertTrue($a->has($item) && $b->has($item));
        }
    }

    function testIntersectionSome() {
        $a = $this->instance();
        $a->add(0);
        $a->add(1);
        $a->add(2);
        $a->add(3);

        $b = $this->instance();
        $b->add(1);
        $b->add(3);
        $b->add(5);
        $b->add(7);

        $intersection = $a->intersection($b);
        $this->assertInstanceOf('Collections\\Set', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertTrue($intersection->has(1));
        $this->assertTrue($intersection->has(3));

        $intersection = $b->intersection($a);
        $this->assertInstanceOf('Collections\\Set', $intersection);
        $this->assertCount(2, $intersection);
        $this->assertTrue($intersection->has(1));
        $this->assertTrue($intersection->has(3));

    }

    function testRelativeComplementSelf() {
        $a = $this->instance();
        $a->add(2);
        $a->add(3);
        $a->add(4);

        $complement = $a->complement($a);
        $this->assertInstanceOf('Collections\\Set', $complement);
        $this->assertCount(0, $complement);
    }

    function testRelativeComplement() {
        $a = $this->instance();
        $a->add(2);
        $a->add(3);
        $a->add(4);

        $b = $this->instance();
        $b->add(1);
        $b->add(2);
        $b->add(3);

        $complement = $a->complement($b);
        $this->assertInstanceOf('Collections\\Set', $complement);
        $this->assertCount(1, $complement);
        $this->assertTrue($complement->has(1));

        $complement = $b->complement($a);
        $this->assertInstanceOf('Collections\\Set', $complement);
        $this->assertCount(1, $complement);
        $this->assertTrue($complement->has(4));
    }

    function testUnionSelf() {
        $a = $this->instance();
        $a->add(1);
        $a->add(2);
        $a->add(3);

        $union = $a->union($a);
        $this->assertInstanceOf('Collections\\Set', $union);
        $this->count(3, $union);
        $this->assertNotSame($a, $union);
        $this->assertEquals($a, $union);
    }

    function testUnion() {
        $a = $this->instance();
        $a->add(1);
        $a->add(2);
        $a->add(3);

        $b = $this->instance();
        $b->add(2);
        $b->add(3);
        $b->add(4);

        $union = $a->union($b);
        $this->assertInstanceOf('Collections\\Set', $union);
        $this->assertCount(4, $union);

        for ($i = 1; $i <= 4; $i++) {
            $this->assertTrue($union->has($i));
        }

        $union = $b->union($a);
        $this->assertInstanceOf('Collections\\Set', $union);
        $this->assertCount(4, $union);

        for ($i = 1; $i <= 4; $i++) {
            $this->assertTrue($union->has($i));
        }

    }

}

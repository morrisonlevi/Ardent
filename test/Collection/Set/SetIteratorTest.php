<?php

namespace Ardent\Collection;


abstract class SetIteratorTest extends TestCase {


    /**
     * @return Set
     */
    abstract function set();


    function test_isEmpty_empty_returnsTrue() {
        $set = $this->set();
        $iterator = $set->getIterator();

        $this->assertTrue($iterator->isEmpty());
    }


    /**
     * @dataProvider provide_rangeZeroToN
     */
    function test_count_sizeN_returnsN(array $data) {
        $set = $this->set();
        array_walk($data, [$set, 'add']);
        $iterator = $set->getIterator();

        $this->assertCount(count($data), $iterator);
    }


    function testIterator() {
        $set = $this->set();
        $set->add(0);
        $set->add(2);
        $set->add(4);
        $set->add(6);

        $iterator = $set->getIterator();
        $this->assertInstanceOf(__NAMESPACE__ . '\\SetIterator', $iterator);
        $this->assertCount(4, $iterator);

        $iterator->rewind();

        for ($i = 0; $i < count($set); $i++) {
            $this->assertTrue($iterator->valid());

            $this->assertEquals($i, $iterator->key());
            $this->assertEquals($i * 2, $iterator->current());

            $iterator->next();
        }

        $this->assertFalse($iterator->valid());
    }

} 
<?php

namespace Ardent\Collection;


class HashMapIteratorTest extends TestCase {


    function test_getIterator_any_returnsCorrectInstance() {
        $map = new HashMap();
        $iterator = $map->getIterator();
        $this->assertNotNull($iterator);
        $this->assertInstanceOf(__NAMESPACE__ . '\\HashMapIterator', $iterator);
    }


    /**
     * @depends test_getIterator_any_returnsCorrectInstance
     */
    function test_valid_empty_returnsFalse() {
        $map = new HashMap();
        $iterator = $map->getIterator();
        $iterator->rewind();
        $this->assertFalse($iterator->valid());
    }


    /**
     * @depends      test_getIterator_any_returnsCorrectInstance
     * @dataProvider provide_rangeZeroToN
     */
    function testCount_containsN_returnsN(array $data) {
        $map = new HashMap();
        foreach ($data as $key => $value) {
            $map[$key] = $value;
        }
        $iterator = $map->getIterator();
        $expectedCount = count($data);

        $this->assertCount($expectedCount, $iterator);
    }


    /**
     * @depends      test_getIterator_any_returnsCorrectInstance
     * @dataProvider provide_rangeZeroToN
     */
    function test_containsN_returnsKeyValuePairCorrectly($dataToAdd) {
        $map = new HashMap();
        foreach ($dataToAdd as $key => $value) {
            $map[$key] = $value;
        }

        foreach ($map->getIterator() as $key => $value) {
            $expect = $dataToAdd[$key];
            $this->assertEquals($expect, $value);
        }
    }


} 
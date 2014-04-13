<?php

namespace Collections;


class HashMapIteratorTest extends \PHPUnit_Framework_TestCase {

    function test_getIterator_any_returnsCorrectInstance() {
        $map = new HashMap();
        $iterator = $map->getIterator();
        $this->assertNotNull($iterator);
        $this->assertInstanceOf('\\Collections\\HashMapIterator', $iterator);
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


    function provider() {
        $data = [];
        $result = [
            'N0' => [$data, 0],
        ];

        for ($i = 1; $i < 10; ++$i) {
            $data[] = 2 * $i;
            $result["N$i"] = [$data, $i];
        }
        return $result;
    }


    /**
     * @depends test_getIterator_any_returnsCorrectInstance
     * @dataProvider provider
     */
    function testCount_containsN_returnsN($dataToAdd, $expectedCount) {
        $map = new HashMap();
        foreach ($dataToAdd as $key => $value) {
            $map[$key] = $value;
        }

        $iterator = $map->getIterator();
        $this->assertCount($expectedCount, $iterator);
    }


    /**
     * @depends test_getIterator_any_returnsCorrectInstance
     * @dataProvider provider
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
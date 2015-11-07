<?php

namespace Ardent\Collection;


class HashSetTest extends SetTest {

    /**
     * @var HashSet
     */
    protected $object;


    function instance() {
        return new HashSet();
    }


    function test_has() {
        $set = $this->instance();

        $scalar = 0;
        $this->assertFalse($set->has($scalar));
        $set->add($scalar);
        $this->assertTrue($set->has($scalar));
        $this->assertTrue($set->has('0'));

        $object = new \StdClass();
        $this->assertFalse($set->has($object));
        $set->add($object);
        $this->assertTrue($set->has($object));

        $resource = fopen(__FILE__, 'r');
        $this->assertFalse($set->has($resource));
        $set->add($resource);
        $this->assertTrue($set->has($resource));
        fclose($resource);

        $emptyArray = array();
        $this->assertFalse($set->has($emptyArray));
        $set->add($emptyArray);
        $this->assertTrue($set->has($emptyArray));

        $array = array(0, 1);
        $this->assertFalse($set->has($array));
        $set->add($array);
        $this->assertTrue($set->has($array));

        $null = null;

        $this->assertFalse($set->has($null));
        $set->add($null);
        $this->assertTrue($set->has($null));

    }


}

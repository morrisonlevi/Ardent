<?php

namespace Ardent\Collection;

class HashSet extends AbstractSet implements Set {


    private $objects = [];

    /**
     * @var callable
     */
    private $hashFunction = null;


    /**
     * @param callable $hashFunction
     */
    function __construct(callable $hashFunction = null) {
        $this->hashFunction = $hashFunction ?: __NAMESPACE__ . '\\hash';
    }


    /**
     * @param $item
     *
     * @return bool
     */
    function has($item) {
        $hash = call_user_func($this->hashFunction, $item);

        assert(is_scalar($hash));

        return array_key_exists($hash, $this->objects);

    }


    /**
     * @return void
     */
    function clear() {
        $this->objects = [];
    }


    /**
     * @return bool
     */
    function isEmpty() {
        return $this->count() === 0;
    }


    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return count($this->objects);
    }


    /**
     * Note that if the item is considered equal to an already existing item
     * in the set that it will be replaced.
     *
     * @param $item
     *
     * @return void
     */
    function add($item) {
        $hash = call_user_func($this->hashFunction, $item);

        assert(is_scalar($hash));

        $this->objects[$hash] = $item;
    }


    /**
     * @param $item
     *
     * @return void
     */
    function remove($item) {
        $hash = call_user_func($this->hashFunction, $item);

        assert(is_scalar($hash));

        unset($this->objects[$hash]);
    }


    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return HashSetIterator
     */
    function getIterator() {
        return new HashSetIterator($this->objects);
    }


    /**
     * @return HashSet
     */
    protected function cloneEmpty() {
        return new self($this->hashFunction);
    }


}

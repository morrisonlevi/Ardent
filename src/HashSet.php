<?php

namespace Collections;

class HashSet extends AbstractSet implements Set {

    use IteratorCollection;

    private $objects = [];

    /**
     * @var callable
     */
    private $hashFunction = NULL;


    /**
     * @param callable $hashFunction
     *
     * @return HashSet
     */
    function __construct(callable $hashFunction = NULL) {
        $this->hashFunction = $hashFunction ?: [$this, 'hash'];
    }


    /**
     * @param $item
     *
     * @return bool
     * @throws FunctionException when the hashing function returns an improper value.
     * @throws TypeException when $item is not the correct type.
     */
    function has($item) {
        $hash = $this->hashGuard(call_user_func($this->hashFunction, $item));
        return array_key_exists($hash, $this->objects);

    }


    /**
     * @param $item
     *
     * @return string
     */
    function hash($item) {
        if (is_object($item)) {
            return spl_object_hash($item);
        } elseif (is_scalar($item)) {
            return "s_$item";
        } elseif (is_resource($item)) {
            return "r_$item";
        } elseif (is_array($item)) {
            return 'a_' . md5(serialize($item));
        }

        return '0';
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
     * @throws FunctionException when the hashing function returns an improper value.
     * @throws TypeException when $item is not the correct type.
     */
    function add($item) {
        $hash = $this->hashGuard(call_user_func($this->hashFunction, $item));
        $this->objects[$hash] = $item;
    }


    /**
     * @param $item
     *
     * @return void
     * @throws FunctionException when the hashing function returns an improper value.
     * @throws TypeException when $item is not the correct type.
     */
    function remove($item) {
        $hash = $this->hashGuard(call_user_func($this->hashFunction, $item));
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


    private function hashGuard($hash){
        if (!is_scalar($hash)) {
            throw new FunctionException(
                'Hashing function must return a scalar value'
            );
        }
        return $hash;
    }

}

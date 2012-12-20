<?php

namespace Ardent;

use Traversable;

class HashSet extends AbstractSet implements Set {

    /**
     * @var array
     */
    private $objects = array();

    /**
     * @var callable
     */
    protected $hashFunction = NULL;

    /**
     * @param callable $hashFunction
     *
     * @return \Ardent\HashSet
     */
    function __construct($hashFunction = NULL) {
        $this->hashFunction = $hashFunction ?: array($this, '__hash');
    }

    /**
     * @param $item
     *
     * @return string
     */
    protected function __hash($item) {
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

    protected function hash($item) {
        return call_user_func($this->hashFunction, $item);
    }

    /**
     * @return void
     */
    function clear() {
        $this->objects = array();
    }

    /**
     * @param $item
     *
     * @return bool
     * @throws FunctionException when the hashing function returns an improper value.
     * @throws TypeException when $item is not the correct type.
     */
    function contains($item) {
        $hash = $this->hash($item);

        if (!is_scalar($hash)) {
            throw new FunctionException(
                'Hashing function must return a scalar value'
            );
        }

        return array_key_exists($hash, $this->objects);
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
     * @param $item
     *
     * @return void
     * @throws FunctionException when the hashing function returns an improper value.
     * @throws TypeException when $item is not the correct type.
     */
    function add($item) {
        $hash = $this->hash($item);

        if (!is_scalar($hash)) {
            throw new FunctionException(
                'Hashing function must return a scalar value'
            );
        }

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
        $hash = $this->hash($item);

        if (!is_scalar($hash)) {
            throw new FunctionException(
                'Hashing function must return a scalar value'
            );
        }

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

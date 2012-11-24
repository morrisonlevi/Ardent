<?php

namespace Spl;

use Traversable;

class HashSet implements Set {

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
     * @return \Spl\HashSet
     */
    public function __construct($hashFunction = NULL) {
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
    public function clear() {
        $this->objects = array();
    }

    /**
     * @param $object
     *
     * @return bool
     * @throws FunctionException when the hashing function returns an improper value.
     * @throws TypeException when $object is not the correct type.
     */
    public function contains($object) {
        $hash = $this->hash($object);

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
    public function isEmpty() {
        return $this->count() === 0;
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count() {
        return count($this->objects);
    }

    /**
     * @param $item
     *
     * @return void
     * @throws FunctionException when the hashing function returns an improper value.
     * @throws TypeException when $item is not the correct type.
     */
    public function add($item) {
        $hash = $this->hash($item);

        if (!is_scalar($hash)) {
            throw new FunctionException(
                'Hashing function must return a scalar value'
            );
        }

        $this->objects[$hash] = $item;
    }

    /**
     * @param Traversable $items
     *
     * @return void
     * @throws TypeException when the Traversable includes an item with an incorrect type.
     */
    public function addAll(Traversable $items) {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * @param $item
     *
     * @return void
     * @throws FunctionException when the hashing function returns an improper value.
     * @throws TypeException when $item is not the correct type.
     */
    public function remove($item) {
        $hash = $this->hash($item);

        if (!is_scalar($hash)) {
            throw new FunctionException(
                'Hashing function must return a scalar value'
            );
        }

        unset($this->objects[$hash]);
    }

    /**
     * @param Traversable $items
     *
     * @return mixed
     * @throws TypeException when the Traversable includes an item with an incorrect type.
     */
    public function removeAll(Traversable $items) {
        foreach ($items as $item) {
            $this->remove($item);
        }
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return HashSetIterator
     */
    function getIterator() {
        return new HashSetIterator($this->objects);
    }

}

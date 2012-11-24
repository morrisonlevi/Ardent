<?php

namespace Spl;

use Traversable;

class SortedMap implements Map {

    private $avl;

    public function __construct($comparator = NULL) {
        if ($comparator === NULL) {
            $comparator = function ($a, $b) {
                if ($a < $b) {
                    return -1;
                } elseif ($b < $a) {
                    return 1;
                } else {
                    return 0;
                }
            };
        }

        $this->avl = new AvlTree(function(Pair $a, Pair $b) use ($comparator) {
            return $comparator($a->first(), $b->first());
        });
    }

    /**
     * @return void
     */
    function clear() {
        $this->avl->clear();
    }

    /**
     * @param $object
     *
     * @return bool
     * @throws TypeException when $object is not the correct type.
     */
    function contains($object) {
        return $this->avl->contains(new Pair($object, NULL));
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return $this->avl->isEmpty();
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset
     *
     * @return boolean
     */
    public function offsetExists($offset) {
        return $this->contains($offset);
    }

    /**
     * @param mixed $offset
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @return mixed
     * @throws KeyException
     */
    public function offsetGet($offset) {
        if (!$this->contains($offset)) {
            throw new KeyException;
        }
        return $this->avl->get($offset);
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value) {
        $this->insert($offset, $value);
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset) {
        $this->remove($offset);
    }

    /**
     * @param $key
     *
     * @return mixed
     * @throws TypeException when the $key is not the correct type.
     */
    function get($key) {
        /**
         * @var Pair $pair
         */
        $pair =  $this->avl->get(new Pair($key, NULL));

        return $pair->second();
    }

    /**
     * @param $key
     * @param mixed $value
     *
     * @return void
     * @throws TypeException when the $key or value is not the correct type.
     */
    function insert($key, $value) {
        $this->avl->add(new Pair($key, $value));
    }

    /**
     * @param Map $items
     *
     * @return void
     * @throws TypeException when the Map does not include an item of the correct type.
     */
    function insertAll(Map $items) {
        foreach ($items as $key) {
            $this->insert($key, $items->get($key));
        }
    }

    /**
     * @param $key
     *
     * @return mixed
     * @throws TypeException when the $key is not the correct type.
     */
    function remove($key) {
        $this->avl->remove(new Pair($key, NULL));
    }

    /**
     * @param Traversable $keys
     *
     * @return mixed
     * @throws TypeException when the Traversable includes an item with an incorrect type.
     */
    function removeAll(Traversable $keys) {
        foreach ($keys as $key) {
            $this->avl->remove($key);
        }
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count() {
        return $this->avl->count();
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return SortedMapIterator
     */
    public function getIterator() {
        return new SortedMapIterator($this->avl->getIterator(), $this->avl->count());
    }
}

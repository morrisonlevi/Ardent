<?php

namespace Spl;

use IteratorAggregate,
    Traversable;

class SortedMap implements IteratorAggregate, Map {

    private $avl;

    private $comparator;

    public function __construct($comparator = NULL) {
        $this->comparator = $comparator !== NULL
            ? $comparator
            : array($this, 'compareItem');
        $this->avl = new AVLTree(array($this, 'comparePair'));
    }

    private function compareItem($a, $b) {
        if ($a < $b) {
            return -1;
        } elseif ($b < $a) {
            return 1;
        } else {
            return 0;
        }
    }

    private function comparePair(Pair $a, Pair $b) {
        return call_user_func($this->comparator, $a->first(), $b->first());
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
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset) {
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
        $pair =  $this->avl->get($key);

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
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator() {
        return new SortedMapIterator($this->avl->getIterator());
    }
}

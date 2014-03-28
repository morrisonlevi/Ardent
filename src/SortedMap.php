<?php

namespace Collections;

class SortedMap implements Map {

    use IteratorCollection;

    private $avl;

    /**
     * @var callable
     */
    private $comparator;

    function __construct($comparator = NULL) {
        $this->comparator = $comparator ?: [$this, 'compareStandard'];

        $this->avl = new SplayTree([$this, 'compareKeys']);
    }

    function compareKeys(Pair $a, Pair $b) {
        return call_user_func($this->comparator, $a->first, $b->first);
    }

    function compareStandard($a, $b) {
        if ($a < $b) {
            return -1;
        } elseif ($b < $a) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * @return void
     */
    function clear() {
        $this->avl->clear();
    }

    /**
     * @param mixed $item
     * @param callable $callback
     *
     * @return bool
     * @throws TypeException when $item is not the correct type.
     */
    function contains($item, callable $callback = NULL) {
        if ($callback === NULL) {
            $callback = [$this, 'compareStandard'];
        }
        foreach ($this->avl as $pair) {
            /**
             * @var Pair $pair
             */
            if (call_user_func($callback, $pair->second, $item) === 0) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return $this->avl->isEmpty();
    }

    /** 
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function first() {
        return $this->avl->first();
    }

    /** 
     * @return mixed
     * @throws EmptyException when the tree is empty
     */
    function last() {
        return $this->avl->last();
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset
     *
     * @return bool
     */
    function offsetExists($offset) {
        return $this->avl->contains(new Pair($offset, NULL));
    }

    /**
     * @param mixed $offset
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @return mixed
     * @throws KeyException
     */
    function offsetGet($offset) {
        if (!$this->offsetExists($offset)) {
            throw new KeyException;
        }

        return $this->get($offset);
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset
     *
     * @return void
     */
    function offsetUnset($offset) {
        $this->remove($offset);
    }

    /**
     * @param $key
     *
     * @return mixed
     * @throws TypeException when the $key is not the correct type.
     * @throws KeyException when the $key is not the correct type.
     */
    function get($key) {
        if (!$this->offsetExists($key)) {
            throw new KeyException;
        }

        /**
         * @var Pair $pair
         */
        $pair = $this->avl->get(new Pair($key, NULL));

        return $pair->second;
    }

    /**
     * Note that if the key is considered equal to an already existing key in
     * the map that it's value will be replaced with the new one.
     *
     * @param $key
     * @param mixed $value
     *
     * @return void
     * @throws TypeException when the $key or value is not the correct type.
     */
    function set($key, $value) {
        $this->avl->add(new Pair($key, $value));
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
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->avl->count();
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return SortedMapIterator
     */
    function getIterator() {
        return new SortedMapIterator($this->avl->getIterator(), $this->avl->count());
    }
}

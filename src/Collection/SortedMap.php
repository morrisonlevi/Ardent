<?php

namespace Ardent\Collection;


class SortedMap implements Map {

    /**
     * @var BinarySearchTree
     */
    private $avl;

    /**
     * @var Callable
     */
    private $comparator;


    function __construct($comparator = null) {
        $this->comparator = $comparator ?: __NAMESPACE__ . '\\compare';

        $this->avl = new AvlTree([$this, 'compareKeys']);
    }


    function compareKeys(Pair $a, Pair $b) {
        return call_user_func($this->comparator, $a->first, $b->first);
    }


    /**
     * @return void
     */
    function clear() {
        $this->avl->clear();
    }


    /**
     * @param Mixed $item
     * @param Callable $callback
     *
     * @return Bool
     */
    function contains($item, Callable $callback = null) {
        if ($callback === null) {
            $callback = __NAMESPACE__ . '\\compare';
        }
        foreach ($this->avl as $pair) {
            /**
             * @var Pair $pair
             */
            if (call_user_func($callback, $pair->second, $item) === 0) {
                return true;
            }
        }

        return false;
    }


    /**
     * @return Bool
     */
    function isEmpty() {
        return $this->avl->isEmpty();
    }


    /**
     * @return Mixed
     */
    function firstKey() {
        return $this->avl->first()->first;
    }


    /**
     * @return Mixed
     */
    function lastKey() {
        return $this->avl->last()->first;
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param Mixed $offset
     *
     * @return Bool
     */
    function offsetExists($offset) {
        return $this->avl->contains(new Pair($offset, null));
    }


    /**
     * @param Mixed $offset
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @return Mixed
     */
    function offsetGet($offset) {
        assert($this->offsetExists($offset));

        return $this->get($offset);
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param Mixed $offset
     * @param Mixed $value
     *
     * @return void
     */
    function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param Mixed $offset
     *
     * @return void
     */
    function offsetUnset($offset) {
        $this->remove($offset);
    }


    /**
     * @param $key
     *
     * @return Mixed
     */
    function get($key) {
        assert($this->offsetExists($key));

        /**
         * @var Pair $pair
         */
        $pair = $this->avl->get(new Pair($key, null));

        return $pair->second;
    }


    /**
     * Note that if the key is considered equal to an already existing key in
     * the map that it's value will be replaced with the new one.
     *
     * @param $key
     * @param Mixed $value
     *
     * @return void
     */
    function set($key, $value) {
        $this->avl->add(new Pair($key, $value));
    }


    /**
     * @param $key
     *
     * @return Mixed
     */
    function remove($key) {
        $this->avl->remove(new Pair($key, null));
    }


    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return Int
     */
    function count() {
        return $this->avl->count();
    }


    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return SortedMapIterator
     */
    function getIterator() {
        return new SortedMapIterator(
            new InOrderIterator($this->avl->toBinaryTree(), $this->avl->count()),
            $this->avl->count()
        );
    }


}

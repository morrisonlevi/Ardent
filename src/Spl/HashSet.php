<?php

namespace Spl;

use Iterator,
Traversable;

class HashSet implements Iterator, Set {

    private $objects = array();

    /**
     * @param $item
     *
     * @return string
     */
    private function hash($item) {
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
    public function clear() {
        $this->objects = array();
    }

    /**
     * @param $object
     *
     * @return bool
     * @throws InvalidTypeException when $object is not the correct type.
     */
    public function contains($object) {
        return array_key_exists($this->hash($object), $this->objects);
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
     * @throws InvalidTypeException when $item is not the correct type.
     */
    public function add($item) {
        $this->objects[$this->hash($item)] = $item;
    }

    /**
     * @param Traversable $items
     *
     * @return void
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
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
     * @throws InvalidTypeException when $item is not the correct type.
     */
    public function remove($item) {
        unset($this->objects[$this->hash($item)]);
    }

    /**
     * @param Traversable $items
     *
     * @return mixed
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
     */
    public function removeAll(Traversable $items) {
        foreach ($items as $item) {
            $this->remove($item);
        }
    }

    /**
     * @param Traversable $items
     *
     * @return mixed
     * @throws InvalidTypeException when the Traversable does not include an item of the correct type.
     */
    public function retainAll(Traversable $items) {
        $this->clear();
        $this->addAll($items);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    public function current() {
        return current($this->objects);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next() {
        next($this->objects);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return string
     */
    public function key() {
        return key($this->objects);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid() {
        return key($this->objects) !== NULL;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind() {
        reset($this->objects);
    }

}

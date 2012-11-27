<?php

namespace Spl;

class ArrayStackIterator implements StackIterator {

    /**
     * @var array
     */
    private $stack;

    /**
     * @var int
     */
    private $key = NULL;

    /**
     * @param array $stack
     */
    function __construct(array $stack) {
        $this->stack = $stack;
        $this->rewind();
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        return current($this->stack);
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        prev($this->stack);
        $this->key++;
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return int|null
     */
    function key() {
        return $this->key;
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return key($this->stack) !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        end($this->stack);

        $this->key = count($this->stack) > 0
            ? 0
            : NULL;
    }

    function count() {
        return count($this->stack);
    }

}

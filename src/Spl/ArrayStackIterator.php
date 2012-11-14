<?php

namespace Spl;

class ArrayStackIterator implements StackIterator {

    /**
     * @var array
     */
    private $stack;

    function __construct(array $stack) {
        $this->stack = $stack;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    public function current() {
        return current($this->stack);
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    public function next() {
        prev($this->stack);
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    public function key() {
        $count = count($this->stack);
        if ($count === 0) {
            return NULL;
        }
        return count($this->stack) - key($this->stack) - 1;
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid() {
        return key($this->stack) !== NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    public function rewind() {
        end($this->stack);
    }

    function count() {
        return count($this->stack);
    }

}

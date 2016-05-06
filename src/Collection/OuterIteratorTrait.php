<?php

namespace Ardent\Collection;

use Iterator;


trait OuterIteratorTrait {


    /**
     * @return Iterator
     */
    abstract function getInnerIterator();


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return Mixed
     */
    function current() {
        return $this->getInnerIterator()->current();
    }


    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->getInnerIterator()->next();
    }


    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return Mixed
     */
    function key() {
        return $this->getInnerIterator()->key();
    }


    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return Bool
     */
    function valid() {
        return $this->getInnerIterator()->valid();
    }


    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->getInnerIterator()->rewind();
    }


}

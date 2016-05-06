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
     * @return mixed
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
     * @return mixed
     */
    function key() {
        return $this->getInnerIterator()->key();
    }


    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool
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

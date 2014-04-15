<?php

namespace Collections;


class LinkedList implements \ArrayAccess, \Countable, Enumerator {

    use EmptyGuard;
    use IteratorCollection;

    private $head;
    private $tail;
    private $size = 0;
    private $current;
    private $offset = -1;


    function __construct() {
        $this->head = $head = new LinkedTerminalNode();
        $this->tail = $tail = new LinkedTerminalNode();

        $head->setNext($tail);
        $tail->setPrev($head);

        $this->current = $this->head;
    }


    function isEmpty() {
        return $this->size === 0;
    }


    function push($value) {
        $this->insertBetween($this->tail->prev(), $this->tail, $value);
        $this->offset = $this->size - 1;
    }


    function unshift($value) {
        $this->insertBetween($this->head, $this->head->next(), $value);
        $this->offset = 0;
    }


    function pop() {
        $this->emptyGuard(__METHOD__);
        $n = $this->seekTail();
        $this->removeNode($n);
        return $n->value();
    }


    function shift() {
        $this->emptyGuard(__METHOD__);
        $n = $this->seekHead();
        $this->removeNode($n);
        return $n->value();
    }


    function first() {
        $this->emptyGuard(__METHOD__);
        return $this->seekHead()->value();
    }


    function last() {
        $this->emptyGuard(__METHOD__);
        return $this->seekTail()->value();
    }


    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->size;
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param int $offset
     * @return boolean
     * @throws TypeException
     */
    function offsetExists($offset) {
        $index = intGuard($offset);
        return $index >= 0 && $index < $this->count();
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param int $offset
     * @return mixed
     * @throws IndexException
     * @throws TypeException
     */
    function offsetGet($offset) {
        $index = intGuard($offset);
        $this->indexGuard($index, __METHOD__);
        $n = $this->seekTo($index);
        return $n->value();
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param int|null $offset
     * @param mixed $value
     * @return void
     * @throws IndexException
     * @throws TypeException
     */
    function offsetSet($offset, $value) {
        if ($offset === null) {
            $this->push($value);
            return;
        }
        $index = intGuard($offset);
        $this->indexGuard($index, __METHOD__);
        $n = $this->seekTo($index);
        $n->setValue($value);
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param int $offset
     * @return void
     * @throws TypeException
     */
    function offsetUnset($offset) {
        $index = intGuard($offset);
        if ($this->offsetExists($index)) {
            $n = $this->seekTo($index);
            $this->removeNode($n);
            $this->current = $n->prev();
            $this->offset--;
        }
    }


    /**
     * @param int $position
     * @param mixed $value
     * @return void
     * @throws IndexException
     * @throws TypeException
     */
    function insertBefore($position, $value) {
        $index = intGuard($position);
        $this->indexGuard($index, __METHOD__);
        $n = $this->seekTo($index);
        $this->insertBetween($n->prev(), $n, $value);
        $this->current = $this->current->next();
        $this->offset++;
    }


    /**
     * @param int $position
     * @param mixed $value
     * @return void
     * @throws IndexException
     * @throws TypeException
     */
    function insertAfter($position, $value) {
        $index = intGuard($position);
        $this->indexGuard($index, __METHOD__);
        $n = $this->seekTo($index);
        $this->insertBetween($n, $n->next(), $value);
        $this->current = $this->current->prev();
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        /**
         * @var LinkedDataNode $n
         */
        $n = $this->current;
        return $n->value();
    }


    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        $this->forward();
    }


    /**
     * @return void
     */
    function prev() {
        $this->backward();
    }


    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed
     */
    function key() {
        return $this->offset;
    }


    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    function valid() {
        return $this->current instanceof LinkedDataNode;
    }


    /**
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void
     */
    function rewind() {
        $this->current = $this->head;
        $this->offset = -1;
        $this->forward();
    }


    /**
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @param int $position
     * @return mixed
     * @throws IndexException
     * @throws TypeException
     */
    function seek($position) {
        $index = intGuard($position);
        $this->indexGuard($index, __METHOD__);
        switch ($index) {
            case 0:
                $n = $this->seekHead();
                break;
            case $this->size-1:
                $n = $this->seekTail();
                break;

            default:
                $n = $this->seekTo($index);
        }
        return $n->value();
    }


    /**
     * @param $value
     * @param callable $f
     * @return int
     */
    function indexOf($value, callable $f = null) {
        $equal = $f ?: '\Collections\equal';

        $filter = $this->filter(function($item) use ($equal, $value) {
            return $equal($item, $value);
        });

        foreach ($filter as $key => $value) {
            return $key;
        }

        return -1;
    }


    /**
     * @param $value
     * @param callable $f [optional]
     * @return bool
     */
    function contains($value, callable $f = null) {
        return $this->indexOf($value, $f) >= 0;
    }


    /**
     * Extract the elements after the first of a list, which must be non-empty.
     * @return LinkedList
     * @throws EmptyException
     */
    function tail() {
        $this->emptyGuard(__METHOD__);
        return $this->copyFromContext($this->head->next()->next());
    }


    function __clone() {
        $list = $this->copyFromContext($this->head->next());
        $this->head = $list->head;
        $this->tail = $list->tail;
        $this->current = $this->head;
        $this->offset = -1;
        $this->size = $list->size;
    }


    private function copyFromContext(LinkedNode $context) {
        $list = new self();
        for ($n = $context; $n !== $this->tail; $n = $n->next()) {
            /**
             * @var LinkedDataNode $n
             */
            $list->push($n->value());
        }
        return $list;
    }


    private function removeNode(LinkedNode $n) {
        $prev = $n->prev();
        $next = $n->next();

        $prev->setNext($next);
        $next->setPrev($prev);
        $this->size--;
    }


    private function insertBetween(LinkedNode $a, LinkedNode $b, $value) {
        $n = new LinkedDataNode($value);
        $a->setNext($n);
        $b->setPrev($n);

        $n->setPrev($a);
        $n->setNext($b);

        $this->current = $n;
        $this->size++;
    }


    private function forward() {
        $this->current = $this->current->next();
        $this->offset++;
    }


    private function backward() {
        $this->current = $this->current->prev();
        $this->offset--;
    }


    /**
     * @return LinkedDataNode
     */
    private function seekTail() {
        $this->offset = $this->size - 1;
        $this->current = $this->tail->prev();
        return $this->current;
    }


    /**
     * @return LinkedDataNode
     */
    private function seekHead() {
        $this->offset = 0;
        return $this->current = $this->head->next();
    }


    /**
     * @param $offset
     * @return LinkedDataNode
     */
    private function seekTo($offset) {
        $diff = $this->offset - $offset;
        $action = $diff < 0 ? 'forward' : 'backward';
        $n = abs($diff);
        for ($i = 0; $i < $n; $i++) {
            $this->$action();
        }
        return $this->current;
    }


    private function indexGuard($offset, $method) {
        if (!$this->offsetExists($offset)) {
            throw new IndexException(
                "{$method} was called with invalid index: {$offset}"
            );
        }
    }


}
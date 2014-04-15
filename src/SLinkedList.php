<?php

namespace Collections;


class SLinkedList implements \ArrayAccess, \Countable, Enumerator {

    use EmptyGuard;
    use IteratorCollection;

    private $head;
    private $tail;
    private $size = 0;
    private $current;
    private $offset = -1;


    function __construct() {
        $this->head = $head = new STerminalNode();
        $this->tail = $tail = new STerminalNode();

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
     */
    function offsetExists($offset) {
        return $offset >= 0 && $offset < $this->count();
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset
     * @return mixed
     * @throws IndexException
     */
    function offsetGet($offset) {
        $this->indexGuard($offset, __METHOD__);
        $n = $this->seekTo($offset);
        return $n->value();
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param int|null $offset
     * @param mixed $value
     * @return void
     * @throws IndexException
     */
    function offsetSet($offset, $value) {
        if ($offset === null) {
            $this->push($value);
            return;
        }
        $this->indexGuard($offset, __METHOD__);
        $n = $this->seekTo($offset);
        $n->setValue($value);
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset
     * @return void
     */
    function offsetUnset($offset) {
        if ($this->offsetExists($offset)) {
            $n = $this->seekTo($offset);
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
     */
    function insertBefore($position, $value) {
        $this->indexGuard($position, __METHOD__);
        $n = $this->seekTo($position);
        $this->insertBetween($n->prev(), $n, $value);
        $this->current = $this->current->next();
        $this->offset++;
    }


    /**
     * @param int $position
     * @param mixed $value
     * @return void
     * @throws IndexException
     */
    function insertAfter($position, $value) {
        $this->indexGuard($position, __METHOD__);
        $n = $this->seekTo($position);
        $this->insertBetween($n, $n->next(), $value);
        $this->current = $this->current->prev();
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed
     */
    function current() {
        /**
         * @var SDataNode $n
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
        return $this->current instanceof SDataNode;
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
     */

    function seek($position) {
        $this->indexGuard($position, __METHOD__);
        switch ($position) {
            case 0:
                $n = $this->seekHead();
                break;
            case $this->size-1:
                $n = $this->seekTail();
                break;

            default:
                $n = $this->seekTo($position);
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
     * @return SLinkedList
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


    private function copyFromContext(SNode $context) {
        $list = new self();
        for ($n = $context; $n !== $this->tail; $n = $n->next()) {
            /**
             * @var SDataNode $n
             */
            $list->push($n->value());
        }
        return $list;
    }


    private function removeNode(SNode $n) {
        $prev = $n->prev();
        $next = $n->next();

        $prev->setNext($next);
        $next->setPrev($prev);
        $this->size--;
    }


    private function insertBetween(SNode $a, SNode $b, $value) {
        $n = new SDataNode($value);
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
     * @return SDataNode
     */
    private function seekTail() {
        $this->offset = $this->size - 1;
        $this->current = $this->tail->prev();
        return $this->current;
    }


    /**
     * @return SDataNode
     */
    private function seekHead() {
        $this->offset = 0;
        return $this->current = $this->head->next();
    }


    /**
     * @param $offset
     * @return SDataNode
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
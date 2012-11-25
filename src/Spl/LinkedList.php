<?php

namespace Spl;

use ArrayAccess;

class LinkedList implements Seekable, ArrayAccess, Collection {

    /**
     * @var LinkedListNode
     */
    private $head;

    /**
     * @var LinkedListNode
     */
    private $tail;

    /**
     * @var int
     */
    private $size = 0;

    /**
     * @var LinkedListNode
     */
    private $currentNode;

    /**
     * @var int
     */
    private $currentOffset;

    /**
     * @return LinkedList
     */
    function __clone() {
        return $this->copyRange(0, $this->size);
    }

    /**
     * @param mixed $value
     * @param callable $callback [optional]
     *
     * @return bool
     * @throws TypeException when $object is not the correct type.
     */
    function contains($value, $callback = NULL) {
        if ($this->head === NULL) {
            return FALSE;
        }

        return $this->indexOf($value, $callback) !== -1;
    }

    /**
     * @param $value
     * @param callable $callback [optional]
     * @return int
     */
    function indexOf($value, $callback = NULL) {
        if ($this->head === NULL) {
            return -1;
        }

        $areEqual = $callback ?: array($this, '__equals');

        if (call_user_func($areEqual, $this->currentNode->value, $value)) {
            return $this->currentOffset;
        }

        $offset = 0;
        for ($node = $this->head; $node !== NULL; $node = $node->next, $offset++) {
            if (call_user_func($areEqual, $value, $node->value)) {
                $this->currentOffset = $offset;
                $this->currentNode = $node;
                return $offset;
            }
        }

        return -1;
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return $this->head === NULL;
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return LinkedListIterator
     */
    function getIterator() {
        return new LinkedListIterator(clone $this);
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param int $offset
     * @return boolean
     */
    function offsetExists($offset) {
        return $offset < $this->size && $offset >= 0;
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param int $offset
     * @return mixed
     * @throws IndexException
     */
    function offsetGet($offset) {
        if (!$this->offsetExists($offset)) {
            throw new IndexException;
        }

        $this->__seek($offset);

        return $this->currentNode->value;
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param int $offset
     * @param mixed $value
     * @return void
     * @throws IndexException
     */
    function offsetSet($offset, $value) {
        if ($offset === NULL) {
            $this->pushBack($value);
            return;
        }

        if (!$this->offsetExists($offset)) {
            throw new IndexException;
        }

        $this->__seek($offset);
        $this->currentNode->value = $value;
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param int $offset
     * @return void
     */
    function offsetUnset($offset) {
        if (!$this->offsetExists($offset)) {
            return;
        }

        $this->__seek($offset);
        $this->removeNode($this->currentNode, $offset);
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->size;
    }

    /**
     * @param mixed $object
     *
     * @throws TypeException if $object is not the correct type.
     * @throws FullException if the LinkedList is full.
     * @return void
     */
    function pushBack($object) {
        $node = new LinkedListNode($object);
        $this->size++;

        if ($this->tail === NULL) {
            $this->tail
                = $this->head
                = $this->currentNode
                = $node;
            return;
        }

        $tail = $this->tail;
        $tail->next = $node;
        $node->prev = $tail;
        $this->tail = $node;
    }


    /**
     * @return mixed
     * @throws EmptyException if the LinkedList is empty.
     */
    function popFront() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        $value = $this->head->value;
        $this->removeNode($this->head, 0);
        return $value;
    }

    /**
     * @return mixed
     * @throws EmptyException if the LinkedList is empty.
     */
    function peekFront() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        return $this->head->value;
    }

    /**
     * @param mixed $value
     * @return void
     * @throws FullException if the LinkedList is full.
     */
    function pushFront($value) {
        $node = new LinkedListNode($value);

        if ($this->head === NULL) {
            $this->head
                = $this->tail
                = $this->currentNode
                = $node;
            return;
        }

        $this->head->prev = $node;
        $node->next = $this->head;
        $this->head = $node;

        $this->currentOffset = 0;
    }

    /**
     * @throws EmptyException if the LinkedList is empty.
     * @return mixed
     */
    function popBack(){
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        $value = $this->tail->value;
        $this->removeNode($this->tail, $this->size - 1);

        $this->currentNode = $this->tail;
        $this->currentOffset = $this->size - 1;

        return $value;
    }

    /**
     * @throws EmptyException if the LinkedList is empty.
     * @return mixed
     */
    function peekBack(){
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        return $this->tail->value;
    }

    function insertAfter($offset, $value) {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }
        if (!$this->offsetExists($offset)) {
            throw new IndexException;
        }

        if ($offset == $this->size - 1) {
            $this->pushBack($value);
            $this->currentNode = $this->tail->prev;
            $this->currentOffset = $this->size - 2;
            return;
        }

        $this->__seek($offset);

        $newNode = new LinkedListNode($value);
        $this->currentNode->next
            = $this->currentNode->next->prev
            = $newNode;

        $this->size++;
    }

    function insertBefore($offset, $value) {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        if (!$this->offsetExists($offset)) {
            throw new IndexException;
        }

        if ($offset == 0) {
            $this->pushFront($value);
            $this->currentNode = $this->head->next;
            $this->currentOffset = 1;
            return;
        }

        $this->__seek($offset);

        $newNode = new LinkedListNode($value);

        $this->currentNode->prev
            = $this->currentNode->prev->next
            = $newNode;

        $this->currentOffset++;
        $this->size++;

    }

    /**
     * @param int $offset
     * @return void
     * @throws IndexException
     */
    public function seek($offset) {
        if (!$this->offsetExists($offset)) {
            throw new IndexException;
        }

        $this->__seek($offset);
    }

    private function __seek($offset) {

        if ($offset == $this->currentOffset) {
            return;
        }

        if ($offset == 0) {
            $this->currentOffset = 0;
            $this->currentNode = $this->head;
            return;
        }

        if ($offset == $this->size - 1) {
            $this->currentOffset = $this->size - 1;
            $this->currentNode = $this->tail;
            return;
        }

        $node = $this->currentNode;

        $currentDiff = $this->currentOffset - $offset;
        if ($currentDiff < 0) {
            for ($i = $this->currentOffset; $i < $offset; $i++) {
                $node = $node->next;
            }
        } else {
            for ($i = $this->currentOffset; $i > $currentDiff; $i--) {
                $node = $node->prev;
            }
        }

        $this->currentOffset = $offset;
        $this->currentNode = $node;
    }


    private function removeNode(LinkedListNode $node, $offset) {
        $current = NULL;

        if ($node->next !== NULL) {
            $this->currentNode = $node->next;
            $this->currentNode->prev = $node->prev;
        } else {
            $this->currentNode = $this->tail = $node->prev;
            $this->currentOffset = $offset - 1;
        }

        if ($node->prev !== NULL) {
            $node->prev->next = $node->next;
        } else {
            $this->head = $node->next;
        }

        $this->size--;
    }

    private function copyRange($start, $finish) {
        $that = new LinkedList();
        $that->size = $finish - $start;

        $this->__seek($start);

        for ($node = $this->currentNode, $i = $start; $i < $finish; $i++, $node = $node->next) {
            $that->pushBack($node->value);
        }

        return $that;
    }

    /**
     * PhpStorm thinks this method is unused, but it is used in indexOf.
     *
     * @param $a
     * @param $b
     * @return bool
     */
    private function __equals($a, $b) {
        return $a == $b;
    }

}
